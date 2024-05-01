const sendAPI = require('../APIs/SendAPI');
const dbAPI = require('../APIs/DBMethods');

const textCard = require('../Cards/TextCard');

const DFResult = require('../NLP/processDialogflowResult');

const searchModel = require('../Models/searchModel');

const constants = require('../config/constants');
const rKey = require('../Db/Redis/KeyGenerator');
const redisConnect = require('../Db/Redis/redisConnect');


module.exports.searchMethod = async function (senderPsid, result, payloadData) {
    console.log('PAYLODDD=== ', payloadData);

    var location = DFResult.getLocationEntity(result);
    var genre = DFResult.getGenreEntity(result);
    var movie = DFResult.getMovieNameEntity(result);

    var obj = searchModel.searchClass(genre, movie);
    console.log('OBJJJJ ==== ', obj);

    var searchStatusKey = rKey.generateKey(constants.SEARCH_STATUS, senderPsid);
    var searchDataKey = rKey.generateKey(constants.SEARCH_DATA, senderPsid);
    var locationKey = rKey.generateKey(constants.LOCATION, senderPsid);

    var redisLoc = await redisConnect.getData(locationKey);
    var searchData = await redisConnect.getData(searchDataKey);

    if (searchData == null) {
        await redisConnect.setData(searchDataKey, obj);
        searchData = await redisConnect.getData(searchDataKey);
    }
    if (redisLoc == null) {
        await redisConnect.setData(locationKey, { 'ID': '', 'name': '' });
        redisLoc = await redisConnect.getData(locationKey);
    }
    // console.log('LOC ==', redisLoc);

    if (redisLoc.name == '' && location != '') {
        var obj =
        {
            "name": location,
            "type": 'location',
            "thId": ''
        }
        console.log(' == locpayload', obj);
        var locId = await dbAPI.getIDMethod(senderPsid, obj);
        console.log("LOCATION ID++++ ", locId);
        await redisConnect.setData(locationKey, { 'ID': locId, 'name': location });
        redisLoc = await redisConnect.getData(locationKey);

    }
    if (searchData.genre == '' && genre != '') {
        searchData.genre = genre;
    }
    if (searchData.movie == '' && movie != '') {
        searchData.movie = movie;
    }

    await redisConnect.setData(searchDataKey, searchData);
    searchData = await redisConnect.getData(searchDataKey);
    console.log('search LOCATIONNN  ==== ', redisLoc);

    if (redisLoc.ID == '') {
        dbAPI.getLocationMethod(senderPsid);
    }
    else {
        if (searchData.genre == '' && searchData.movie == '') {
            var text = textCard.textTemplate('Type in genre or movie name ');
            await sendAPI.callSendAPI(senderPsid, text);
        }
        else {
            var obj = {
                "userId": senderPsid,
                "location": redisLoc.name,
                "genre": searchData.genre,
                "movie": searchData.movie,
                "type": 'search'
            }
            dbAPI.searchMovieMethod(senderPsid, obj);

            await redisConnect.deleteData(searchDataKey);
            await redisConnect.deleteData(searchStatusKey);
        }
    }
}