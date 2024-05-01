const request = require('request');
const config = require('config');

module.exports.callSendAPI = async function(sender_psid, response, cb = null) {
        // Construct the message body'
        // console.log('RESPONSE' + response);
        // console.log("+++++++++++++");
        let request_body = {
            "recipient": {
                "id": sender_psid
            },
            "message": response
        };
        return new Promise(async function (resolve, reject) {
        // Send the HTTP request to the Messenger Platform
        request({
            "uri": "https://graph.facebook.com/v8.0/me/messages",
            "qs": { "access_token": config.get('facebook.page.access_token') },
            "method": "POST",
            "json": request_body
        }, (err, res, body) => {
            if (!err) {               
                // console.log(body);
                resolve(true);
                if(cb){
                    cb();
                }
            } else {
                console.log(err);
                resolve(false);
            }
        });
    });
    
}