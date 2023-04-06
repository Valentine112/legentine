class Comment {

    constructor(data) {
        this.func = new Func()
        this.data = data

        this.comment = data['comment']
        this.other = data['other']
        this.self = data['self']
        this.more = data['more']

        return this
    }

    main() {
        var element = `
            <div class="comment-box entity-body"
                data-token=LT-${this.comment['token']}
                data-post=${this.comment['post']}
                data-user=${this.other['id']}
            >
                <div class="comment-details">
                    <div>
                        <a href="profile?token=${this.other['id']}">
                            <img src="../src/${this.other['photo']}" alt="" class="comment-img">
                        </a>
                    </div>

                    <div>
                        <span class="username">
                        `
                        +
                        // Username goes here
                        `
                        ${this.other['username']}
                        </span><br>
                        <span class="user-comment">
                            `
                            +
                            // Comment goes here
                            `
                            ${this.func.domParser(this.comment['content'], 'text/html')}
                        </span>
                        &ensp;
                        <span class="date">
                            ${this.func.timeFormatting(this.comment['date'])}
                        </span>
                    </div>
                </div>

                <div class="comment-options">
                    <div>
                        <span id="replyAction" data-action="reply-comment">Reply - ${this.comment['replies']}</span>
                    </div>
                    `
                    +
                    // Display options based on user
                    `
                    ${this.config_authority()}
                </div>
            </div>
        `

        return element
    }

    config_authority() {
        // First check if user is the owner of the comment
        // If he is, display all options
        // Else if he's owner of post, then display only the delete option
        // Else display no option
        if(this.self === this.comment['user']){
            return `
                <div>
                    <span data-action="edit-comment">Edit</span>
                </div>
                <div>
                    <span data-action="delete-comment">Delete</span>
                </div>
            `
        }
        else if(this.more['post_owner'] === this.self){
            return `
                <div>
                    <span data-action="delete-comment">Delete</span>
                </div>
            `
        }else{
            return ``
        }
    }
}