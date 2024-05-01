
module.exports.generateKey = function (keyName, senderPsid) {
    // console.log('KEEEY  === ', keyName+senderPsid);
    return keyName+senderPsid;
}