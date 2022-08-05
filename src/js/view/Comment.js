class Comment {

    constructor(data) {
        this.data = data

        this.comment = data['comment']
        this.other = data['other']
        this.self = data['self']
        this.more = data['more']

        return this
    }

    main() {
        var element = `
            <div class="comment-box"
                data-token=LT-${this.comment['token']}
                data-post=${this.comment['post']}
            >
                <div class="comment-details">
                    <div>
                        <a href="">
                            <img src="../${this.other['photo']}" alt="">
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
                        ${this.comment['comment']}
                        </span>
                    </div>
                </div>

                <div class="comment-options">
                    <div>
                        <span>Reply</span>
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
        if(this.self === this.comment['user'] || this.more['post_owner'] === this.self){
            return `
                <div>
                    <span>Edit</span>
                </div>
                
                <div>
                    <span>Delete</span>
                </div>
            `
        }else{
            return ``
        }
    }
}