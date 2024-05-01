
module.exports.getIntent = function (dfResult) {
    if (dfResult.queryResult.intentDetectionConfidence > 0.6)
    {
        var intentName = dfResult.queryResult.intent.displayName;
    }
    else
    {
        var intentName = 'none';
    }    
    return intentName;
}

module.exports.getEntities = function (dfResult) {
    // console.log('ENTITIES RESULT === ', dfResult.queryResult.parameters);
    try{
        if(dfResult.queryResult.parameters != undefined){
            return dfResult.queryResult.parameters;
        }
    }
    catch(e){
        console.log('ENTITIES ERROR ==== ', e);
        return null;
    }
}

module.exports.getLocationEntity = function (result)
{
    try{
        return result.queryResult.parameters.fields.location.stringValue;
    }
    catch (e){
        console.log('ERRoR == ',e);
        return '';
    }
}

module.exports.getGenreEntity = function (result)
{
    try {
        return result.queryResult.parameters.fields.genre.stringValue;
    }
    catch (e) {
        console.log('ERRoR == ',e);
        return '';
    }
}

module.exports.getMovieNameEntity = function (result)
{
    try {
        return result.queryResult.parameters.fields.movie.stringValue;
    }
    catch (e) {
        console.log('ERRoR == ',e);
        return '';
    }
}

module.exports.getDateEntity = function (result)
{
    try{
        return result.queryResult.parameters.fields.date.stringValue;
    }
    catch (e){
        console.log('ERRoR == ',e);
        return '';
    }
}

module.exports.getTheatreEntity = function (result)
{
    try{
        return result.queryResult.parameters.fields.theatre.stringValue;
    }
    catch (e){
        console.log('ERRoR == ',e);
        return '';
    }
}

module.exports.getShowTimeEntity = function (result)
{
    try{
        return result.queryResult.parameters.fields.showname.stringValue;
    }
    catch (e){
        console.log('ERRoR == ',e);
        return '';
    }
}