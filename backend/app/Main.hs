{-# LANGUAGE OverloadedStrings #-}
-- Scotty tls failed me, switching to servant
import Web.Scotty
import Web.Scotty.Cookie
--import Web.Scotty.TLS
import Data.Aeson (FromJSON, ToJSON)
import GHC.Generics (Generic)
import Network.Wai.Handler.Warp (defaultSettings, setPort)
import Network.Wai.Handler.WarpTLS (runTLS, tlsSettings)
import Network.Wai.Middleware.Cors (simpleCors)
import Network.Wai.Middleware.RequestLogger (logStdoutDev)
import Database.SQLite.Simple
import Text.StringRandom
import Data.Text
import Prelude hiding (id)

data User = User { userName :: String, gameId :: Int } deriving (Show, Generic)
instance ToJSON User
instance FromJSON User

newtype SuccessR = SuccessR { success :: Bool } deriving (Show, Generic)
instance ToJSON SuccessR

data Session = Session String String Int String deriving (Show)

data GetGame = GetGame Int Int deriving (Show)
instance FromRow GetGame where
    fromRow = GetGame <$> field <*> field
instance ToRow GetGame where
    toRow (GetGame id_ gameid) = toRow (id_, gameid)

--instance FromRow TestField where
--    fromRow = TestField <$> field <*> field
--
--instance ToRow TestField where
--    toRow (TestField id_ str) = toRow (id_, str)


main :: IO()
main = do
    -- connect to the database in memory
    conn <- open ":memory:"
    -- create session table (id :: INT, sesid :: TEXT, name :: TEXT, gameid :: INT, numbers :: TEXT)
    execute_ conn "CREATE TABLE IF NOT EXISTS session (id INTEGER PRIMARY KEY, sesid TEXT, name TEXT, gameid INT, numbers TEXT)"
    --execute_ conn "CREATE TABLE session (id INTEGER PRIMARY KEY, sesid TEXT, name TEXT, gameid INT, numbers TEXT)"
    -- create game table (id :: INT, gameid :: INT, participants :: INT, ongoing :: BOOL, host :: TEXT)
    execute_ conn "CREATE TABLE IF NOT EXISTS game (id INTEGER PRIMARY KEY, gameid INT UNIQUE, participants INT, ongoing BOOL, host TEXT)"
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
    middleware simpleCors
    -- Lisening on:
    get "/" giveWelcomePage
    post "/join" $ enlistUser conn

giveWelcomePage :: ActionM()
giveWelcomePage = do
    file "../frontend/index.html"

enlistUser :: Connection -> ActionM()
enlistUser conn = do
    user <- jsonData :: ActionM User
    sessionId <- liftIO $ initUser conn $ sanitizeUser user
    setSimpleCookie "SESSIONID" sessionId
    json $ SuccessR True

-- can't get this right
initUser :: Connection -> User -> IO Data.Text.Text
initUser conn (User name 0) = do
    sesid <- getNewSessionId conn
    gameId <- getFreeId conn
    execute conn "INSERT INTO session (sesid, name, gameid, numbers) VALUES (?, ?, ?, ?)" (sesid :: Data.Text.Text, name :: String, gameId :: Int, "00000" :: String)
    execute conn "INSERT INTO game (gameid, participants, ongoing) VALUES (?, ?, ?)" (gameId :: Int, 1 :: Int, False :: Bool)
    return sesid
initUser conn (User name gameId) = do
    sesid <- getNewSessionId conn
    execute conn "INSERT INTO session (sesid, name, gameid, numbers) VALUES (?, ?, ?, ?)" (sesid :: Data.Text.Text, name :: String, gameId :: Int, "00000" :: String)
    execute conn "UPDATE game SET participants = participants + 1 WHERE gameid = ?" (Only gameId)
    return sesid

sanitizeUser :: User -> User
sanitizeUser (User name gameId) = User (Data.Text.unpack $ sqlFilter $ Data.Text.pack name) gameId

sqlFilter :: Data.Text.Text -> Data.Text.Text
sqlFilter query = Data.Text.replace "," "" $ Data.Text.replace "(" "" $ Data.Text.replace ")" "" $ Data.Text.replace "\"" "" $ Data.Text.replace "'" "" $ Data.Text.replace "\\" "" query

getNewSessionId :: Connection -> IO Data.Text.Text
getNewSessionId conn = do
    sesid <- stringRandomIO $ Data.Text.replicate 192 "[A-Za-z0-9]" -- should be enought for any number of users
    isValid <- checkSessionId conn sesid
    if isValid then return sesid else getNewSessionId conn

-- may be an infinite loop if there are no free unique ids, thats approximately 10^57 user
checkSessionId :: Connection -> Data.Text.Text -> IO Bool
checkSessionId conn sesid = do
    sessions <- query conn "SELECT sesid FROM session WHERE sesid = ?" (Only sesid) :: IO [Only Data.Text.Text]
    if isEmpty sessions then return True else return False where
        isEmpty :: [Only Data.Text.Text] -> Bool
        isEmpty [] = True
        isEmpty _ = False

getFreeId :: Connection -> IO Int
getFreeId conn = do
    games <- query_ conn "SELECT gameid FROM game ORDER BY gameid DESC LIMIT 1" :: IO [GetGame]
    case games of
        [] -> return 1
        [GetGame _ id] -> return (id + 1)
        _ -> return 0
