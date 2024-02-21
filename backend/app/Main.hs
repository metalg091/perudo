{-# LANGUAGE OverloadedStrings #-}
import Web.Scotty
import Data.Aeson (FromJSON, ToJSON)
import GHC.Generics (Generic)
import Network.Wai.Middleware.Cors (simpleCors)

newtype User = User { userName :: String } deriving (Show, Generic)
instance ToJSON User
instance FromJSON User

main :: IO()
main = do
    scotty 3000 routes


routes :: ScottyM()
routes = do
    middleware simpleCors
    get "/" $ genHelloWord
    post "/join" $ enlistUser

genHelloWord :: ActionM()
genHelloWord = do
    text ""

enlistUser :: ActionM()
enlistUser = do
    user <- jsonData :: ActionM User
    json user
