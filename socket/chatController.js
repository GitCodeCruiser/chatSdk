const Axios = require("axios");
const { response } = require("express");
const envirmentVirable = require("dotenv").config();
const baseUrl = process.env.BASE_URL;
module.exports.sendMessage = async (req, token) => {
    let response = await createRoom(req, token);
    if (response.status === true) {
        let data = {
            room_id: response.data.id,
            message: req?.message,
            message_type: req?.message_type,
            file: req?.file,
        };

        return await sendMessage(data, token);
    }
    return response;
    // return res.status(response.status).send(response);
};

const createRoom = async (req, token) => {
    try {
        const responseFromService = await Axios.post(
            `${baseUrl}/chat/create-room`,
            req,
            {
                headers: {
                    Authorization: `Bearer ${token}`,
                },
            }
        );
        return responseFromService.data;
    } catch (error) {
        return error.data;
    }
};

const sendMessage = async (req, token) => {
    try {
        const responseFromService = await Axios.post(
            `${baseUrl}/chat/send-message`,
            req,
            {
                headers: {
                    Authorization: `Bearer ${token}`,
                },
            }
        );
        return responseFromService.data;
    } catch (error) {
        return error.data;
    }
};
