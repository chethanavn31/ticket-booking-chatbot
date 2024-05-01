var redis = require("redis");
var client = redis.createClient();

client.on("connect", function () {
    console.log("You are now connected");
});

module.exports = {
    async setData(key, data) {
        return new Promise(async function (resolve, reject) {
            try {
                client.hmset(key, data,

                    function (error, obj) {
                        if (error) {
                            console.log(error);
                        }
                        else {
                            resolve(obj)
                        }
                    });
            }
            catch (e) {
                console.log(e)
                reject(null);
            }

        });
    },
    async getData(key) {
        return new Promise(async function (resolve, reject) {
            try {
                client.hgetall(key,

                    function (error, obj) {
                        if (error) {
                            console.log(error);
                        }
                        else {
                            resolve(obj)
                        }
                    });
            }
            catch (e) {
                console.log(e)
                reject(null);
            }

        });
    },

    async deleteData(key) {
        return new Promise(async function (resolve, reject) {
            try {
                client.del(key);
                resolve(true)
            }
            catch (e) {
                console.log(e)
                resolve(false)
            }
        });
    },

    async deleteAllData(){
        return new Promise(async function (resolve, reject) {
        client.flushdb( function (err, succeeded) {
            if(err){
                resolve(false);
            }
            else{
                resolve(true);
            }
            console.log(succeeded); // will be true if successfull
        });
    });
    }
}