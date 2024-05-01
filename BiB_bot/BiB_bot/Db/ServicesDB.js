/*
 * @Author: mukund 
 * @Date: 2020-08-06 15:46:45 
 * @Last Modified by: mukund.gokulan
 * @Last Modified time: 2020-10-13 21:33:31
 */

const { spawn } = require('child_process');
var moment = require('moment');
module.exports = {
	pythonServicesFunctionCall: function (pyFile, receivedData) {

		//var serviceStart = moment().format('YYYY-MM-DDTHH:mm:ss.SSS');
		return new Promise(async function (resolve, reject) {					
			var dataToSend = "";
			var errCode = 3;

			try {
				const python = spawn('python3', [process.cwd() +'/Db/Services/'+ pyFile, JSON.stringify(receivedData)]);
				python.stdout.on('data', function (data) {
					dataToSend = dataToSend + data.toString();					
				});

				python.stderr.on('data', (data) => {
						console.log(data.toString());
						dataToSend = dataToSend + data.toString();
				});
				python.on('close', (code) => {	
					console.log(pyFile);				
					console.log(code);
				});

				python.on('exit', (code) => {
					console.log(code);
					console.log(dataToSend)
					if (code == 0) {
						var pResp = {};
						try {
							pResp = JSON.parse(dataToSend)
							resolve(pResp.data);
						}
						catch (e) {
							console.log(pyFile);
							console.log(e);
						}
					}
					else {
						var pErrResp = {};
						try {
							resolve(pErrResp.data);
						}
						catch (e) {
							console.log(pyFile);
							console.log(e);
						}
					}					
				  });
			}
			catch (e) {
				console.log(pyFile);
				console.log(e);
			}
		});
	},

	

}