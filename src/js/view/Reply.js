class Reply {

    constructor(data) {
        this.func = new Func()
        this.data = data

        this.reply = data['reply']
        this.other = data['other']
        this.self = data['self']
        this.more = data['more']

        return this
    }

    main() {
        var element = `
            <div class="replies box entity-body"
            data-token=LT-${this.reply['token']}
            data-self=${this.self}
            data-other=${this.reply['user']}
            >
                <div>
                    <div>
                        <a href="profile?token=${this.other['id']}">
                            <img src="../src/${this.other['photo']}" alt="">
                        </a>
                    </div>

                    <div>
                        <div class="reply-username">
                            <span>${this.other['username']}</span>
                        </div>
                        <div>
                            <span class="reply-content">
                                ${this.reply['content']}
                            </span>
                            &ensp;
                            <span class="date">
                                ${new Func().timeFormatting(this.reply['date'])}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="reply-options">
                    ${this.authorOptions()}
                    <div>
                        <span data-action="reply-reply">Reply</span>
                    </div>
                </div>
            </div>
        `

        return element
    }

    authorOptions() {
        // THis options is for the owner of the reply
        if(this.reply['user'] === this.self) {
            return `
                <div>
                    <span data-action="edit-reply">Edit</span>
                </div>

                <div>
                    <span data-action="delete-reply">Delete</span>
                </div>
            `
        }
        // This option is for the owner of the post
        else if(this.more['post_owner'] === this.self) {
            return `
                <div>
                    <span data-action="delete-reply">Delete</span>
                </div>
            `
        }else{
            return ``
        }
    }

}