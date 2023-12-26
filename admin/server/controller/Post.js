const UserModel = require("../model/User");
const Response = require("../service/Response");

class Post extends Response {

    constructor(db, payload) {
        super()

        this.db = db
        this.payload = payload
    }

    async main() {
        // Navigate through the action to see admin the user really wants
        switch (this.payload.body.action) {
            case "fetchPosts":
                return await new PostModel({ Db: this.db, data: this.payload }).fetchPosts()

        
            default:
                break;
        }
    }
}

module.exports = Post