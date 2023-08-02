const Response = require("../service/Response")

class Update extends Response {

    value = []
    more1 = ""

    constructor(DB, more) {
        super()
        
        this.db = DB
        this.more = more
    }

    process() {
        if(this.more != "" && this.more.length > 0) {
            let moreSplit = this.more.split("#")
            this.more1 = moreSplit[0]
            let moreLength = moreSplit.length
            
            return new Promise(res => {
                for (let i = 0; i < moreLength; i++) {
                    if(i > 0)
                        res(this.value.push(moreSplit[i]))
                }
            })
        }
        else{
            Promise.resolve("")
        }
    }

    async action(table) {
        await this.process()

        const sql = `UPDATE ${table} ${this.more1}`

        return new Promise(resolve => {
            this.db.query(sql, this.value, (err, res) => {
                if(err) {
                    // Check if there is an error and resolve it
                    this.status = 0
                    this.type = "error"
                    this.message = "void"
                    this.content = `Error while udating the result, Error: ${err}`

                    resolve(this.deliver())
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

module.exports = Update