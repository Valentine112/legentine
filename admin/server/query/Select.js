const Response =  require("./../service/Response")

class Select extends Response {

    constructor(DB) {
        super()

        this.db = DB
        this.more1 = ""
        this.value = []
        this.types = []
    }

    process(more) {
        if(more != "" && more.length > 0) {
            let moreSplit = more.split("#")
            this.more1 = moreSplit[0]
            let moreLen = moreSplit.length

            return new Promise(res => {
                for (let i = 0; i < moreLen; i++) {
                    if(i > 0) {
                        this.value.push(moreSplit[i].trim())
                    }
                }
                res(this.value)
            })
        }
        else{
            return Promise.resolve(this.value)
        }
    }

    // Named arguments since i always forget their position
    action({items, table} = {}) {
        //let moreSplit = this.more.split("#")
        let more_ = this.more1

        let sql = `SELECT ${items} FROM ${table} ${more_}`;
        
        return new Promise(resolve => {
            this.db.query(sql, this.value, (err, res) => {
                if(err) {
                    // Check if there is an error and resolve it
                    this.status = 0
                    this.type = "error"
                    this.message = "void"
                    this.content = `Error while fetching the result, Error: ${err}`

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

    close() {
        this.more1 = ""
        this.value = []
        this.types = []
    }
}

module.exports = Select