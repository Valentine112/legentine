
window.addEventListener("load", function() {

    // Hide the contents loader if there is]
    var contents_loader = document.querySelector(".content-loader")
    if(contents_loader != null) {
        contents_loader.style.display = "none"
    }

    setTimeout(() => {
        if(this.document.querySelector(".lazy-load-image") != null){
            new Func().intersect_show_image("data-image", "src", 0.75, ".lazy-load-image")
        }

    }, 3000)


})

// Click events for elements would be activated here
document.body.addEventListener("click", async function(e) {
    var elem = e.target
    var parent = elem

    var action = ""

    // Check if the targetted element has the data-action attribute
    if(elem.getAttribute("data-action") != null) {
        action = elem.getAttribute("data-action")
    }else{
        // Check if the parent has the class as action which signifies there is a data-toggle
        if(elem.closest(".action") != null) {
            elem = elem.closest(".action")
            action = elem.getAttribute("data-action")
            parent = elem.closest(".action")
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

    // Declare the post
    var post = new Post()

    // Declare the user
    var user = new User()

    switch (action) {
        case "sub-dropdown":
            // Doing this because the focus on css, when trying to toggle the feature items doesn't work properyly

            var sub_item = elem.querySelector(".feature-dropdown-item")
            if(sub_item != null) {
                var feature_display = this.getComputedStyle(sub_item).getPropertyValue("display")

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

        case "delete_post":
            post.delete_post(elem)

            break

        case "properties":
            post.properties(elem)

            break;

        case "save_post":
            post.save_post(elem)

            break;

        case "react":
            post.react(elem)

            break;

        case "unlist_user":
            user.unlist_user(elem)

            break;

        default:
            break;
    }

    if(elem.classList.contains("edit-options")){
        if(elem.closest(".small-option") != null) elem.closest(".small-option").remove()
    }

})