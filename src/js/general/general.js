
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

    var action = elem.getAttribute("data-action")
    var post = new Post()

    switch (action) {
        case "toggle_options":
            post.toggle_options(elem)

            break;
    
        default:
            break;
    }

})