{-# LANGUAGE OverloadedStrings #-}
import Web.Scotty
import Web.Scotty.Cookie
import Data.Aeson (FromJSON, ToJSON)
import GHC.Generics (Generic)
import Network.Wai.Middleware.Cors (simpleCors)
import Database.SQLite.Simple
import Database.SQLite.Simple.FromRow
import Text.StringRandom
import Data.Text (Text)
import Foreign.Marshal.Unsafe (unsafeLocalState)

data User = User { userName :: String, gameId :: Int } deriving (Show, Generic)
instance ToJSON User
instance FromJSON User

data Session = Session String String Int String deriving (Show)

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
    execute_ conn "CREATE TABLE session (id INTEGER PRIMARY KEY, sesid TEXT, name TEXT, gameid INT, numbers TEXT)"
    -- create game table (id :: INT, gameid :: INT, participants :: INT, ongoing :: BOOL, host :: TEXT)
    execute_ conn "CREATE TABLE game (id INTEGER PRIMARY KEY, gameid INT UNIQUE, participants INT, ongoing BOOL, host TEXT)"
    -- start web server
    scotty 3000 $ routes conn
    -- close the connection
    close conn

routes :: Connection -> ScottyM()
routes conn = do
    -- Need to enable CORS to allow the frontend to access the backend
    middleware simpleCors
    -- Lisening on:
    get "/" $ genHelloWord
    post "/join" $ enlistUser conn

genHelloWord :: ActionM()
genHelloWord = do
    text "Hello World!"

enlistUser :: Connection -> ActionM()
enlistUser conn = do
    user <- jsonData :: ActionM User
    setSimpleCookie "SESSIONID" $ unsafeLocalState $ initUser conn user
    json $ user

-- can't get this right
initUser :: Connection -> User -> IO Text
initUser conn (User name 0) = do
    sesid <- stringRandomIO "????????????????"
    gameid <- getFreeId conn
    execute conn "INSERT INTO session (sesid, name, gameid, numbers) VALUES (?, ?, ?, ?)" (sesid, name, gameid, "00000")
    execute conn "INSERT INTO game (gameid, participants, ongoing) VALUES (?, ?, ?)" (getFreeId conn, 1, False)
    return sesid
initUser conn (User name gameid) = do
    sesid <- stringRandomIO "????????????????"
    execute conn "INSERT INTO session (sesid, name, gameid, numbers) VALUES (?, ?, ?, ?)" (sesid, name, gameid, "00000")
    execute conn "UPDATE game SET participants = participants + 1 WHERE gameid = ?" (gameid)
    return sesid

getFreeId :: Connection -> Int
getFreeId conn = do
    [[nextid :: Int]] <- query_ conn "SELECT gameid FROM game ORDER BY gameid DESC LIMIT 1"
    return $ nextid + 1
    --return ((unsafeLocalState nextid) + 1)