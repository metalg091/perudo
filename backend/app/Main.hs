{-# LANGUAGE OverloadedStrings #-}
import Web.Scotty

main :: IO()
main = do
    scotty 3000 routes

routes :: ScottyM()
routes = do
    get "/" $ genHelloWord

genHelloWord :: ActionM()
genHelloWord = do
     text "Hello, World!"
