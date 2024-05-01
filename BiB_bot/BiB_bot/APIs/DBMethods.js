const sendAPI = require('./SendAPI');;
const dbCall = require('../Db/ServicesDB');

const listCard = require('../Cards/ListCard');
const textCard = require('../Cards/TextCard');
const scrollListCard = require('../Cards/ScrollListCard');

const constants = require('../config/constants');
const dateFormat = require('../APIs/DateFormat');
var path = require('path');
require('dotenv').config()

module.exports.addUserMethod = async function (senderPsid, name) {
    var obj =
    {
        "id": senderPsid,
        "name": name
    }
    // console.log(' == USERpayload', obj);
    var userData = await dbCall.pythonServicesFunctionCall(constants.ADD_USER, obj);
    console.log("userr DATAA++++ ", userData);
    if (userData.status == 'S') {
        console.log("SUCCESS");
    }
    else {
        console.log("EEEEEEEEEEEEE");
    }
}

module.exports.getLocationMethod = async function (senderPsid) {
    var titleList = [];
    var payloadList = [];
    var locData = await dbCall.pythonServicesFunctionCall(constants.GET_LOCATION, "");
    console.log("LOCATION DATAA++++ ", locData);
    if (locData.status == 'S') {
        var obj = locData.locDetails;
        if (obj.length > 0) {
            for (var i = 0; i < obj.length; i++) {
                payloadList.push(obj[i].locId);
                titleList.push(obj[i].locName);
            }
            var lst = listCard.listTemplate('Select Location : ', titleList, payloadList, null);
            await sendAPI.callSendAPI(senderPsid, lst);
        }
        else {
            var text = textCard.textTemplate(constants.ERROR_MESSAGE_NO_DATA+' for location');
            await sendAPI.callSendAPI(senderPsid, text);
        }
    }
    else {
        var text = textCard.textTemplate('LOCATION ..' + constants.ERROR_MESSAGE_DB);
        await sendAPI.callSendAPI(senderPsid, text);
    }
}

module.exports.getIDMethod = async function (senderPsid, obj) {
    // console.log(' == locpayload', obj);
    var idData = await dbCall.pythonServicesFunctionCall(constants.GET_ID, obj);
    console.log("ID  DATAA --------- ", idData);
    if (idData.status == 'S') {
        var id = idData.details;
        console.log('=========ID=====', id);
        return id;
    }
    else {
        var text = textCard.textTemplate('LOCATION ID ..' + constants.ERROR_MESSAGE_DB);
        await sendAPI.callSendAPI(senderPsid, text);
    }
}

module.exports.searchMovieMethod = async function (senderPsid, obj) {
    console.log(' == payload',obj);
    // if (type == 'search') {
    //     var movieData = await dbCall.pythonServicesFunctionCall(constants.SEARCH_MOVIE, obj);
    // }
    // else {
    var movieData = await dbCall.pythonServicesFunctionCall(constants.GET_MOVIE, obj);
    // }
    console.log("MOVIE DATAA++++ ", movieData);
    if (movieData.status == 'S') {
        var titleList = [];
        var genreList = [];
        var imageList = [];
        var bookObj = [];
        var obj = movieData.movieDetails;
        if (obj.length > 0) {
            for (var i = 0; i < obj.length; i++) {
                titleList.push("Director : " + obj[i].director);
                genreList.push(obj[i].genre);
                imageList.push(obj[i].poster);

                var bObj = [{
                    "type": "postback",
                    "title": obj[i].movie,
                    "payload": obj[i].id
                }];
                bookObj.push(bObj);
            }
             console.log('----------', imageList);
            var newImageList = []
            for(var i=0; i<imageList.length;i++){
                var scriptName = path.basename(imageList[i]);
                console.log(scriptName);
                newImageList.push(process.env.domainpath+"/poster/"+scriptName);
            }


            var movieList = scrollListCard.scrollListTemplate(titleList, genreList, newImageList, bookObj);
            await sendAPI.callSendAPI(senderPsid, movieList);
            // console.log('----');
        }
        else {
            var text = textCard.textTemplate(constants.ERROR_MESSAGE_NO_DATA +' for this movie. Try changing the location');
            await sendAPI.callSendAPI(senderPsid, text);
        }
    }
    else {
        var text = textCard.textTemplate('MOVIEEEE ..' + constants.ERROR_MESSAGE_DB);
        await sendAPI.callSendAPI(senderPsid, text);
    }
}

module.exports.getTheatreMethod = async function (senderPsid, obj) {
    var titleList = [];
    var payloadList = [];
    console.log(' == theatrepayload', obj);
    var theatreData = await dbCall.pythonServicesFunctionCall(constants.GET_THEATRE, obj);
    console.log("THEATRE DATAA++++ ", theatreData);
    if (theatreData.status == 'S') {
        var obj = theatreData.theatreDetails;
        if (obj.length > 0) {
            for (var i = 0; i < obj.length; i++) {
                payloadList.push(obj[i].theatre_id);            
                titleList.push(obj[i].name);
            }
            var lst = listCard.listTemplate('Theatres : ', titleList, payloadList, null);
            await sendAPI.callSendAPI(senderPsid, lst);
        }
        else {
            var text = textCard.textTemplate(constants.ERROR_MESSAGE_NO_DATA+' for theatre');
            await sendAPI.callSendAPI(senderPsid, text);
        }
    }
    else {
        var text = textCard.textTemplate('THEATRE ..' + constants.ERROR_MESSAGE_DB);
        await sendAPI.callSendAPI(senderPsid, text);
    }
}

module.exports.getMovieDateMethod = async function (senderPsid, obj) {
    var payloadList = [];
    console.log(' == datepayload', obj);
    var dateData = await dbCall.pythonServicesFunctionCall(constants.GET_MOVIE_DATE, obj);
    console.log("DATE DATAA++++ ", dateData);
    if (dateData.status == 'S') {
        var obj = dateData.dateDetails;

        if (obj.length > 0) {
            var dateArray = dateFormat.getDates(obj[0], obj[1]);
            console.log('-----------', dateArray);
            for (var i = 0; i < dateArray.length; i++) {
                // console.log('---------iiii', i);
                payloadList.push(dateArray[i]);
            }
            var lst = listCard.listTemplate('Available Dates : ', payloadList, payloadList, null);
            await sendAPI.callSendAPI(senderPsid, lst);
        }
        else {
            var text = textCard.textTemplate(constants.ERROR_MESSAGE_NO_DATA+' for available dates');
            await sendAPI.callSendAPI(senderPsid, text);
        }
    }
    else {
        var text = textCard.textTemplate('DATE ..' + constants.ERROR_MESSAGE_DB);
        await sendAPI.callSendAPI(senderPsid, text);
    }
}

module.exports.getShowTimeMethod = async function (senderPsid, obj) {
    var titleList = [];
    var payloadList = [];
    console.log(' == showpayload', obj);
    var showData = await dbCall.pythonServicesFunctionCall(constants.GET_SHOW, obj);
    console.log("SHOW DATAA++++ ", showData);
    if (showData.status == 'S') {
        var obj = showData.showDetails;
        if (obj.length > 0) {
            for (var i = 0; i < obj.length; i++) {
                payloadList.push(obj[i].type);
                titleList.push(obj[i].type);
            }
            var lst = listCard.listTemplate('Available shows : ', titleList, payloadList, null);
            await sendAPI.callSendAPI(senderPsid, lst);
        }
        else {
            var text = textCard.textTemplate(constants.ERROR_MESSAGE_NO_DATA+' for shows');
            await sendAPI.callSendAPI(senderPsid, text);
        }
    }
    else {
        var text = textCard.textTemplate('SHOWSS ..' + constants.ERROR_MESSAGE_DB);
        await sendAPI.callSendAPI(senderPsid, text);
    }
}