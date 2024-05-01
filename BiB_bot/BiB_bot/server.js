'use strict';
const express = require('express');
const request = require('request');
const config = require('config');
const constants = require('./config/constants');
const DFobj = require('./APIs/DialogFlowConnect');
const routes = require('./routes');

const sendAPI = require('./APIs/SendAPI');

const textCard = require('./Cards/TextCard');

const rKey = require('./Db/Redis/KeyGenerator');
const redisConnect = require('./Db/Redis/redisConnect');

const app = express();
const bodyParser = require('body-parser');
app.use(bodyParser.json());
app.use(bodyParser.urlencoded({ extended: true }));

require('dotenv').config()

app.use(express.static(process.env.staticDir));


var myLogger = function (req, res, next) {
  console.log('LOGGED');
  next();
};

app.post('/fb/messages', (req, res) => {
  let body = req.body;

  // Checks this is an event from a page subscription
  if (body.object === 'page') {
    // Iterates over each entry - there may be multiple if batched
    body.entry.forEach(function (entry) {

      // Gets the message. entry.messaging is an array, but
      // will only ever contain one message, so we get index 0
      let webhook_event = entry.messaging[0];
      console.log(webhook_event);

      // Get the sender PSID
      let sender_psid = webhook_event.sender.id;
      console.log('Sender PSID: ' + sender_psid);

      // Check if the event is a message or postback and
      // pass the event to the appropriate handler function
      if (webhook_event.message) {
        const queries = [];

        // queries[0] = webhook_event.message.text;
        queries[0] = webhook_event.message;
         var isPostBack = false;
        //res.send('mukund_gokulan deleted!!! \n');
        DFobj.executeQueries(constants.PROJECT_ID, constants.SESSION_ID, queries, constants.LANGUAGE_CODE, sender_psid,isPostBack);
        //handleMessage(sender_psid, webhook_event.message);
      } else if (webhook_event.postback) {
        handlePostback(sender_psid, webhook_event.postback);
      }
    });

    // Returns a '200 OK' response to all requests
    res.status(200).send('EVENT_RECEIVED');
    //sendMessage(webhook_event.sender.id,webhook_event.message.text);

  } else {
    // Returns a '404 Not Found' if event is not from a page subscription
    res.sendStatus(404);
  }
});

app.get('/poster/:fileName', function (req, res) {
  console.log(req.params.fileName);
  res.sendFile(process.env.staticDir+req.params.fileName);
})

// Adds support for GET requests to our webhook
app.get('/fb/messages', function (req, res) {
  let body = req.body;
  //console.log(body);
  // Your verify token. Should be a random string.
  let VERIFY_TOKEN = config.get('facebook.page.access_token');
  // let VERIFY_TOKEN = "EAAN2LFmNTgUBAN0VpYYEiToS3bRj1MKBSOI9QObAZBYlUYQna7b6S5DZCiBgbcj4eRzZCCBnUnQZBXRJxpN0kZAjJZAEBmGnXWJ3UNIIuHKZCl5D8uqQP9a59gmac0GdaOjspsS9YQS7Cj0E6BL826rhbkIXodXPs4hLZBQQWENtls82wxwS7ZCWapMC8wkPQm4cQi4bFpdl6BgZDZD";
 // console.log(req);

  // Parse the query params
  let mode = req.query['hub.mode'];
  let token = req.query['hub.verify_token'];
  let challenge = req.query['hub.challenge'];
    // console.log(challenge);
  // Checks if a token and mode is in the query string of the request
  if (mode && token) {
    // Checks the mode and token sent is correct
    if (mode === 'subscribe' && token === VERIFY_TOKEN) {

      // Responds with the challenge token from the request
      console.log('WEBHOOK_VERIFIED');
      // console.log(challenge);
      res.status(200).send(challenge);

    } else {
      // Responds with '403 Forbidden' if verify tokens do not match
      res.sendStatus(403);
    }
  }
});

app.use(myLogger, routes);

app.listen(constants.PORT, function () {
  console.log(`Listen to http://localhost:${constants.PORT}`);

  console.log(process.env.GOOGLE_APPLICATION_CREDENTIALS);
});

module.exports = app;



// Handles messages events
function handleMessage(sender_psid, received_message) {
  let response;

  // Check if the message contains text
  if (received_message.text) {
    console.log('received_message.text' + received_message.text);
    // Create the payload for a basic text message
    response = textCard.textTemplate(received_message.text);
  }
  console.log('calling API');
  // Sends the response message
  sendAPI.callSendAPI(sender_psid, response);
}

async function handlePostback(sender_psid, received_postback) {
  let response;
   console.log("-----------",received_postback);
  // Get the payload for the postback
  let payload = received_postback.payload;

  // Set the response based on the postback payload
  if (payload === 'FACEBOOK_WELCOME') {
    var locationKey = rKey.generateKey(constants.LOCATION, sender_psid);
    await redisConnect.deleteData(locationKey);

    response = textCard.textTemplate('Welcome ☺ Type Hi to continue.');
    sendAPI.callSendAPI(sender_psid, response);
  }
  else {
    var isPostBack = true;
    DFobj.executeQueries(constants.PROJECT_ID, constants.SESSION_ID, received_postback, constants.LANGUAGE_CODE, sender_psid,isPostBack);

    console.log('posted!!!!');
  }
  // Send the message to acknowledge the postback
}


