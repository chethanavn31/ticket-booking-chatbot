//buttons - upto 3 btns allowed

module.exports.optionTemplate = function (text,img,subtitle,urlLink,intent) {

  if(intent == 'book') {
    return{
      "attachment":{
        "type":"template",
        "payload":{
          "template_type":"generic",
          "elements":[
             {
              "title":text,
              "image_url":img,
              "subtitle":subtitle,
              "default_action": {
                "type": "web_url",
                "url": img,
                "webview_height_ratio": "tall",
              },
              "buttons":[
                {
                  "type":"web_url",
                  "url": urlLink,
                  "title":"Select seat"
                }            
              ]      
            }
          ]
        }
      }
    }
  }

  else if(intent == 'view') {
    return{
      "attachment":{
        "type":"template",
        "payload":{
          "template_type":"generic",
          "elements":[
             {
              "title":text,
              "image_url":img,
              "default_action": {
                "type": "web_url",
                "url": urlLink,
                "webview_height_ratio": "tall",
              }    
            }
          ]
        }
      }
    }
  }
    




    // return{
    //   "attachment":{
    //     "type":"template",
    //     "payload":{
    //       "template_type":"generic",
    //       "elements":[
    //          {
    //           "title":"Welcome!",
    //           "image_url":"https://cdn.pixabay.com/photo/2015/04/23/22/00/tree-736885__340.jpg",
    //           "subtitle":"We have the right hat for everyone.",
    //           "default_action": {
    //             "type": "web_url",
    //             "url": "https://petersfancybrownhats.com/view?item=103",
    //             "webview_height_ratio": "tall",
    //           },
    //           "buttons":[
    //             {
    //               "type":"web_url",
    //               "url":"https://petersfancybrownhats.com",
    //               "title":"View Website"
    //             }            
    //           ]      
    //         }
    //       ]
    //     }
    //   }
    // }
}