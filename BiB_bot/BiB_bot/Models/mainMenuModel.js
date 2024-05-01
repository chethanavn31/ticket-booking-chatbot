const sendAPI = require('../APIs/SendAPI');

const listCard = require('../Cards/ListCard');

module.exports.mainMenuModelWithLocation = async function (senderPsid) {

    var titleList = ['Search for movie', 'Book ticket', 'View bookings', 'Change location']; //'Cancel bookings', 
    var payloadList = ['search', 'book', 'view', 'change']; //'cancel',
    var imageList = ['https://icons.iconarchive.com/icons/ampeross/qetto-2/48/search-icon.png',
        'https://icons.iconarchive.com/icons/sonya/swarm/48/Ticket-icon.png',
        'https://icons.iconarchive.com/icons/oxygen-icons.org/oxygen/48/Actions-view-list-details-icon.png',
        'https://icons.iconarchive.com/icons/paomedia/small-n-flat/48/building-icon.png'];
        //,  'https://icons.iconarchive.com/icons/icons8/ios7/48/Cinema-Delete-Ticket-icon.png'

    var lst = listCard.listTemplate('What can I do for you?', titleList, payloadList, imageList);
    await sendAPI.callSendAPI(senderPsid, lst);

}

module.exports.mainMenuModelWithoutLocation = async function (senderPsid) {
    var titleList = ['Search for movie', 'Book ticket', 'View bookings'];  //'Cancel bookings', 
    var payloadList = ['search', 'book', 'view'];  //'cancel', 
    var imageList = ['https://icons.iconarchive.com/icons/ampeross/qetto-2/48/search-icon.png',
        'https://icons.iconarchive.com/icons/sonya/swarm/48/Ticket-icon.png',
        'https://icons.iconarchive.com/icons/oxygen-icons.org/oxygen/48/Actions-view-list-details-icon.png'];

        //'https://icons.iconarchive.com/icons/icons8/ios7/48/Cinema-Delete-Ticket-icon.png',

    var lst = listCard.listTemplate('What can I do for you?', titleList, payloadList, imageList);
    await sendAPI.callSendAPI(senderPsid, lst);
}



