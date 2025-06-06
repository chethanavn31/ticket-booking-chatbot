const sendAPI = require('../APIs/SendAPI');

const textCard = require('../Cards/TextCard');
const mainMenu = require('../Models/mainMenuModel');

const constants = require('../config/constants');
const rKey = require('../Db/Redis/KeyGenerator');
const redisConnect = require('../Db/Redis/redisConnect');

module.exports.defaultTextMethod = async function (senderPsid,text) {
    var locationKey = rKey.generateKey(constants.LOCATION, senderPsid);
    var redisLoc = await redisConnect.getData(locationKey);

    var text = textCard.textTemplate(text);
    await sendAPI.callSendAPI(senderPsid, text);

    if (redisLoc.name != '') {
        mainMenu.mainMenuModelWithLocation(senderPsid);
    }
    else {
        mainMenu.mainMenuModelWithLocation(senderPsid);
    }

}