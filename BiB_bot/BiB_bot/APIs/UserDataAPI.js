const config = require("config");
const request = require('request');

module.exports.getUserDataAPI = function (sender_psid) {

    return new Promise(async function (resolve, reject) {
        console.log(config.get('facebook.page.access_token'));
        request({
            "url": "https://graph.facebook.com/v8.0/" + sender_psid + "?",
            "qs": {
                "fields": "name",
                "access_token": config.get('facebook.page.access_token')
            },
            "method": "GET"

        }, function (error, response, body) {
            if (error) {
                resolve(false);
                console.log("error getting username")
            } else {
                obj = JSON.parse(body);
                console.log(obj)
                // console.log('name +++ ' + obj.name);
                resolve(obj.name);
            }
        });
    });
}