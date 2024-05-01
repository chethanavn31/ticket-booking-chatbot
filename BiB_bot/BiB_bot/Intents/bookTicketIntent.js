const dbAPI = require('../APIs/DBMethods');
const sendAPI = require('../APIs/SendAPI');
const moment = require('moment');

const DFResult = require('../NLP/processDialogflowResult');
// const textCard = require('../Cards/TextCard');
const optionCard= require('../Cards/OptionsCard');

const searchModel = require('../Models/searchModel');

const constants = require('../config/constants');
const rKey = require('../Db/Redis/KeyGenerator');
const redisConnect = require('../Db/Redis/redisConnect');


module.exports.bookTicketMethod = async function (senderPsid, result, payloadData) {
    // console.log("-------------", result.queryResult.parameters.fields);

    var location = DFResult.getLocationEntity(result);
    var movie = DFResult.getMovieNameEntity(result);
    var date = DFResult.getDateEntity(result);
    var theatre = DFResult.getTheatreEntity(result);
    var showname = DFResult.getShowTimeEntity(result);

    var obj = searchModel.bookClass(movie, date, theatre, showname);

    var locationKey = rKey.generateKey(constants.LOCATION, senderPsid);
    var bookStatusKey = rKey.generateKey(constants.BOOK_STATUS, senderPsid);
    var bookDataKey = rKey.generateKey(constants.BOOK_DATA, senderPsid);

    var redisLoc = await redisConnect.getData(locationKey);
    var redisBookData = await redisConnect.getData(bookDataKey);
    // console.log('REDIS LOC ==== ', redisLoc);

    if (redisBookData == null) {
        console.log('OBJJJJ ==== ', obj);
        await redisConnect.setData(bookDataKey, obj);
        redisBookData = await redisConnect.getData(bookDataKey);
    }
    else {
        if (redisBookData.movie == '' && movie != '') {
            redisBookData.movie = movie;
        }
        if (redisBookData.date == '' && date != '') {
            var d1 = moment(date).format('YYYY-MM-DD');
            redisBookData.date = d1;
        }
        if (redisBookData.theatre == '' && theatre != '') {
            redisBookData.theatre = theatre;
        }
        if (redisBookData.showname == '' && showname != '') {
            redisBookData.showname = showname;
        }
    }
    // console.log('REDIS DATA ==== ', redisBookData);

    if (redisLoc == null) {
        console.log('LOC ENTITY == ', location);
        if (location == '') {
            await dbAPI.getLocationMethod(senderPsid);
        }
        else {
            console.log('PAYLODDD=== ', payloadData);
            if (payloadData.quick_reply == null) {
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
            else {
                if (location != '' && location != null) {
                    await redisConnect.setData(locationKey, { 'ID': payloadData.quick_reply.payload, 'name': location });
                    redisLoc = await redisConnect.getData(locationKey);
                }
                else {
                    await redisConnect.setData(locationKey, { 'ID': payloadData.quick_reply.payload, 'name': payloadData.text });
                    redisLoc = await redisConnect.getData(locationKey);
                }
            }
        }
        console.log('======== REDIS LOCATION ==== ', redisLoc);
    }

    if (redisBookData.movie != '' && redisLoc != null) {
        var obj =
        {
            "name": redisBookData.movie,
            "type": 'movie',
            "thId": ''
        }
        console.log(' =====M=====', obj);
        // var movieId = await dbAPI.getIDMethod(senderPsid, obj);
        // console.log("MOVIE ID++++ ", movieId);
        // redisBookData.mID = movieId;
        // console.log(' =====M=====', redisBookData);
        var movieData = await dbAPI.getIDMethod(senderPsid, obj);
        console.log("MOVIE ID++++ ", movieData[0],movieData[1]);
        redisBookData.mID = movieData[0];
        redisBookData.poster = movieData[1];
        await redisConnect.setData(bookDataKey, redisBookData);
        redisBookData = await redisConnect.getData(bookDataKey);

        if (redisBookData.date != '') {
            if (redisBookData.day == '') {
                redisBookData.day = moment(redisBookData.date).format('dddd');
            }
            if (redisBookData.theatre != '') {
                var obj =
                {
                    "name": redisBookData.theatre,
                    "type": 'theatre',
                    "thId": ''
                }
                console.log(' == ========', obj);
                // var theatreId = await dbAPI.getIDMethod(senderPsid, obj);
                // console.log("THEATRE ID++++ ", theatreId);
                // redisBookData.thID = theatreId;
                var theatreData = await dbAPI.getIDMethod(senderPsid, obj);
                console.log("THEATRE ID++++ ", theatreData[0],theatreData[1]);
                redisBookData.thID = theatreData[0];
                redisBookData.layout = theatreData[1];
                await redisConnect.setData(bookDataKey, redisBookData);
                // redisBookData = await redisConnect.getData(bookDataKey);
                // await redisConnect.setData(bookDataKey, redisBookData);
                redisBookData = await redisConnect.getData(bookDataKey);
                if (redisBookData.showname != '') {
                    var obj =
                    {
                        "name": redisBookData.showname,
                        "type": 'show',
                        "thId": redisBookData.thID
                    }
                    console.log(' == ========', obj);
                    var showData = await dbAPI.getIDMethod(senderPsid, obj);
                    console.log("SHOW ID++++ ", showData[0],showData[1]);
                    redisBookData.shID = showData[0];
                    redisBookData.time = showData[1];
                    await redisConnect.setData(bookDataKey, redisBookData);
                    redisBookData = await redisConnect.getData(bookDataKey);

                    // var text = textCard.textTemplate('=====MOVIE TICKET===== \n'+redisBookData.theatre + ', ' + redisLoc.name +
                    //                                  '\n-------------------------------------------- \n '
                    //                                  + redisBookData.movie.toUpperCase()
                    //                                   + '\n\n' + redisBookData.showname +'    |   ' + redisBookData.time + '\n' 
                    //                                   + moment(redisBookData.date).format('ll') + '         |   ' + redisBookData.day);
                    // await sendAPI.callSendAPI(senderPsid, text);

                    console.log("====================SEATS==================");
                    phpPostData = constants.URL_HEAD+'middlePhp.php?mid='+redisBookData.mID+'&thid='+redisBookData.thID+
                                            '&locid='+redisLoc.ID+'&shid='+redisBookData.shID+'&layout='+redisBookData.layout+'&date='+redisBookData.date
                                            +'&userId='+senderPsid;
                    
                    var seatCard = optionCard.optionTemplate(redisBookData.movie+' at '+ redisBookData.theatre+', '+redisLoc.name,
                                        redisBookData.poster,redisBookData.showname+' on '+redisBookData.date,phpPostData,'book');
                    console.log(JSON.stringify(seatCard));
                    await sendAPI.callSendAPI(senderPsid, seatCard);

                    await redisConnect.deleteData(bookStatusKey);
                    await redisConnect.deleteData(bookDataKey);

                }
                else {
                    console.log('=s');
                    var obj = {
                        "theatreId": redisBookData.thID
                    }
                    await dbAPI.getShowTimeMethod(senderPsid, obj);
                }
            }
            else {
                console.log('=t');
                var obj = {
                    "moviename": redisBookData.movie,
                    "locationId": redisLoc.ID
                }
                await dbAPI.getTheatreMethod(senderPsid, obj);
            }
        }
        else {
            console.log('=d');
            var obj = {
                "movieId": redisBookData.mID
            }
            await dbAPI.getMovieDateMethod(senderPsid, obj);
        }
    }
    else {
        console.log('=m');
        var obj = {
            "location": redisLoc.name,
            "type":''
        }
        await dbAPI.searchMovieMethod(senderPsid, obj);
    }
    console.log('======== REDIS LOCATION ==== ', redisLoc);
    console.log('REDIS BOOK DATA ==== ', redisBookData);
}