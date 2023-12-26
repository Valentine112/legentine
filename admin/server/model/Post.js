const Select = require("../query/Select");
const Response = require("../service/Response");

class Post extends Response {
    constructor( {Db, data} = {}) {
        super()

        this.db = Db
        this.payload = data
        this.data = data.body
    }

    async fetchPosts() {
        return new Promise(async (res, rej) => {
            const selecting = new Select(this.db)
            await selecting.process("")
            const posts = await selecting.action({item: "*", table: "post"})

            selecting.close()
            if(posts.status === 0) rej(posts)
            res(posts)
        })
    }
    
}

modules.export = Post