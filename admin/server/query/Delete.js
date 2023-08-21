const Response = require("../service/Response");

class Delete extends Response {

    constructor(DB, more) {
        super()

        this.db = Db
        this.more = more
        this.value = []
        this.type = ""
        this.query = ""
    }
    
    process() {
        let prepared = []
        return new Promise(res => {
            // Check if more is not empty
            if(this.more !== "" && this.more.trim().length > 0) {
                let moreSplit = this.more.split(",")
                this.query = moreSplit[0]
                moreSplit.forEach((elem, ind) => {
                    if(ind > 0) {
                        // Store the types
                        prepared.push("s")
                        // Store the value
                        this.value.push(elem.trim())
                    }
                })
                // Join the type array
                res(this.type = prepared.join(""))
            }else{
                res()
            }
        })
    }

    async action(table) {
        await this.process()
        const sql = `DELETE FROM ${table} ${this.query}`

        return new Promise((resolve, reject) => {
            this.db.query(sql, this.value, (err, res) => {
                if(err) {
                    // Check if there is an error and resolve it
                    this.status = 0
                    this.type = "error"
                    this.message = "void"
                    this.content = `Error while deleting record, Error: ${err}`

                    reject(this.deliver())
                }

                this.status = 1
                this.type = "success"
                this.message = "void"
                this.content = res

                resolve(this.deliver())
            })
        })
    }
}

module.exports = Delete