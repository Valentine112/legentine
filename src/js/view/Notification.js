class Notification {

    constructor(data) {
        this.notification = data['notification']
        this.other = data['other']
        this.post = data['post']
        this.content = data['content']
    }

    main() {
        return `
            <div 
            class="notificationBox entity-body ${this.process()['status']}"
            data-token="LT-${this.notification['token']}"
            >
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
            result.path = `read?type=comment&token=${this.post['token']}&comment=${this.content['token']}`
        }
        else if(this.notification['elementType'] == "reply") {
            result.path = `read?type=reply&token=${this.post['token']}&comment=${this.content['comment']}&reply=${this.content['token']}`
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