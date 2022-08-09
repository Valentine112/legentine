class Reply {

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
            <div class="replies box">
                <div>
                    <div>
                        <img src="../src/photo/image.jpg" alt="">
                    </div>

                    <div>
                        <div class="reply-username">
                            <span>Himself</span>
                        </div>
                        <div class="reply-content">
                            <span>
                                Now we can comment
                                &ensp;
                                <span class="date">
                                14 hours ago
                                </span>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="reply-options">
                    <div>
                        <span>Delete</span>
                    </div>
                </div>
            </div>
        `
    }

}