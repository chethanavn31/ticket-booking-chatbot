var express = require('express');
var router = express.Router();
const bodyParser = require('body-parser');
router.use(bodyParser.json());
router.use(bodyParser.urlencoded({extended: true}));

const sendAPI = require('../BiB_bot/APIs/SendAPI');
const textCard = require('../BiB_bot/Cards/TextCard');
const mainMenu = require('../BiB_bot/Models/mainMenuModel');


router.get('/',function(req,res)
{
    res.send('HOME GET');
});

router.post('/select',function(req,res)
{
    res.send('HOME POST');
});

router.post('/ack',function(req,res)
{
    res.send('SUCCESS');
    var response = textCard.textTemplate('The ticket has been successfully booked.. ');
    sendAPI.callSendAPI(req.body.senderID, response);

    mainMenu.mainMenuModelWithLocation(senderPsid);

});

module.exports = router;