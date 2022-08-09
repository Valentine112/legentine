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

    edit_comment(elem) {
        var parent = elem.closest(".comment-box")
        var comment_input = document.getElementById("comment-value")
        // Fetch the user comment to place in the input field
        var user_comment = parent.querySelector(".user-comment")
        // Fetch the button to change the data-action
        var send = document.getElementById("send")

        comment_input.innerText = user_comment.innerText
        comment_input.focus()

        send.setAttribute("data-action", "edit-comment-1")
        send.setAttribute("data-comment", parent.getAttribute("data-token"))

        document.getElementById("cancel-edit-comment").style.display = "inline"
    }

    edit_comment_1(elem) {
        var comment = elem.getAttribute("data-comment")

        var comment_input = document.getElementById("comment-value")
        // Fetch post token
        var post = document.querySelector(".post-body").getAttribute("data-token")
        post = this.func.removeInitials(post)

        if(new Func().stripSpace(comment_input.innerText).length > 0) {

            comment = this.func.removeInitials(comment)
            var data = {
                part: "comment",
                action: "edit_comment",
                val: {
                    from: this.pathObj['main_path'],
                    comment_value: comment_input.innerText,
                    post: post,
                    comment: comment
                }
            }

            comment_input.innerText = ""

            // Fetch the post first, then comments next
            this.func.request("../request.php", JSON.stringify(data), 'json')
            .then(async function(val) {

                // Revert everything back to how it was
                cancel_edit_comment(document.getElementById("cancel-edit-comment"))

                if(val.status === 1){
                    var comment_box = document.querySelector("[data-token=LT-" + val.content['token'])
                    comment_box.querySelector(".user-comment").innerText = val.content['comment']

                }

                new Func().notice_box(val)
            })


        }else{
            comment_input.focus()
        }
    }

    async delete_comment(elem) {
        var parent = elem.closest(".entity-body"),
        token = parent.getAttribute("data-token")

        // Fetch post token
        var post = document.querySelector(".post-body").getAttribute("data-token")
        post = this.func.removeInitials(post)

        var data = {
            part: "comment",
            action: "delete_comment",
            val: {
                from: this.pathObj['main_path'],
                token: "",
                post: post
            }
        }

        var delete_notice = document.querySelector(".delete-notice")

        var promise = new Promise(res => {
            res(
                delete_notice.setAttribute("data-delete-token", token),
                call_animation(parent, data)
            )
        })
        await promise
    }

    reply_comment(elem) {
        var parent = elem.closest(".comment-box")
        var token = parent.getAttribute("data-token")
        token = this.func.removeInitials("token")

        // Fetch post token
        var post = document.querySelector(".post-body").getAttribute("data-token")
        post = this.func.removeInitials(post)

        // Display the reply box first
        // Feed in the values of the comment next
        // Fetch all the replies

        var reply_box = document.querySelector(".reply")
        reply_box.setAttribute("data-reply-token", token)
        reply_box.setAttribute("data-post-token", post)

        // Feed the values
        var photo = document.getElementById("comment-photo")
        var username = document.getElementById("comment-username")
        var content = document.getElementById("comment-content")
        var date = document.getElementById("comment-date")

        username.innerText = elem.querySelector(".username").innerText
        content.innerText = elem.querySelector(".user-comment").innerText
        date.innerText = elem.querySelector(".date").innerText

    }
}

function cancel_edit_comment(self) {
    var comment_input = document.getElementById("comment-value")
    // Fetch the button to change the data-action
    var send = document.getElementById("send")

    // Revert the button back to it's defaults
    send.removeAttribute("data-comment")
    send.setAttribute("data-action", "create-comment")

    comment_input.innerText = ""

    self.style.display = "none"
}