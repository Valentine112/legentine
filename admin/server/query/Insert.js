const Response =  require("./../service/Response")

class Insert extends Response{

    constructor(DB, where, items, more) {
        super()

        this.db = DB
        this.where = where
        this.items = items
        this.more = more 

        // The query question marks
        this.ques = ""
    }

    process() {
        return new Promise(resolve => {
            // Get length of items to pad the question mark
            const itemLength = this.items.length
            // Padding the question mark
            this.ques = this.ques.padEnd(itemLength, "?")
            // Seperating it with comma
            this.ques = this.ques.split("").join(",")

            resolve(this.ques)
        })
    }

    async action(values) {
        // Call the process method
        await this.process()
        //Convert the items to string
        const item = this.items.join(",")
        // Write the query
        const sql = `INSERT into ${this.where} (${item}) VALUES (${this.ques}) ${this.more};`
        return new Promise(resolve => {
            this.db.query(sql, values, (err, res) => {
                if(err) {
                    // Check if there is an error and resolve it
                    this.status = 0
                    this.type = "error"
                    this.message = "void"
                    this.content = `Error while inserting data, ${err}`

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

module.exports = Insert