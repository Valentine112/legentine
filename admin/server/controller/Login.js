const Response = require("../service/Response")
const LoginModel = require("../model/Login")

class Login extends Response{

    constructor(db, payload) {
        super()

        this.db = db
        this.payload = payload
    }

    async main() {
        // Navigate through the action to see admin the user really wants
        switch (this.payload.body.action.toLowerCase()) {
            case "login":
                return await new LoginModel({ Db: this.db, data: this.payload }).login()
        
            default:
                break;
        }
    }

}

module.exports = Login