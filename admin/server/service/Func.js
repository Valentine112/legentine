const { check, body, validationResult } = require("express-validator")
const Response = require("./Response")
const Select = require("../query/Select")
const Argon = require("argon2")
const crypto = require("crypto")

class Func extends Response{

    constructor() {super()}

    clean(data, mode) {
        let error 
        // get the typeof data
        switch (mode) {
            case "email":
                return check(data).isEmail().normalizeEmail()

            case "length":
                return check(data).trim()

            case "clean":
                return data.trim().escape()
        
            default:
                break;
        }
    }

    searchDb(DB, payload, operator) {
        // Initialize the error message first
        // To avoid rewriting

        this.status = 0
        this.type = "error"
        this.message = "void"
        // Confirm that the data is an object first
        return new Promise(async resolve => {
            // Validate that the payload sent is an Object
            if((typeof payload).toLowerCase() === "object") {
                const keys = Object.keys(payload)
                const values = Object.values(payload)

                this.content = ""
                // Check to see if the data is properly formatted
                for (let i = 0; i < 4; i++) {
                    if(!keys[i]) {
                        this.content = "Invalid payload provided"
                        resolve(this.deliver())
                    } 
                }
                // Proceed to fetch the info using the instructions from the payload 
                const selecting = new Select(DB)

                const sql = `WHERE ${keys[0]} = ? ${operator} ${keys[1]} = ?# ${values[0]}# ${values[1]}`

                
                await selecting.process(sql)
                // Named argunments used here
                let res = await selecting.action( {items: values[2], table: values[3]} )

                // Reset values
                selecting.close()

                resolve(res)

            }else{
                this.content = `payload should be of type Object, ${typeof payload} given`
            }

            resolve(this.deliver())
        })
    }

    generateHash(data) {
        this.status = 0
        this.type = "error"
        this.message = "void"

        return new Promise(async res => {
            if(data.length > 0) {
                this.status = 1
                this.content = await Argon.hash(data)
            }else{
                this.content = "Hash should not be empty"
            }

            res(this.deliver())
        })
    }

    confirmHash(hash, userHash) {
        this.status = 0
        this.type = "error"
        this.message = "void"

        return new Promise(async res => {
            if(hash.length > 0) {
                this.status = 1
                this.content = await Argon.verify(hash, userHash)
            }else{
                this.content = false
            }

            res(this.deliver())
        })
    }

    genRanToken() {
        return new Promise(res => res(crypto.randomUUID()))
    }

    deleteRows(val, table) {
        return new Promise((res, rej) => {
            // Loop throught the user tokens
            val.content.forEach(async elem => {
                // Convert the token to Id to verify it
                let data = {
                    "token": elem,
                    "1": "1",
                    "needle": "id",
                    "table": table
                }

                let res1 = await this.searchDb(this.db, data, "AND")
                // check if the return is an integer, that's how we verify
                this.content = "User is invalid"
                if(typeof Number(res1) !== "number") return this.deliver()
                const deleting = new Delete(this.db, `WHERE id = ?# ${res1}`)
                let result = await deleting.action("users")

                result.status === 0 ? rej(result) : res(result)
            })
        })
    }



}

module.exports = Func