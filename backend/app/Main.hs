{-# LANGUAGE OverloadedStrings #-}
{-# OPTIONS_GHC -Wno-name-shadowing #-}
import Web.Scotty
import Web.Scotty.Cookie
import Data.Aeson (FromJSON, ToJSON (toJSON))
import GHC.Generics (Generic)
import Network.Wai.Handler.Warp (defaultSettings, setPort)
import Network.Wai.Handler.WarpTLS (runTLS, tlsSettings)
--import Network.Wai.Middleware.Cors (simpleCors)
import Network.Wai.Middleware.RequestLogger (logStdoutDev)
import Database.SQLite.Simple
import Text.StringRandom
import Data.Text
import Data.Text.Lazy as LT
import Prelude hiding (id)
-- disconnection events, real-time application
data User = User { userName :: String, gameId :: Int } deriving (Show, Generic)
instance ToJSON User
instance FromJSON User

newtype SuccessR = SuccessR { success :: Bool } deriving (Show, Generic)
instance ToJSON SuccessR

data Players = Players { name :: String, dice :: Int, guess :: Int } deriving (Show, Generic)
instance ToJSON Players

data Session = Session String String Int String deriving (Show)

data GetGame = GetGame Int Int deriving (Show)
instance FromRow GetGame where
    fromRow = GetGame <$> field <*> field
instance ToRow GetGame where
    toRow (GetGame gameid participants) = toRow (gameid, participants)

data RespGame = RespGame { gmid :: Int, participants :: Int } deriving (Show, Generic)
instance ToJSON RespGame

data PlayerList = PlayerList Data.Text.Text Data.Text.Text Int deriving (Show)
instance FromRow PlayerList where
    fromRow = PlayerList <$> field <*> field <*> field
instance ToRow PlayerList where
    toRow (PlayerList name numbers guess) = toRow (name, numbers, guess)


main :: IO()
main = do
    -- connect to the database in memory
    conn <- open ":memory:"
    -- create session table (id :: INT, sesid :: TEXT, name :: TEXT, gameid :: INT, numbers :: TEXT, lastguess)
    execute_ conn "CREATE TABLE IF NOT EXISTS session (id INTEGER PRIMARY KEY, sesid TEXT UNIQUE, name TEXT, gameid INT, numbers TEXT, lastguess INT)"
    --execute_ conn "CREATE TABLE session (id INTEGER PRIMARY KEY, sesid TEXT, name TEXT, gameid INT, numbers TEXT)"
    -- create game table (id :: INT, gameid :: INT, participants :: INT, ongoing :: BOOL, host :: TEXT)
    execute_ conn "CREATE TABLE IF NOT EXISTS game (id INTEGER PRIMARY KEY, gameid INT UNIQUE, participants INT, ongoing BOOL, host TEXT, curPlayer TEXT)"
    --execute_ conn "CREATE TABLE game (id INTEGER PRIMARY KEY, gameid INT UNIQUE, participants INT, ongoing BOOL, host TEXT)"
    -- start web server
    --scottyTLS 3000 "example.key" "example.crt" $ routes conn
    let tlsConfig = tlsSettings "your.crt" "your.key"
        config    = setPort 3000 defaultSettings
    waiApp <- scottyApp $ routes conn
    runTLS tlsConfig config (logStdoutDev waiApp)
    -- close the connection
    close conn

routes :: Connection -> ScottyM()
routes conn = do
    -- Need to enable CORS to allow the frontend to access the backend
    -- middleware simpleCors
    -- Lisening on:
    get "/" $ giveWelcomePage conn
    get "/multiplayer/lobby" $ giveLobbyPage conn
    post "/api/join" $ enlistUser conn
    post "/api/leave" $ deleteUser conn
    post "/api/players" $ listPlayers conn
    post "/api/games" $ listGames conn

giveWelcomePage :: Connection -> ActionM()
giveWelcomePage conn = do
    sessionid <- getCookie "SESSIONID"
    case sessionid of
        Nothing -> file "../frontend/index.html"
        Just sessionid -> do
            isNew <- liftIO $ isNewSID conn sessionid
            if not isNew then file "../frontend/multiplayer/lobby.html" else do
                deleteCookie "SESSIONID"
                file "../frontend/index.html"

giveLobbyPage :: Connection -> ActionM()
giveLobbyPage conn = do
    sessionid <- getCookie "SESSIONID"
    case sessionid of
        Nothing -> file "../frontend/index.html"
        Just sessionid -> do
            isNew <- liftIO $ isNewSID conn sessionid
            if isNew then file "../frontend/index.html" else file "../frontend/multiplayer/lobby.html"

deleteUser :: Connection -> ActionM()
deleteUser conn = do
    sessionid <- getCookie "SESSIONID"
    case sessionid of
        Nothing -> json $ SuccessR False
        Just sessionid -> do
            liftIO $ removeDb conn sessionid
            json $ SuccessR True

removeDb :: Connection -> Data.Text.Text -> IO()
removeDb conn sessionid = do
    execute conn "DELETE FROM session WHERE sesid = ?" (Only sessionid)
    --execute_ conn ""

listPlayers :: Connection -> ActionM()
listPlayers conn = do
    sessionid <- getCookie "SESSIONID"
    case sessionid of
        Nothing -> json $ SuccessR False
        Just sessionid -> do
            isNew <- liftIO $ isNewSID conn sessionid
            if isNew then json $ SuccessR False else do
                --internal server error here:
                players <- liftIO $ listUsers conn sessionid
                json $ [Players (Data.Text.unpack name) (Data.Text.length cubes) guess | PlayerList name cubes guess <- players] 

listUsers :: Connection -> Data.Text.Text -> IO [PlayerList]
listUsers conn sessionid = do
    gameid <- query conn "SELECT gameid FROM session WHERE sesid = ?" (Only $ sqlFilter sessionid) :: IO [Only Int]
    query conn "SELECT name, numbers, lastguess FROM session WHERE gameid = ? and sesid != ?" (clear gameid, Data.Text.unpack $ sqlFilter sessionid) :: IO [PlayerList] where
        clear :: [Only Int] -> Int
        clear [a] = fromOnly a
        clear _ = 0

listGames :: Connection -> ActionM()
listGames conn = do
    games <- liftIO $ getGames conn
    json $ toResp games where
        toResp :: [GetGame] -> [RespGame]
        toResp [] = []
        toResp (GetGame gameid participants:xs) = RespGame gameid participants : toResp xs

getGames :: Connection -> IO [GetGame]
getGames conn = do
    query_ conn "SELECT gameid, participants FROM game WHERE ongoing = False" :: IO [GetGame]

enlistUser :: Connection -> ActionM()
enlistUser conn = do
    user <- jsonData :: ActionM User
    sessionId <- liftIO $ initUser conn $ sanitizeUser user
    let cookie = "SESSIONID=\"" <> sessionId <> "\"; Path=/; SameSite=Lax" -- custom path
    setHeader "Set-Cookie" (LT.fromStrict cookie)
    json $ SuccessR True

initUser :: Connection -> User -> IO Data.Text.Text
initUser conn (User name 0) = do
    sesid <- getNewSessionId conn
    gameId <- getFreeId conn
    execute conn "INSERT INTO session (sesid, name, gameid, numbers, lastguess) VALUES (?, ?, ?, ?, ?)" (sesid :: Data.Text.Text, name :: String, gameId :: Int, "00000" :: String, 0 :: Int)
    execute conn "INSERT INTO game (gameid, participants, ongoing, host, curPlayer) VALUES (?, ?, ?, ?, ?)" (gameId :: Int, 1 :: Int, False :: Bool, name :: String, "" :: String)
    return sesid
initUser conn (User name gameId) = do
    sesid <- getNewSessionId conn
    execute conn "INSERT INTO session (sesid, name, gameid, numbers, lastguess) VALUES (?, ?, ?, ?, ?)" (sesid :: Data.Text.Text, name :: String, gameId :: Int, "00000" :: String, 0 :: Int)
    execute conn "UPDATE game SET participants = participants + 1 WHERE gameid = ?" (Only gameId)
    return sesid

getFreeId :: Connection -> IO Int
getFreeId conn = do
    games <- query_ conn "SELECT gameid FROM game ORDER BY gameid DESC LIMIT 1" :: IO [Only Int]
    case games of
        [id] -> return (fromOnly id + 1)
        _ -> return 1

sanitizeUser :: User -> User
sanitizeUser (User name gameId) = User (Data.Text.unpack $ sqlFilter $ Data.Text.pack name) gameId

sqlFilter :: Data.Text.Text -> Data.Text.Text
sqlFilter quer = Data.Text.replace "," "" $ Data.Text.replace "(" "" $ Data.Text.replace ")" "" $ Data.Text.replace "\"" "" $ Data.Text.replace "'" "" $ Data.Text.replace "\\" "" quer

getNewSessionId :: Connection -> IO Data.Text.Text
getNewSessionId conn = do
    sesid <- stringRandomIO $ Data.Text.replicate 192 "[A-Za-z0-9]" -- should be enought for any number of users
    isValid <- isNewSID conn sesid
    if isValid then return sesid else getNewSessionId conn

-- may be an infinite loop if there are no free unique ids, thats approximately 10^57 user
isNewSID :: Connection -> Data.Text.Text -> IO Bool
isNewSID conn sesid = do
    sessions <- query conn "SELECT sesid FROM session WHERE sesid = ?" (Only $ sqlFilter sesid) :: IO [Only Data.Text.Text]
    return $ isEmpty sessions where
        isEmpty :: [Only Data.Text.Text] -> Bool
        isEmpty [] = True
        isEmpty _ = False

