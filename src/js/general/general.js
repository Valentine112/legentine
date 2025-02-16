
window.addEventListener("load", function() {

    var pathObj = new Func().getPath()
    var path = pathObj['main_path']

    // Creating this due to the other segregation in rank page
    // As there would also be all time and weekly
    var more = ""
    if(path == "rank"){
        var ranking_time = document.querySelector(".ranking-time")
        more = ranking_time.querySelector(".active").getAttribute("value")
    }
    
    if(path != "read" && path != "profile"){
        // Fetch post
        new Post().fetch_post(path, "", more)
    }

    // Hide the contents loader if there is
    var contents_loader = document.querySelector(".content-loader")
    if(contents_loader != null) {
        contents_loader.style.display = "none"
    }

    // Lazy images
    this.setInterval(() => {
        if(this.document.querySelector(".lazy-load-image") != null){
            new Func().intersect_show_image("data-image", "src", 0.75, ".lazy-load-image")
        }

    }, 1000)

})

// Click events for elements would be activated here
document.body.addEventListener("click", async function(e) {
    var elem = e.target

    var action = ""

    // Check if the targetted element has the data-action attribute
    if(elem.getAttribute("data-action") != null) {
        action = elem.getAttribute("data-action")
    }else{

        // Check if the parent has the class as action which signifies there is a data-toggle
        if(elem.closest(".action") != null) {
            elem = elem.closest(".action")
            action = elem.getAttribute("data-action")
        }
    }

    // Hide the large option if anywhere other than the toggle option is clicked
    if(action != "toggle_options"){
        if(document.querySelector("[data-status=option-active]") != null) {
            var active_option = document.querySelector("[data-status=option-active]")
            active_option.style.display = "none"
            active_option.removeAttribute("data-status")
        }
    }

    var func = new Func()

    // Declare the post
    var post = new Post()

    // Declare the user
    var user = new User()

    // Declare the feature
    var feature = new Feature()

    // Declare the comment
    var comment = new CommentActions()

    // Declare the notification
    var notification = new Notifications()

    switch (action) {
        case "unlist_user":
            user.unlist_user(elem)

            break;
        
        case "send-feedback":
            var feedback = document.getElementById("feedbackContent")
            user.sendFeedback(elem, feedback)

            break;

        case "category":
            var category_cover = elem.closest(".category")
            // Check that we are not on the same page
            if(category_cover.querySelector(".active span").innerText != elem.querySelector("span").innerText){
                var contents_loader = document.querySelector(".content-loader")
                if(contents_loader != null) {
                    // display the loader first
                    contents_loader.style.display = "block"
                }

                var path = func.getPath()['main_path']

                // Creating this due to the other segregation in rank page
                // As there would also be all time and weekly
                var more = ""
                if(path == "rank"){
                    var ranking_time = document.querySelector(".ranking-time").querySelector(".active")
                    more = ranking_time.getAttribute("value")
                }

                // Remove the current active element
                category_cover.querySelector(".active").classList.remove("active")

                // Set a new active element next
                elem.classList.add("active")

                // Fetch the post
                var filter = elem.getAttribute("value")
                post.fetch_post(path, filter, more)
            }
            break;

        case "time-section":
            var path = func.getPath()['main_path']

            var parent = elem.closest(".ranking-time")
            //Check that we are not on the same page
            if(parent.querySelector(".active span").innerText != elem.querySelector("span").innerText) {
                parent.querySelector(".active").classList.remove("active")
                elem.classList.add("active")

                // Get the time section
                var more = ""
                if(path == "rank") {
                    var ranking_time = parent.querySelector(".active")
                    more = ranking_time.getAttribute("value")
                }

                // Get the category section
                var category_cover = document.querySelector(".category")

                // Remove the current active element
                var filter = category_cover.querySelector(".active").getAttribute("value")

                post.fetch_post(path, filter, more)
            }
            break;

        case "sub-dropdown":
            // Doing this because the focus on css, when trying to toggle the feature items doesn't work properyly

            var sub_item = elem.querySelector(".feature-dropdown-item")
            if(sub_item != null) {
                var feature_display = getComputedStyle(sub_item).getPropertyValue("display")

                if(feature_display === "block"){
                    sub_item.style.display = "none"

                    elem.querySelector(".dropdown-icon").style.transform = "rotateZ(0deg)"
                }else{
                    sub_item.style.display = "block"

                    elem.querySelector(".dropdown-icon").style.transform = "rotateZ(180deg)"
                }
            }

            break

        case "show_sidebar":
            var promise = new Promise(res => {
                res(
                    document.querySelector(".sidebar").style.display = "block"
                )
            })
            await promise
            setTimeout(() => {
                document.querySelector(".sidebar-list").style.right = "0%"
                document.querySelector(".sidebar-closure").style.backgroundColor = "rgba(0, 0, 0, 0.3)"
            }, 0200)

            break

        case "toggle_options":
            post.toggle_options(elem)

            break
    
        case "toggle_comment":
            post.toggle_comment(elem)

            break

        case "delete-entity":
            post.delete_entity(elem)

            break

        case "properties":
            post.properties(elem)

            break;

        case "save_post":
            post.save_post(elem)

            break;

        case "remove_saved_post":
            post.remove_saved_post(elem)

            break;

        case "react":
            post.react(elem)

            break;

        case "create-comment":
            comment.create_comment()
            
            break;

        case "reply-comment":
            comment.reply_comment(elem)

            break;

        case "edit-comment":
            comment.edit_comment(elem)

            break;

        case "edit-comment-1":
            comment.edit_comment_1(elem)
            break;

        case "delete-comment":
            comment.delete_comment(elem)
            break;

        case "create-reply":
            comment.create_reply(elem)

            break;

        case "reply-reply":
            comment.reply_reply(elem)

            break;

        case "edit-reply":
            comment.edit_reply(elem)

            break;
        
        case "edit-reply-1":
            comment.edit_reply_1(elem)

            break;
        
        case "delete-reply":
            comment.delete_reply(elem)

            break;

        case "feature-request":
            feature.request(elem)
            
            break;

        case "confirm-feature":
            feature.confirmFeature(elem)

            break;

        case "quiet-feature":
            feature.quietFeature(elem)

            break;

        case "view-notification":
            notification.viewNotification(elem)
            break;

        default:
            break;
    }

    if(elem.classList.contains("edit-options")){
        if(elem.closest(".small-option") != null) elem.closest(".small-option").remove()
    }

})