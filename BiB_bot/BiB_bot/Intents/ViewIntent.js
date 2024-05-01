const sendAPI = require('../APIs/SendAPI');

const optionCard= require('../Cards/OptionsCard');
const mainMenu = require('../Models/mainMenuModel');

const constants = require('../config/constants');
const rKey = require('../Db/Redis/KeyGenerator');
const redisConnect = require('../Db/Redis/redisConnect');

module.exports.viewMethod = async function (senderPsid) {
    var locationKey = rKey.generateKey(constants.LOCATION, senderPsid);
    var redisLoc = await redisConnect.getData(locationKey);

    phpPostData = constants.URL_HEAD+'middleView.php?&userId='+senderPsid;

    var viewCard = optionCard.optionTemplate('VIEW BOOKINGS','https://thumbs.dreamstime.com/b/d-old-man-reading-book-render-50476568.jpg',
                                '',phpPostData,'view');
    // console.log(JSON.stringify(viewCard));
    await sendAPI.callSendAPI(senderPsid, viewCard);

    // phpPostData = constants.URL_HEAD+'viewBookings.php?&userId='+senderPsid;

    // var text = textCard.textTemplate('No bookings available');
    // await sendAPI.callSendAPI(senderPsid, text);

    // if (redisLoc.name != '') {
    //     mainMenu.mainMenuModelWithLocation(senderPsid);
    // }
    // else {
    //     mainMenu.mainMenuModelWithLocation(senderPsid);
    // }

}