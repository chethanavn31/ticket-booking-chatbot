const sendAPI = require('../APIs/SendAPI');
const dbAPI = require('../APIs/DBMethods');

const textCard = require('../Cards/TextCard');

const DFResult = require('../NLP/processDialogflowResult');

const mainMenu = require('../Models/mainMenuModel');

const constants = require('../config/constants');
const rKey = require('../Db/Redis/KeyGenerator');
const redisConnect = require('../Db/Redis/redisConnect');

module.exports.changeLocationMethod = async function (senderPsid, result, payloadData) {
    var location = DFResult.getLocationEntity(result);
    var changeLocStatusKey = rKey.generateKey(constants.CHANGE_LOCATION_STATUS, senderPsid);
    var locationKey = rKey.generateKey(constants.LOCATION, senderPsid);
    var redisLoc = await redisConnect.getData(locationKey);

    console.log('LOCATION == ', location);
    if (location == '') {
       dbAPI.getLocationMethod(senderPsid);
        // await redisConnect.deleteData(changeLocStatusKey);
    }
    else {
        console.log('PAYLODDD=== ', payloadData.quick_reply);
        if (payloadData.quick_reply == undefined || payloadData.quick_reply == null) {
            var obj =
            {
                "name": location,
                "type":'location',
                "thId":''
            }
            var locId = await dbAPI.getIDMethod(senderPsid,obj);
            await redisConnect.setData(locationKey, { 'ID': locId, 'name': location });
            redisLoc = await redisConnect.getData(locationKey);           
        }
        else {
            if (location != '') {
                await redisConnect.setData(locationKey, { 'ID': payloadData.quick_reply.payload, 'name': location });
                redisLoc = await redisConnect.getData(locationKey);
            }
        }

        var text = textCard.textTemplate("Location changed to " + redisLoc.name);
        await sendAPI.callSendAPI(senderPsid, text);

        mainMenu.mainMenuModelWithLocation(senderPsid);

        await redisConnect.deleteData(changeLocStatusKey);
    }
}