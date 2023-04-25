
var user = ""
var person = ""

window.addEventListener("load", async function () {
    var func = new Func()

    var pathObj = func.getPath()
    var path = pathObj['main_path']

    var param = pathObj['parameter']['token'] != null ? pathObj['parameter']['token'] : ""

    // Fetch user info
    var data = {
        part: "notification",
        action: 'fetchNotification',
        val: {}
    }

    func.request("../request.php", JSON.stringify(data), 'json')
    .then(val => {
        if(val.status == 1) {
            var content = val.content

            content.forEach(elem => {
                var notification = new notificationBox(elem)
                document.getElementById("postCover").insertAdjacentHTML("beforeend", notification.main())
            })
        }

        new Func().notice_box(val)
    })
})

class notificationBox {

    constructor(data) {
        this.notification = data['notification']
        this.other = data['other']
        this.post = data['post']
        this.content = data['content']
    }

    main() {
        return `
            <div class="notificationBox ${this.process()['status']}">
                <a href="${this.process()['path']}">
                    <div class="notificationSub">
                        <div>
                            <img src=" " class="lazy-load-image" data-image="${"../src/" + this.other['photo']}" alt="">
                        </div>
                        <div>
                            ${this.process()['message']}
                            &ensp;
                            <small>${new Func().timeFormatting(this.notification['date'])}</small>
                        </div>
                    </div>
                </a>
            </div>
        `
    }

    process() {
        var result = {
            message: "",
            status: "",
            path: ""
        }

        // We processed the message formatting here based on the TYPE of notification, not elementType

        switch (this.notification['type']) {
            case "comment":
                result.message = ` ${this.other['username']} <span class="comment">commented</span> "${this.contentFormat()}" on your post "${this.post['title']}" `

                break;

            case "reply":
                result.message = `${this.other['username']} <span class="reply">replied</span> with "${this.contentFormat()}" on your comment`

                break;

            case "mention":
                result.message = `${this.other['username']} <span class="mention">mentioned</span> you "${this.contentFormat()}" `
                

                break;
        
            default:
                break;
        }

        // Formatting the right link based on the elementType
        if(this.notification['elementType'] == "comment") {
            result.path = `read?token=${this.post['token']}&comment=${this.content['token']}`
        }
        else if(this.notification['elementType'] == "reply") {
            result.path = `read?token=${this.post['token']}&comment=${this.notification['more']}&reply=${this.content['token']}`
        }


        // Process the status of the notification here

        if(this.notification['status'] == 1) {
            result.status = "seen"
        }

        return result
    }

    contentFormat() {
        var result = this.content['content']
        if(new Func().stripSpace(this.content['content']).length > 15) {
            result = this.content['content'].substr('0, 15') + "..."
        }

        return result
    }
}