const mysql = require("mysql");

const DB = mysql.createConnection({
	host: "localhost",
	user: "root",
	password: "",
	database: "legentine",
});

DB.connect((err) => {
	if (err) throw err;

	console.log("Working");
});

module.exports = DB;
