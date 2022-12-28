class CommentActions {

    constructor() {
        this.pathObj = new Func().getPath()
        this.func = new Func()

        return this
    }

    create_comment() {
        var mentions = []
        var comment_elem = document.getElementById("comment-value")
        var comment = comment_elem.innerText

        // Fetch the mentions from the comment
        mentions = new Func().fetch_mentions(comment)

        if(new Func().stripSpace(comment).length > 0) {
            var post = document.querySelector(".post-body").getAttribute("data-token")

            post = this.func.removeInitials(post)
            var data = {
                part: "comment",
                action: "create_comment",
                val: {
                    from: this.pathObj['main_path'],
                    content: comment,
                    mentions: mentions,
                    filter: post
                }
            }

            comment_elem.innerText = ""
            // Fetch the post first, then comments next
            this.func.request("../request.php", JSON.stringify(data), 'json')
            .then(async function(val) {
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
        var self = this
        var comment = elem.getAttribute("data-comment")

        var comment_input = document.getElementById("comment-value")

        // Fetch the mentions from the comment
        var mentions = new Func().fetch_mentions(comment_input.innerText)

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
                    comment: comment,
                    mentions: mentions
                }
            }

            // Fetch the post first, then comments next
            this.func.request("../request.php", JSON.stringify(data), 'json')
            .then(async function(val) {

                // Revert everything back to how it was
                self.cancel_edit(elem.closest(".cancel-box").querySelector(".cancel-edit"), "create-comment")

                if(val.status === 1){
                    var comment_box = document.querySelector("[data-token=LT-" + val.content['token'])

                    comment_box.querySelector(".user-comment").innerHTML = val.content['comment']

                }

                new Func().notice_box(val)
            })


        }else{
            comment_input.focus()
        }
    }

    async delete_comment(elem) {
        var parent = elem.closest(".entity-body")

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

        call_animation(parent, data)
    }

    reply_comment(elem) {
        var parent = elem.closest(".comment-box")
        var token = parent.getAttribute("data-token")
        token = this.func.removeInitials("token")

        // Fetch post token
        var post = document.querySelector(".post-body").getAttribute("data-token")
        post = this.func.removeInitials(post)

        // Fetch the comment token
        var comment = this.func.removeInitials(parent.getAttribute("data-token"))

        // Display the reply box first
        // Feed in the values of the comment next
        // Fetch all the replies

        var reply_box = document.querySelector(".reply")
        reply_box.setAttribute("data-reply-token", token)
        reply_box.setAttribute("data-post-token", post)

        reply_box.style.display = "block"

        // MODIFYING THE COMMENT BOX FOR THE COMMENT THAT WAS REPLIED TO
        var personLink = document.getElementById("personLink")
        var comment_body = document.getElementById("reply-comment")
        var photo = document.getElementById("comment-photo")
        var username = document.getElementById("comment-username")
        var content = document.getElementById("comment-content")
        var date = document.getElementById("comment-date")

        personLink.setAttribute("href", "profile?token=" + parent.getAttribute("data-user"))
        comment_body.setAttribute("data-token", "LT-" + comment)
        username.innerText = parent.querySelector(".username").innerText
        content.innerHTML = parent.querySelector(".user-comment").innerHTML
        date.innerText = parent.querySelector(".date").innerText
        photo.setAttribute("src", parent.querySelector(".comment-img").getAttribute("src"))

        // FETCHING THE REPLIES HERE
        var data = {
            part: "comment",
            action: "fetch_reply",
            val: {
                from: this.pathObj['main_path'],
                comment: comment,
                post: post
            }
        }

        // Fetch the post first, then comments next
        this.func.request("../request.php", JSON.stringify(data), 'json')
        .then(async function(val) {

            if(val.status === 1) {

                var reply_cover = document.querySelector(".reply-cover")
                // Empty the reply cover before inserting new elements
                reply_cover.innerHTML = ""

                val.content.forEach(elem => {
                    var reply = new Reply(elem)

                    reply_cover.insertAdjacentHTML("afterbegin", reply.main())
                })
            }

            new Func().notice_box(val)
        })

    }

    create_reply(elem) {
        var mentions = []
        var reply_elem = document.getElementById("reply-value")
        var reply = reply_elem.innerText

        // Fetch the mentions from the comment
        mentions = new Func().fetch_mentions(reply)

        if(new Func().stripSpace(reply).length > 0) {
            // Fetch the post token
            var post = document.querySelector(".post-body").getAttribute("data-token")

            // Fetch the comment token
            var comment = document.getElementById("reply-comment")
            comment = this.func.removeInitials(comment.getAttribute("data-token"))

            post = this.func.removeInitials(post)
            var data = {
                part: "comment",
                action: "create_reply",
                val: {
                    from: this.pathObj['main_path'],
                    content: reply,
                    mentions: mentions,
                    comment: comment,
                    post: post
                }
            }

            reply_elem.innerText = ""
            // Fetch the post first, then comments next
            this.func.request("../request.php", JSON.stringify(data), 'json')
            .then(async function(val) {
                if(val.status === 1) {
                    var reply_box = new Reply(val.content['comment'])

                    document.querySelector(".reply-cover").insertAdjacentHTML("afterbegin", reply_box.main())
                }

                new Func().notice_box(val)
            })

        }else{
           reply_elem.focus()
        }
    }

    reply_reply(elem) {
        var parent = elem.closest(".replies")
        var reply_value = document.getElementById("reply-value")

        // Check if its the same person
        if(parent.getAttribute("data-self") != parent.getAttribute("data-other")){
            var username  = parent.querySelector(".reply-username span")

            // Check if person has been mention previously
            if(reply_value.innerHTML.search("@" + username.innerText) === -1){
                reply_value.innerHTML += "@" + username.innerText
            }
        }

        reply_value.focus()
    }

    edit_reply(elem) {
        var parent = elem.closest(".replies")
        var reply_input = document.getElementById("reply-value")
        // Fetch the user comment to place in the input field
        var user_comment = parent.querySelector(".reply-content")
        // Fetch the button to change the data-action
        var send = document.querySelector(".send-reply")

        reply_input.innerText = user_comment.innerText
        reply_input.focus()

        send.setAttribute("data-action", "edit-reply-1")
        send.setAttribute("data-reply", parent.getAttribute("data-token"))

        elem.closest(".main-content").querySelector(".cancel-edit").style.display = "inline"
    }

    edit_reply_1(elem) {
        var self = this
        var reply = elem.getAttribute("data-reply")

        var reply_input = document.getElementById("reply-value")

        // Fetch post token
        var post = document.querySelector(".post-body").getAttribute("data-token")
        post = self.func.removeInitials(post)

        if(new Func().stripSpace(reply_input.innerText).length > 0) {
            // Fetch the mentions from the comment
            var mentions = new Func().fetch_mentions(reply_input.innerText)

            reply = this.func.removeInitials(reply)
            var data = {
                part: "comment",
                action: "edit_reply",
                val: {
                    from: this.pathObj['main_path'],
                    content: reply_input.innerText,
                    post: post,
                    reply: reply,
                    mentions: mentions
                }
            }

            this.func.request("../request.php", JSON.stringify(data), 'json')
            .then(async function(val) {
                console.log(val)

                // Revert everything back to how it was
                self.cancel_edit(elem.closest(".cancel-box").querySelector(".cancel-edit"), "create-reply")

                if(val.status === 1){
                    var reply_box = document.querySelector("[data-token=LT-" + val.content['token'])

                    reply_box.querySelector(".reply-content").innerHTML = val.content['content']

                }

                new Func().notice_box(val)
            })
        }
    }

    delete_reply(elem) {
        var parent = elem.closest(".entity-body")

        // Fetch post token
        var post = document.querySelector(".post-body").getAttribute("data-token")
        post = this.func.removeInitials(post)

        var data = {
            part: "comment",
            action: "delete_reply",
            val: {
                from: this.pathObj['main_path'],
                token: "",
                post: post
            }
        }

        call_animation(parent, data)
    }

    cancel_edit(elem, action) {
        var parent = elem.closest(".cancel-box")
        var input_value = parent.querySelector(".input-value")
        // Fetch the button to change the data-action
        var send = parent.querySelector("#send")
    
        // Revert the button back to it's defaults
        send.removeAttribute("data-comment")
        send.removeAttribute("data-reply")

        send.setAttribute("data-action", action)
    
        input_value.innerText = ""
    
        elem.style.display = "none"
    }
}