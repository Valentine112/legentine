class Post {
    
    constructor() {
        this.func = new Func()

        return this
    }

    fetch_post(from, filter, more) {
        if(from === "home" || from === "rank" || from === "profile" || from === "saved") {
            var filter_value

            (filter == "all") ? filter_value = "" : filter_value = filter

            var result = ""

            var data = {
                part: "post",
                action: "fetch_post",
                val: {
                    from: from,
                    filter: filter_value,
                    more: more
                }
            }


            this.func.request("../request.php", JSON.stringify(data), 'json')
            .then(val => {
                console.log(val)
                result  = val
                
                if(val.status === 1){
                    var content = val.content
                    var postCover = document.getElementById("postCover")

                    // Remove all the previous content if there is a filter
                    if(filter != ""){
                        postCover.innerHTML = ""
                    }
                    
                    content.forEach(elem => {
                        var post = new PostHTML(elem, from, "../")
                        postCover.insertAdjacentHTML("beforeend", post.main())
                    })
                }

                this.func.notice_box(val)
            })

            return result
        }
    }

    toggle_options(elem) {
        var large_screen = document.querySelector(".large")
        var small_screen = document.querySelector(".small")

        var large_display = getComputedStyle(large_screen).getPropertyValue("display")
        var small_display = getComputedStyle(small_screen).getPropertyValue("display")

        // Check if the options exist
        if(document.querySelector(".options") != null){

            // Display the wanted option by getting its direct parent and transversing back to it

            var parent = elem.closest(".post-body")
            var large_option = parent.querySelector(".large-option")

            // Display the right option for large screen
            if(small_display === "none" && large_display === "flex") {
                if(getComputedStyle(large_option).getPropertyValue("display") === "none") {

                    // hide all options first
                    var options = document.querySelectorAll(".options")
                    options.forEach(opt => {
                        opt.style.display = "none"
                    })

                    large_option.style.display = "block"
                    large_option.setAttribute("data-status", "option-active")
                }else{
                    large_option.style.display = "none"
                    large_option.removeAttribute("data-status")
                }

            }else if(large_display === "none" && small_display === "flex") {
                // Setting the data to pass to the option class
                // This would be used to configure how it would display
                // And how its actions would be processed
                var token = parent.getAttribute("data-token")
                token = new Func().removeInitials(token)

                var data = {
                    token: token,
                    owner: parent.getAttribute("data-owner"),
                    title: parent.getAttribute("data-title"),
                    username: parent.getAttribute("data-username"),
                    photo: parent.getAttribute("data-photo"),
                    comment_state: parent.getAttribute("data-comments-state"),
                    saved_state: parent.getAttribute("data-saved-state")
                }

                var small_option = document.querySelector(".small-option")

                if(document.querySelector(".small-option") == null){
                    var path = new Func().getPath()['main_path']

                    // For small screen options
                    parent.insertAdjacentHTML("afterbegin", new Options(data, path, "../").main())

                    document.querySelector(".small-option .options").style.display = "block"
                }else{
                    small_option.remove()
                }
            }
        }
    }

    toggle_comment(elem) {
        var parent = elem.closest(".post-body")
        var token = parent.getAttribute("data-token")
        token = new Func().removeInitials(token)

        var comment_state = parent.getAttribute("data-comments-state")

        var data = {
            part: "post",
            action: 'toggle_comment',
            val: {
                token: token,
                comment_state: comment_state
            }
        }


        this.func.request("../request.php", JSON.stringify(data), 'json')
        .then(val => {

            if(val.status === 1){
                var edit_options = elem

                // Get the two elements that needs modifying
                var img = edit_options.querySelector("img"),
                span = edit_options.querySelector("span")


                var src, state, text

                if(val.content === 0){
                    src = "../src/icon/option-icon/block-comment.svg"
                    state = 0
                    text = "Block Comments"

                }else{
                    src = "../src/icon/option-icon/allow-comment.svg"
                    state = 1
                    text = "Allow Comments"
                }

                img.setAttribute("src", src)
                span.setAttribute("data-state", state)
                span.innerText = text

                // Set the comments-state for the parent post
                // This would help when check options for a post
                // And also for properties

                parent.setAttribute("data-comments-state", state)

            }

            this.func.notice_box(val)
        })
    }

    async delete_entity(elem) {
        // First hide the post
        // Display delete notice and start the animation
        // Then set a timeout for 5 seconds
        // If delete notice is clicked within that time, cancel the timeout
        // Proceed to send the resources for deleting

        var parent = elem.closest(".entity-body"),
        token = parent.getAttribute("data-token")

        var data = {
            part: "post",
            action: "delete_post",
            val: {
                token: ""
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

    properties(elem) {
        var parent = elem.closest(".post-body")
        var data = {
            "name": parent.getAttribute("data-name"),
            "username": parent.getAttribute("data-username"),
            "rating": parent.getAttribute("data-rating"),
            "title": parent.getAttribute("data-title"),
            "category": parent.getAttribute("data-category"),
            "word-count": parent.getAttribute("data-word-count"),
            "stars": parent.getAttribute("data-stars"),
            "comments_state": parent.getAttribute("data-comments-state"),
            "date": parent.getAttribute("data-date")
        }

        parent.insertAdjacentHTML("beforeend", Properties(data))
    }

    save_post(elem) {
        var post_body = elem.closest(".post-body"),
        token = new Func().removeInitials(post_body.getAttribute("data-token"))

        var data = {
            part: "post",
            action: 'save_post',
            val: {
                token: token,
            }
        }

        this.func.request("../request.php", JSON.stringify(data), 'json')
        .then(val => {
            if(val.status === 1){
                var span = elem.querySelector("span")

                console.log(elem)

                if(val.content == "Post Saved"){
                    post_body.setAttribute("data-saved-state", 1)
                    span.innerText = "Unsave"

                }else if(val.content == "Post Unsaved"){
                    post_body.setAttribute("data-saved-state", 0)
                    span.innerText = "Save"
                }
            }
            this.func.notice_box(val)
        })
    }

    async remove_saved_post(elem) {
        // First hide the post
        // Display delete notice and start the animation
        // Then set a timeout for 5 seconds
        // If delete notice is clicked within that time, cancel the timeout
        // Proceed to send the resources for deleting

        var parent = elem.closest(".entity-body"),
        token = parent.getAttribute("data-token")

        var data = {
            part: "post",
            action: "remove_saved_post",
            val: {
                token: ""
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

    react(elem) {
        var box = elem.closest(".reaction-box")
        var type = elem.getAttribute("data-type")

        if(type === "unstar") {
            box.querySelector(".unstar").classList.remove("active")
            box.querySelector(".star").classList.add("active")

        }else if(type === "star") {
            box.querySelector(".star").classList.remove("active")
            box.querySelector(".unstar").classList.add("active")

        }
        
        var post_body = elem.closest(".post-body"),
        token = new Func().removeInitials(post_body.getAttribute("data-token"))

        var data = {
            part: "post",
            action: 'react',
            val: {
                token: token,
            }
        }

        this.func.request("../request.php", JSON.stringify(data), 'json')
        .then(val => {

            if(val.status === 1){

                var type = val.content['type']
                var count = val.content['count']

                if(type === "star") {
                    box.querySelector(".unstar").classList.remove("active")
                    box.querySelector(".star").classList.add("active")

                }else{
                    if(type === "unstar") {
                        box.querySelector(".star").classList.remove("active")
                        box.querySelector(".unstar").classList.add("active")
                    }
                }

                if(count === 0) count = ""

                // Show the total number of likes here
                post_body.querySelector(".reaction-count span").innerText = count
            }
        })
    }

}