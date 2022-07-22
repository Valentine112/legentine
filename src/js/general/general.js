
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
window.addEventListener("click", function(e) {
    var elem = e.target

    var action = ""

    // Check if the targetted element has the data-action attribute
    if(elem.getAttribute("data-action") != null) {
        action = elem.getAttribute("data-action")
    }else{
        // Check if the parent has the class as action which signifies there is a data-toggle
        if(elem.closest(".action") != null) {
            action = elem.closest(".action").getAttribute("data-action")
        }
    }

    var post = new Post()

    switch (action) {
        case "toggle_options":
            post.toggle_options(elem)

            break
    
        case "toggle_comment":
            post.toggle_comment(elem)

            break
        default:
            break;
    }

})