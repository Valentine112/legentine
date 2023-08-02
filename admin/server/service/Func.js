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



}

module.exports = Func