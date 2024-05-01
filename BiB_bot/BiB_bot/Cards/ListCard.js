// Quick replies upto 13 allowed

module.exports.listTemplate = function (text, title, payload, imageLst) {
    var optArr = [];
    for (var i = 0; i < title.length; i++) {
        if (imageLst != null) {
            var obj = {
                "content_type": "text",
                "title": title[i],
                "payload": payload[i],
                "image_url": imageLst[i]
            }
        }
        else {
            var obj = {
                "content_type": "text",
                "title": title[i],
                "payload": payload[i]
            }
        }
        optArr.push(obj);
    }

    return {
        "text": text,
        "quick_replies": optArr
    }
}
