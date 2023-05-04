var app = require('express')();
var axios = require("axios");
const envirmentVirable = require("dotenv").config();
var http = require("http").Server(app);
const port = process.env.PORT || 3000;
const baseUrl = process.env.BASE_URL;
const chatApis = require("./chatController");
var io = require("socket.io")(http, {
    cors: {
        origin: "*",
        methods: ["GET", "POST"],
    },
    allowEIO3: true,
});

app.get("/", function (req, res) {
    res.send("Socket Working Fine Chat SDK.");
});

io.on("connection", function (socket) {
    socket.emit("on_connect", {
        return_data: "connected",
    });

    socket.on("create_conversation", async (data) => {
        const token = socket.handshake.headers.token;
        const res = await chatApis.sendMessage(data, token);
        socket.emit("get_conversation", {
            data: res,
        });
    });
});

http.listen(port, function () {
    console.log(`Socket Working Fine for Chat SDK listing on port ${port}.`);
});
