// 10 generic elemnts and 3 btns per element
module.exports.scrollListTemplate = function (titleList, subtitleList , imageList, btnArr) {
  var dataArr = [];
  for (var i = 0; i < titleList.length; i++) {
      if (imageList != null) {
          var obj = {
               "title" : titleList[i],
               "image_url" : imageList[i],
               "subtitle" : subtitleList[i],
               "buttons": btnArr[i]
             }
      }
      else {
        var obj = {
          "title" : titleList[i],
          "subtitle" : subtitleList[i],
           "buttons": btnArr[i]
        }
      }

      dataArr.push(obj);
    }

    return {
        "attachment":{
            "type":"template",
            "payload":{
              "template_type":"generic",
              "elements": dataArr             
              
            }
         }
    }
}

module.exports.scrollListTemplate_1 = function (titleList, subtitleList , imageList, btnArr) {
  var dataArr = [];
  for (var i = 0; i < titleList.length; i++) {
      if (imageList != null) {
          var obj = {
               "title" : titleList[i],
               "image_url" : imageList[i],
               "subtitle" : subtitleList[i],
               "buttons": btnArr[i]
             }
      }
      else {
        var obj = {
          "title" : titleList[i],
          "subtitle" : subtitleList[i],
           "buttons": btnArr[i]
        }
      }

      dataArr.push(obj);
    }

    return {
        "attachment":{
            "type":"template",
            "payload":{
              "template_type":"generic",
              "elements": dataArr             
              
            }
         }
    }
}



// [
//   {
//    "title":"movie 1!",
//    "image_url":"https://icons.iconarchive.com/icons/sirubico/movie-genre/128/Action-3-icon.png",
//    "subtitle":"genre 1",
//    "buttons":[
//     {
//        "type":"postback",
//        "title":"BOOK",
//        "payload":"book"
//      }              
//    ]      
//  }
// ]