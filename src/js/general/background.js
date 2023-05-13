window.addEventListener("load", () => {

    var userIdentification = ""

    var func = new Func()

    var pathObj = func.getPath()
    var path = pathObj['main_path']
    var param = pathObj['parameter']['token'] != null ? pathObj['parameter']['token'] : ""

    // ------------------LOAD SEARCH PREVIEW----------------------- //

    // Load the search preview
    if(this.document.querySelector(".search") != null) {
        var search = this.document.querySelector(".search")

        var people = search.querySelector(".people")
        var post = search.querySelector(".post")
        var recentSearches = document.querySelector(".recent-searches")

        var data = {
            part: "user",
            action: 'openSearch',
            val: {}
        }

        new Func().request("../request.php", JSON.stringify(data), 'json')
        .then(val => {

            if(val.status === 1) {

                // Profile on the sidebar menu
                document.getElementById("sidebarProfile").innerHTML = SidebarProfile(val.content['user'][0])

                // Configure the feature quiet status on the nav and side bar
                var featureStatus = ""
                if(val.content['user'][0]['quiet'] == 0){
                    featureStatus = "Quiet"
                }else{
                    featureStatus = "Allow"
                }

                document.querySelectorAll(".quiet").forEach(elem => {
                    elem.innerText = featureStatus
                })

                // END //

                // Create the people
                val.content['people'].forEach(elem => {
                    var openSearch = new OpenSearch(elem)
    
                    people.insertAdjacentHTML("afterbegin", openSearch.People());
                })

                // Create the post
                val.content['post'].forEach(elem => {
                    var openSearch = new OpenSearch(elem)
    
                    post.insertAdjacentHTML("afterbegin", openSearch.Post());
                })

                // Create the recently searched
                val.content['recent'].forEach(elem => {
                    var openSearch = new OpenSearch(elem)

                    recentSearches.insertAdjacentHTML("afterbegin", openSearch.Recent())
                })
            }

        })
    }

    // --------------------END-------------------- //


    // --------------LOAD NEW ELEMENT ONCE USER REACHES THE BOTTOM-------------//

    // Load the new elements once the user reaches the bottom of the page
    window.addEventListener("scroll", function() {

        // List of valid paths to perform this action
        // This is to reduce the uneccessary running in the background
        var validPaths = ["home", "profile", "saved", "notification", "privatePost", "featureRequest", "featureHistory"]

        if(validPaths.includes(path)){
            // Check if user has reachd the bottom of the page
            if(window.scrollY + window.innerHeight >= document.body.scrollHeight) {
                // Fetch all the elements on the page
                var elements = document.querySelectorAll(".entity-body"),
                // Get the last element on the page
                last_element = elements[elements.length - 1],
                // Get the token from the last element and remove the initials
                last_element_token = func.removeInitials(last_element.getAttribute("data-token"))

                // Create the data in regards to the type of content to fetch
                // Configure based on the page
                var action = ""
                var more = ""
                var filter = ""

                switch (path) {
                    case "home":

                        action = "more_post"
                        // Get the current category
                        var category = document.querySelector(".category").querySelector(".active")
                        category = category.getAttribute("value");

                        category == "all" ? filter = "" : filter = category

                        break;

                    case "profile":
                        
                        action = "profile"
                        // Get the current category
                        var category = document.querySelector(".headerSectionSub").querySelector(".active")

                        category = category.getAttribute("data-type")

                        // Setting the category, either notes or photos
                        category == "all" ? filter = "" : filter = category

                        // Passing the person's id
                        more = param
                        break;

                    case "saved":
                        // The saved post should have its own token from the post token
                        last_element_token = func.removeInitials(last_element.getAttribute("data-saved-token"))

                        action = "saved";

                        break;

                    case "notification":
                        // Setting the action to notification
                        action = "notification"
                        
                        break;
                    
                    case "privatePost":
                        // Setting the action to privatePost
                        action = "privatePost"

                        break;

                    case "featureRequest":
                        // Setting the action to featureRequest
                        action = "featureRequest"

                        break;

                    case "featureHistory":
                        // Setting the action to featureHistory
                        action = "featureHistory"

                        break;
                
                    default:
                        break;
                }

                var data = {
                    part: "moreData",
                    action: action,
                    val: {
                        lastElement: last_element_token,
                        filter: filter,
                        more: more
                    }
                }


                new Func().request("../request.php", JSON.stringify(data), 'json')
                .then(val => {

                    if(val.status == 1) {
                        // If there is not item, content would return null

                        var postCover = document.getElementById("postCover")

                        var content = val.content

                        console.log(content)
                        if(!new Func().isEmpty(content)) {
                            if(path == "home") {
                                content.forEach(elem => {
                                    var token = elem['post']['token'];
        
                                    if(document.querySelector("[data-token=LT-" + token + "]") == null) {
                                        var post = new PostHTML(elem, path, "../")
                                        postCover.insertAdjacentHTML("beforeend", post.main())
                                    }
                                })
                            }
        
                            if(path == "profile") {

                                // Get the container that would hold the newer elements
                                if(filter == "notes") {
                                    var container = document.getElementById("postCover")
                                }
                                else if(filter == "photos") {
                                    var container = document.getElementById("photoSub")
                                }

                                content.forEach(elem => {
                                    // Get the skeleton of the element
                                    if(filter == "notes") {
                                        var elementBox = new PostHTML(elem, path, "../").main()
                                        var token = elem['post']['token']
                                    }
                                    else if(filter == "photos") {
                                        var elementBox = photoBox(elem)
                                        var token = elem['content']['token']
                                    }

                                    if(document.querySelector("[data-token=LT-" + token + "]") == null) {
                                        container.insertAdjacentHTML("beforeend", elementBox)
                                    }
                                })
                            }

                            if(path == "saved") {
                                content.forEach(elem => {
                                    var token = elem['more']['savedToken'];

        
                                    if(document.querySelector(`[data-saved-token=LT-${token}]`) == null) {
                                        var post = new PostHTML(elem, path, "../")
                                        postCover.insertAdjacentHTML("beforeend", post.main())
                                    }
                                })
                            }

                            if(path == "notification") {
                                content.forEach(elem => {
                                    if(document.querySelector(`[data-token=LT-${elem['notification']['token']}]`) == null) {

                                        var notification = new Notification(elem)
                                        document.getElementById("postCover").insertAdjacentHTML("beforeend", notification.main())
                                    }
                                })
                            }

                            if(path == "privatePost") {
                                content.forEach(elem => {
                                    var token = elem['post']['token'];
        
                                    if(document.querySelector("[data-token=LT-" + token + "]") == null) {
                                        var post = new PostHTML(elem, path, "../")
                                        postCover.insertAdjacentHTML("beforeend", post.main())
                                    }
                                })
                            }

                            if(path == "featureRequest") {
                                var features = content['content']

                                features.forEach(elem => {
                                    var token = elem['feature']['token']
                                    
                                    if(document.querySelector("[data-token=LT-" + token + "]") == null) {
                                        document.getElementById("featureBox").insertAdjacentHTML("beforeend", featureBox(elem))
                                    }
                                })
                            }

                            if(path == "featureHistory") {
                                var history = content['content']

                                history.forEach(elem => {
                                    var token = elem['history']['token']
                                    
                                    if(document.querySelector("[data-token=LT-" + token + "]") == null) {
                                        document.getElementById("featureBox").insertAdjacentHTML("beforeend", historyBox(elem, val.content['self']))
                                    }
                                })
                            }

                        }
                    }

                    func.notice_box(val)
                })
            }
        }
    })

    // ------------------------------ END ------------------------ //


    // --------------INDICATE NEW NOTIFICATION ---------------------- //

    // Called this outside, so that it would only have to fine the element once
    var liveNotification = document.querySelector(".live-notification")
    var notificationBar = document.querySelector(".notification-bar")
    var notificationBox = document.querySelector(".notification-box")

    async function newNotification() {

        // Fetch user id first
        var data = {
            part: "user",
            action: 'userIdentification',
            val: {}
        }

        await new Func().request("../request.php", JSON.stringify(data), 'json')
        .then(val => {
            userIdentification = val.content
        })


        var eventSource = new EventSource("../eventSourceRequest.php?part=live&action=liveNotification", {
            withCredentials: true
        })

        eventSource.addEventListener(`LT-${userIdentification}`, (ev) => {
            var result = JSON.parse(ev.data)['content']

            var count = 0
            // Check if its not empty
            if(result.length > 0) {
                // Empty the notification box first
                notificationBox.innerHTML = ""
                result.forEach(val => {
                    var token = ""
                    // Getting the token for the element
                    if(data['type'] == "feature"){
                        token = data['feature']['token']
                    }else if(data['type'] == "notification") {
                        token = data['notification']['token']
                    }

                    // Counting how many elements to pop when the loop is done
                    if(liveNotification.querySelector("[data-token=LT-" + token + "]") == null) {
                        // Check if the number of notifications has exceeded 20, then increment the count to trim them

                        if(notificationBox.querySelectorAll(".notifications").length >= 20) count++

                        notificationBox.insertAdjacentHTML('beforeend', LiveNotification(val))
                    }
                })

                // Proceed to remove the extra elements
                for (let i = 0; i < count; i++) {
                    var notificationChild = notificationBox.querySelectorAll(".notifications")
                    console.log(notificationChild)
                    notificationBox.removeChild(notificationChild[notificationChild.length - 1])       
                }
                notificationBar.style.display = "block"
            }
        })
    }

    newNotification()

    // --------------END --------------- //


})

function SidebarProfile(data) {
    var element = `
        <a href="profile?token=${data['id']}">
            <div class="profile-sub">
                <div>
                    <img src="../src/${data['photo']}" alt="">
                </div>
                <div>
                    <div>
                        <span>${data['fullname']}</span>
                    </div>

                    <div>
                        <span>${data['username']}</span>
                    </div>
                </div>
            </div>
        </a>
    `

    return element
}

function LiveNotification(data) {
    // Link
    var link = ""
    var time = new Date(data['sortMethod']['sortDate']).toLocaleTimeString('en-us', {
        hour: '2-digit',
        minute: '2-digit',
        hour12: true
    })
    
    var token = ""
    var message = ""

    if(data['type'] == "feature") {
        link = `featureRequest?token=${data['feature']['token']}`
        token = data['feature']['token']

        message = `${data['other']['username']}
            made a request to be <span class="feature">featured</span> on your 
            ${data['post']['category'].toLowerCase()}, 
            "${data['post']['title']}"
            `
    }

    else if(data['type'] == "notification") {

        // Formatting the right link based on the elementType
        if(data['notification']['elementType'] == "comment") {
            link = `read?type=comment&token=${data['post']['token']}&comment=${data['content']['token']}`
        }
        else if(data['notification']['elementType'] == "reply") {
            link = `read?type=reply&token=${data['post']['token']}&comment=${data['content']['comment']}&reply=${data['content']['token']}`
        }


        token = data['notification']['token']

        // Trimming the length of the content for the notification
        var content = data['content']['content']
        if(new Func().stripSpace(content).length > 15) {
            content = content.substr('0, 15') + "..."
        }
    
        // Text formatting
        switch (data['notification']['type']) {
            case "comment":
                message = ` ${data['other']['username']} <span class="comment">commented</span> "${content}" on your post "${data['post']['title']}" `

                break;

            case "reply":
                message = `${data['other']['username']} <span class="reply">replied</span> with "${content}" on your comment`

                break;

            case "mention":
                message = `${data['other']['username']} <span class="mention">mentioned</span> you "${content}" `
                
                break;
        
            default:
                break;
        }
    }


    var result = `
        <div 
            class="notifications"
            data-token="LT-${token}"
            data-type="${data['type']}"
            data-action="seen-notification"
        >
            <a href="${link}">
                <div class="notification-text">
                    ${message}
                </div>
                <div class="notification-date">${time}</div>
            </a>
        </div>
    `

    return result
}