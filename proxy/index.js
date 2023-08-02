const express = require("express");
const app = express();
const cors = require("cors")
const createProxyMiddleware = require("http-proxy-middleware")

app.use(cors())

app.get("/",createProxyMiddleware({target:'http://127.0.0.1:8000',changeOrigin:true}))

app.listen(3000,()=>{
    console.log("proxy started")
})
