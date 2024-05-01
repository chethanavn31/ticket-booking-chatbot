const dialogflow = require('dialogflow');
const nlpHandler = require('../NLP/processDialogflowResult');

const welcomeIntent = require('../Intents/welcomeIntent');
const searchIntent = require('../Intents/searchIntent');
const changeLocationIntent = require('../Intents/changeLocationIntent');
const bookTicketIntent = require('../Intents/bookTicketIntent');
const viewIntent = require('../Intents/ViewIntent');
const cancelIntent = require('../Intents/CancelIntent');
const defaultIntent = require('../Intents/defaultIntent');

const redisConstant = require('../config/constants');
const rKey = require('../Db/Redis/KeyGenerator');
const redisConnect = require('../Db/Redis/redisConnect');

//export GOOGLE_APPLICATION_CREDENTIALS="/home/mukund/Work/NodeJs/nodeProjects/DialogFlow/popularBot/DialogFlowCred/popularmaruti-ktxvxi-704259d3fa3a.json"
//set GOOGLE_APPLICATION_CREDENTIALS=D:\Nandu\Work\Gapblue\Chatbot\Project\Sample\genBot\credentials\gen-bot-xkiwjr-46db8745d211.json
//export GOOGLE_APPLICATION_CREDENTIALS="/home/mukund/Work/NodeJs/nodeProjects/DialogFlow/genBot/credentials/gen-bot-xkiwjr-46db8745d211.json"
//export GOOGLE_APPLICATION_CREDENTIALS="/home/ubuntu/Documents/NodeJS/Nandu/genBot/credentials/gen-bot-xkiwjr-46db8745d211.json"
//export GOOGLE_APPLICATION_CREDENTIALS="/home/ubuntu/Documents/NodeJS/Nandu/genBot/credentials/genbot-501ab96cffb9.json"
//export GOOGLE_APPLICATION_CREDENTIALS="/home/ubuntu/Documents/NodeJS/Nandu/genBot/credentials/gen-bot-xkiwjr-5e1c85d4d6aa.json"
// Instantiates a session client
const sessionClient = new dialogflow.SessionsClient();

async function detectIntent(
    projectId,
    sessionId,
    query,
    contexts,
    languageCode
) {
    // The path to identify the agent that owns the created intent.
    const sessionPath = sessionClient.sessionPath(projectId, sessionId);

    // The text query request.
    const request = {
        session: sessionPath,
        queryInput: {
            text: {
                text: query,
                languageCode: languageCode,
            },
        },
    };

    if (contexts && contexts.length > 0) {
        request.queryParams = {
            contexts: contexts,
        };
    }

    const responses = await sessionClient.detectIntent(request);
    return responses[0];
}


module.exports = {
    //var intentActions = require('doActionForIntentDF');
    executeQueries: async function (projectId, sessionId, queries, languageCode, senderPsid,isPostBack) 
    {
        let context;
        let intentResponse;
        if (isPostBack == true)
        {
            var query = queries.title;
        }
        else{
            var query = queries[0].text;
        }
        // var query = queries;

        try {
            console.log('SENDING QUERY: ================================== ' + query);
            intentResponse = await detectIntent(
                projectId,
                sessionId,
                query,
                context,
                languageCode
            );
            // console.log('Detected intent');
            
            // console.log(queries);
            console.log(intentResponse.queryResult.parameters);
            console.log('Fulfillment Text: ' + intentResponse.queryResult.fulfillmentText);
            var intentName = nlpHandler.getIntent(intentResponse);
            
            var entities = nlpHandler.getEntities(intentResponse);
            console.log('ENTITIESSSS ==  ', entities);

            var searchStatusKey = rKey.generateKey(redisConstant.SEARCH_STATUS, senderPsid);
            var searchDataKey = rKey.generateKey(redisConstant.SEARCH_DATA, senderPsid);
            var changeLocStatusKey = rKey.generateKey(redisConstant.CHANGE_LOCATION_STATUS, senderPsid);
            var bookStatusKey = rKey.generateKey(redisConstant.BOOK_STATUS,senderPsid);
            var bookDataKey = rKey.generateKey(redisConstant.BOOK_DATA,senderPsid);

            var searchStatus = await redisConnect.getData(searchStatusKey);
            var changeLocStatus = await redisConnect.getData(changeLocStatusKey);
            var bookStatus = await redisConnect.getData(bookStatusKey);

            console.log('SEARCHHH----',searchStatus);
            console.log('CHANGE ---- ', changeLocStatus);
            console.log('BOOK STATUS ----- ',bookStatus);

            if (searchStatus != null) {
                if ((intentName == 'none' || intentName == 'Default Fallback Intent') && searchStatus.searchStatusKey == 'y') {
                    intentName = 'Search';
                }
            }
            if (changeLocStatus != null) {
                if ((intentName == 'none' || intentName == 'Default Fallback Intent') && changeLocStatus.changeLocStatusKey == 'y') {
                    intentName = 'ChangeLocation';
                }
            }
            if (bookStatus != null) {
                if ((intentName == 'none' || intentName == 'Default Fallback Intent') && bookStatus.bookStatusKey == 'y') {
                    intentName = 'Book';
                }
            }
            if (isPostBack == true && bookStatus == null) {
                intentName = 'Book';
            }

            console.log(intentName);

            switch (intentName) {
                case 'Default Welcome Intent':
                    await redisConnect.deleteData(searchDataKey);
                    await redisConnect.deleteData(searchStatusKey);
                    await redisConnect.deleteData(changeLocStatusKey);
                    await redisConnect.deleteData(bookStatusKey);
                    await redisConnect.deleteData(bookDataKey);

                    welcomeIntent.welcomeMethod(senderPsid, intentResponse.queryResult.fulfillmentText);

                    break;

                case 'Search':
                    await redisConnect.setData(searchStatusKey, { searchStatusKey: 'y' });
                    searchIntent.searchMethod(senderPsid, intentResponse, queries[0]);

                    break;

                case 'ChangeLocation':
                    await redisConnect.setData(changeLocStatusKey, { changeLocStatusKey: 'y' });
                    changeLocationIntent.changeLocationMethod(senderPsid, intentResponse, queries[0]);

                    break;

                case 'Book':
                    await redisConnect.setData(bookStatusKey, {bookStatusKey : 'y'});
                    if (isPostBack == true) {var data = queries; }
                    else {   var data = queries[0];              }
                    bookTicketIntent.bookTicketMethod(senderPsid,intentResponse,data);
                    
                    break;

                case 'View':
                    viewIntent.viewMethod(senderPsid);
                    break;

                case 'Cancel':
                    cancelIntent.cancelMethod(senderPsid);
                    break;

                case 'Default Fallback Intent':
                    defaultIntent.defaultTextMethod(senderPsid,intentResponse.queryResult.fulfillmentText);
                    break;

                default:
                    //console.log("intentResponse = ",intentResponse);
                    // session.send(intentResponse.queryResult.fulfillmentText);
                    break;
            }
           

        } catch (error) {
            console.log(error);
            var text = textCard.textTemplate('DIALOGFLOW ..' + redisConstant.ERROR_MESSAGE_API);
            await sendAPI.callSendAPI(senderPsid, text);
        }

    }
}
