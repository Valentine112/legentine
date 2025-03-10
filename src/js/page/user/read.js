window.addEventListener("load", async function() {
    var post_photo = this.document.querySelector(".photo img")
    var post_body = this.document.querySelector(".post-body")
    var username = this.document.querySelector(".post-username")
    var title = this.document.querySelector(".post-title")
    var content = this.document.querySelector(".post-content span")
    var reaction_box = this.document.querySelector(".reaction-box")
    var reaction_count = this.document.querySelector(".reaction-count span")
    var category = this.document.querySelector(".category span")
    var reader = this.document.querySelector(".reader span")
    var date = this.document.querySelector(".date span")

    var func = new Func()

    // Check there is a parameter in the link
    // If there is, this would mean that the user wants to update a post
    var param = func.getPath()

    
    if(!func.isEmpty(param['parameter'])) {
        // Fetching the post
        if(param['parameter']['token'] != null) {
            // Fetch post content

            var data = {
                part: "post",
                action: "fetch_post",
                val: {
                    from: param['main_path'],
                    filter: param['parameter'],
                }
            }

            // Fetch the post first
            func.request("../request.php", JSON.stringify(data), 'json')
            .then(async function(val) {

                if(val.status === 1) {
                    var other = val.content[0]['other']
                    var post = val.content[0]['post']
                    var more =  val.content[0]['more']
                    var comment = val.content[0]['comments']
                    var feature = val.content[0]['feature']

                    // The post token
                    post_body.setAttribute("data-token", "LT-" + post['token'])

                    document.getElementById("profileLink").setAttribute("href", more['profile'])
                    post_photo.setAttribute("src", '../src/' + other['photo'])
                    username.innerText = other['username']
                    title.innerText = post['title']
                    content.innerText = post['content']

                    // Set react button
                    reaction_box.innerHTML = config_react(more)
                    reaction_count.innerText = post['stars'] == 0 ? "" : post['stars']

                    // Category
                    category.innerText = post['category']
                    // Reader
                    reader.innerText = post['readers']
                    // Date
                    date.innerText = func.timeFormatting(post['date'])

                    var promise = new Promise(res => {
                        res(
                            // Comments
                            comment.forEach(elem => {
                                comment_box = new Comment(elem)
                                document.querySelector(".comment-content").insertAdjacentHTML("beforeend", comment_box.main())
                            })
                        )
                    })

                    // Add reader
                    var data = {
                        part: "post",
                        action: "reader",
                        val: {
                            from: param['main_path'],
                            post: post['token'],
                        }
                    }

                    // Add the reader count here
                    func.request("../request.php", JSON.stringify(data), 'json')

                    // Set the feature content
                    feature['content'].forEach(elem => {
                        var featureContent = elem['feature']
                        if(featureContent['response'] === 1) {
                            var type = 0
                            if(func.stripSpace(featureContent['content']).length > 0){
                                type = 1
                            }
    
                            document.querySelector(".feature-content").insertAdjacentHTML('beforeend', featureBox(elem, type))
                        }
                    })

                        
                    var featureElem = document.querySelector("[data-action=feature-request]")

                    // Set for the Feature actions
                    if(post['user'] != val.content[0]['self']['user']){

                        var feature = more['feature']
                        if(feature == -1) {
                            // No request has been sent yet
    
                        }else{
                            var featureStatus = feature['response']
    
                            if(featureStatus == 0){
                                // Requested, but not yet accepted
    
                                featureElem.innerText = "Cancel feature"
                                featureElem.style.cssText = "background-color: #ff465b; color: #fff";
                            }
                            else if(featureStatus == 1) {
                                featureElem.innerText = "Compose"
                                featureElem.style.cssText = "background-color: dodgerblue; color: #fff";
                                featureElem.removeAttribute("data-action")
                                let composeLink = document.getElementById("composeLink")
                                composeLink.appendChild(featureElem)
                                composeLink.setAttribute("href", `compose?token=${feature['token']}`)
                            }
    
                        }
                    }else{
                        featureElem.remove()
                    }
                }

                func.notice_box(val)

                // Transversing from notification section
                // Fetch the type first and match the functions
                // ---------------------------------------

                await promise
                if(param['parameter']['type'] != null) {
                    var elemType = param['parameter']['type']

                    var comment = param['parameter']['comment']
                    var commentElem = document.querySelector("[data-token=LT-" + comment + "]")

                    if(elemType === "comment") {
                        // Transversing via parameters passed in the url
                        commentElem.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        })
                    }

                    if(elemType === "reply") {
                        // Transversing via parameters passed in the url
                        var elem = commentElem.querySelector("#replyAction")
                        var reply = param['parameter']['reply']

                        // Triggering the reply action
                        // Since the element to be transvered to is a reply and not a comment
                        var promise = new Promise((res) => {
                            res(new CommentActions().reply_comment(elem))
                        })

                        // Proceeding to scroll to the reply after the reply box has been displayed
                        // Using promise to wait for the reply box to be popped up
                        // Making sure that everything is ready before requesting for the reply element

                        await promise
                        setTimeout(() => {
                            document.querySelector("[data-token=LT-" + reply + "]").scrollIntoView({
                                behavior: 'smooth',
                                block: 'start'
                            })
                        }, 1000)

                    }

                }
            
            })

            // --------------- REPLY SCROLL, MoreData -------------- //

            var replySub = document.querySelector(".reply-sub")
            replySub.addEventListener("scroll", function() {

                if(replySub.scrollTop + replySub.clientHeight >= replySub.scrollHeight - 20) {

                    var elements = document.querySelectorAll(".reply-entity"),
                    // Get the last comment on the page
                    last_element = elements[elements.length - 1],
                    // Get the token from the last comment and remove the initials
                    last_element_token = func.removeInitials(last_element.getAttribute("data-token"))

                    // Fetch the comment to which holds the replies
                    var replyComment = document.getElementById("reply-comment")
                    commentToken = func.removeInitials(replyComment.getAttribute("data-token"))

                    var data = {
                        part: "moreData",
                        action: "reply",
                        val: {
                            lastElement: last_element_token,
                            filter: "",
                            more: commentToken
                        }
                    }

                    new Func().request("../request.php", JSON.stringify(data), 'json')
                    .then(val => {
                        if(val.status == 1) {
                            console.log(val.content)
                            var content = val.content
                            var reply_cover = document.querySelector(".reply-cover")
                            content.forEach(elem => {
                                var token = elem['reply']['token']

                                if(document.querySelector(`[data-token=LT-${token}]`) == null) {
                                    val.content.forEach(elem => {
                                        var reply = new Reply(elem)
                    
                                        reply_cover.insertAdjacentHTML("beforeend", reply.main())
                                    })
                                }
                            })
                        }
                    })

                }
            })

        }
    }
})

function config_react(data) {
    if(data['starred']){
        return `
            <img src="../src/icon/post-icon/star.svg" alt="" class="reaction star active" data-action="react">

            <img src="../src/icon/post-icon/unstar.svg" alt="" class="reaction unstar" data-action="react">
        `
    }else{
        return `
            <img src="../src/icon/post-icon/unstar.svg" alt="" class="reaction unstar active" data-action="react">

            <img src="../src/icon/post-icon/star.svg" alt="" class="reaction star" data-action="react">
        `
    }
}

function featureBox(data, type) {
    var feature = data['feature']
    var other = data['other']
    var composedFeature = `
        <div class="composed-feature">
            <h1>featuring <a href="profile?token=${other['id']}">${other['username']}</a></h1>
            <span>
                <!-- Feature content goes here -->
                ${feature['content']}
            </span>
        </div>
    `
    var pendingFeature = `
        <div class="pending-feature">
            <span>Pending from <a href="profile?token=${other['id']}">${other['username']}</a>...</span>
        </div>
    `

    if(type == 0) {
        return pendingFeature
    }else if(type == 1) {
        return composedFeature
    }
}