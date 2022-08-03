class Comment {

    constructor(data) {
        this.data = data

        return this
    }

    main() {
        var element = `
            <div class="comment-box"
                data-token=${this.data['token']}
                data-post=${this.data['post']}
            >
                <div class="comment-details">
                    <div>
                        <a href="">
                            <img src="../src/photo/image.jpg" alt="">
                        </a>
                    </div>

                    <div>
                        <span class="username">Himself</span><br>
                        <span class="user-comment">I have a day to make it all work, but i have forever to make it. What's really needed for this because my mind is so clouded</span>
                    </div>
                </div>

                <div class="comment-options">
                    <div>
                        <span>Reply</span>
                    </div>

                    <div>
                        <span>Edit</span>
                    </div>
                    
                    <div>
                        <span>Delete</span>
                    </div>
                </div>
            </div>
        `
    }
}