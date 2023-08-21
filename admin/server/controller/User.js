const UserModel = require("../model/User");
const Response = require("../service/Response");

class User extends Response {

    constructor(db, payload) {
        super()

        this.db = db
        this.payload = payload
    }

    async main() {
        // Navigate through the action to see admin the user really wants
        switch (this.payload.body.action) {
            case "fetchHome":
                return await new UserModel({ Db: this.db, data: this.payload }).fetchHome()

        
            default:
                break;
        }
    }
}

module.exports = User