const mysql = require("mysql");

const DB = mysql.createConnection({
	host: "localhost",
	user: "binemmanuel",
	password: "SMARTlogin89",
	database: "legentine",
});

DB.connect((err) => {
	if (err) throw err;

	console.log("Working");
});

module.exports = DB;
