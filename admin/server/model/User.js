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
        return new Promise(async (res, rej) => {
            const selecting = new Select(this.db)

            await selecting.process("")
            let users = await selecting.action({items:"*", table:"user"})
            selecting.close()
            if(users.status === 0) rej(users) 
    
            let post = await selecting.action({items: "*", table: "post"})
            selecting.close()
            if(post.status === 0) rej(post)

            // Prepare the chart Data
            let chartData = {
                name: "",
                uv: 0,
                pv: 2400,
                amt: 2400
            }

            // prepare structure of data for users
            let userData = {
                total: users.content.length,
                timeFrame: []
            }
            // prepare structure of data for posts
            let postData = {
                total: post.content.length,
                category: {},
                timeFrame: []
            }

            // Users config
            // Get the years of each users
            let userDates = users.content.map((val) => new Date(val.date.trim()).getFullYear())
            // Count the users per each year, first create a unique array
            new Set(userDates).forEach((val) => {
                let r = userDates.filter(p => p === val)

                userData.timeFrame.push({...chartData, name: val, uv: r.length})
            })

            // Get the years of each users and configure the categories
            let postDates = post.content.map((val) => {
                //new Date(val.date.trim()).getFullYear()
                
                // Fetch and count the post categories
                !postData.category[val.category] ? 
                postData.category[val.category] = 1 : 
                postData.category[val.category] += 1
                // Fetch and count the post per year
                return new Date(val.date.trim()).getFullYear()
            })
            
            new Set(postDates).forEach(val => {
                let r = userDates.filter(p => p === val)
                postData.timeFrame.push({...chartData, name: val, uv: r.length})
            })
        

            this.status = 1
            this.type = "success"
            this.message = "void"
            this.content = {users: userData, posts: postData}
            

            res(this.deliver())
        })
    }

    async deleteUser() {
        const val = this.data.val
        return await Func.deleteRows(val, "users")
    }
}

module.exports = User