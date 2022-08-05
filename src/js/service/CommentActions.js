class CommentActions {

    constructor() {
        this.pathObj = new Func().getPath()
        this.func = new Func()

        return this
    }

    create_comment() {
        var comment_elem = document.getElementById("comment-value")
        var comment = comment_elem.innerText
        if(new Func().stripSpace(comment).length > 0) {
            var post = document.querySelector(".post-body").getAttribute("data-token")

            post = this.func.removeInitials(post)
            var data = {
                part: "comment",
                action: "create_comment",
                val: {
                    from: this.pathObj['main_path'],
                    content: comment,
                    filter: post
                }
            }

            comment_elem.innerText = ""
            // Fetch the post first, then comments next
            this.func.request("../request.php", JSON.stringify(data), 'json')
            .then(async function(val) {
                console.log(val)
                if(val.status === 1){
                    var comments = new Comment(val.content['comment'])
                    document.querySelector(".comment-content").insertAdjacentHTML("afterbegin", comments.main())
                }

                new Func().notice_box(val)
            })

        }else{
           comment_elem.focus()
        }
    }
}