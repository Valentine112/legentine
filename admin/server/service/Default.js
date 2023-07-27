const Response = require("./Response")
const Select = require("../query/Select")
const Func = require("./Func")
const Insert = require("../query/Insert")

async function DefaultLogin (DB) {
    let res = ""
    const func = new Func()

    let payload = {
        "id": 1,
        "2": "2",
        "needle": "*",
        "table": "admin"
    }

    let findAdmin = await func.searchDb(DB, payload, "OR")
    // Check that there was no error
    if(findAdmin.status === 0) return findAdmin


    // Create the admin if none is registered
    if(findAdmin.content.length === 0) {
        console.log("Help")
        // Add the credentials
        const USER = "Kakarotgoku"
        const PASS = "justapassword1!"

        // Hash password
        const passwordHash = await func.generateHash(PASS)
        // Proceed to store in database
        const token = await func.genRanToken()
        const inserting = new Insert(DB, 
            "admin", 
            ["token", "username", "password", "lastSession"],
            ""
        )
        res = await inserting.action([token, USER, passwordHash, new Date()])

    }

    return res
}


module.exports = DefaultLogin