const textCard = require('../Cards/TextCard');

const sendAPI = require('../APIs/SendAPI');
const userAPI = require('../APIs/UserDataAPI');
const dbAPI = require('../APIs/DBMethods');

const mainMenu = require('../Models/mainMenuModel');

const constants = require('../config/constants');
const rKey = require('../Db/Redis/KeyGenerator');
const redisConnect = require('../Db/Redis/redisConnect');

module.exports.welcomeMethod = async function (senderPsid, text) {
    var name = await userAPI.getUserDataAPI(senderPsid);
    console.log('Nmae >>');
    console.log(name);
    if (name == false)
    {
        var text = textCard.textTemplate('FB USERNAME ..' + constants.ERROR_MESSAGE_API);
        await sendAPI.callSendAPI(senderPsid, text);
    }
    else
    {
        console.log('NAME.... ', name);
        await dbAPI.addUserMethod(senderPsid,name);

        var text = textCard.textTemplate(text + " " + name + " I'm BiB 🤖.");
        await sendAPI.callSendAPI(senderPsid, text);
    
        var locationKey = rKey.generateKey(constants.LOCATION, senderPsid);
        var redisLoc = await redisConnect.getData(locationKey);
        // console.log('LOC ==', redisLoc);
    
        if (redisLoc == null) {
            mainMenu.mainMenuModelWithoutLocation(senderPsid);
        }
        else {
            mainMenu.mainMenuModelWithLocation(senderPsid);
        }
    }   

}

