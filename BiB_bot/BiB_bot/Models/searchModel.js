module.exports.searchClass = function (genre,movieName)
{
    return {
        genre : genre,
        movie : movieName
    }
}

module.exports.bookClass = function (movie,date,theatre,showname)
{
    return {
        mID : '',
        movie : movie,
        poster : '',
        date : date,
        thID : '',
        theatre : theatre,
        layout : '',
        shID : '',
        showname : showname,
        time : '',
        day : ''
    }
}
