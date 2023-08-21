const Select = require("../query/Select")
const Func = require("../service/Func")
const Response = require("../service/Response")

class Login extends Response {

    constructor({Db, data} = {}) {
        super()

        this.db = Db
        this.payload = data
        this.data = data.body
    }

    async login() {
        const func = new Func()
        // Fetch the username and password from the database
        // Then proceed to verify them before letting the user in
        this.status = 0
        this.type = "error"
        this.message = "void"
        this.content = ``

        const val = this.data.val

        console.log(val)

        // Confirm if username exists
        let payload = {
            'username': val.username,
            '1': '1',
            'needle': '*',
            'table': 'admin'
        }

        let value = await func.searchDb(this.db, payload, 'AND')

        return new Promise(async resolve => {
            // Check there was an error in fetching the code
            if(value.status === 0) resolve(value)

            let content = value.content
            // Check if the user exist
            if(content.length > 0){
                content = content[0]
                // Verify the password
                let verify = await func.confirmHash(content.password, val.password)

                if(verify.content){
                    this.status = 1
                    this.type = "success"
                    this.message = "fill"
                    this.content = "login"

                    // Create a new session
                    this.payload.session.auth = true
                    this.payload.session.token = content.token

                }else{
                    this.message = "fill"
                    this.content = "Invalid username and password"
                }
            }else{
                this.message = "fill"
                this.content = "Invalid username and password"

            }

            resolve(this.deliver())
        })

    }
}

module.exports = Login