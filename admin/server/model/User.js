const Select = require("../query/Select");
const Response = require("../service/Response");
const Func = require("../service/Func");
const Delete = require("../query/Delete");

class User extends Response{

    constructor( {Db, data} = {}) {
        super()

        this.db = Db
        this.payload = data
        this.data = data.body
    }

    async fetchHome() {
        // Fetch the users, post
        return new Promise(async res => {
            const selecting = new Select(this.db)

            await selecting.process("")
            let users = await selecting.action({items:"*", table:"user"})
            selecting.close()
            if(users.status === 0) res(users)
    
            let post = await selecting.action({items: "*", table: "post"})
            selecting.close()
            if(post.status === 0) res(post)
        

            this.status = 1
            this.type = "success"
            this.message = "fill"
            this.content = {users: users, post: post}

            console.log(users.date)

            res(this.deliver())
        })
    }

    async deleteUser() {
        const val = this.data.val
        return await Func.deleteRows(val, "users")
    }
}

module.exports = User