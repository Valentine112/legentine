const Response = require("../service/Response")

class Update extends Response {

    value = []
    more1 = ""

    constructor(DB, more) {
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
            new Promise.resolve()
        }
    }

    async action() {
        await this.process()


    }
}