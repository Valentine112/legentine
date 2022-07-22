class Post {
    
    constructor() {
        this.func = new Func()

        return this
    }

    fetch_post(from, filter) {
        var result = ""

        var data = {
            part: "post",
            action: "fetch_post",
            val: {
                from: from,
                filter: filter
            }
        }

        this.func.request("../request.php", JSON.stringify(data), 'json')
        .then(val => {
            result  = val
            
            if(val.status === 1){
                var content = val.content
                content.forEach(elem => {
                    var post = new PostHTML(elem, from, "../")

                    document.querySelector(".article-content").insertAdjacentHTML("beforeend", post.main())
                })
            }

            //this.func.notice_box(val)
        })

        return result

    }

    toggle_options(elem) {
        var large_screen = document.querySelector(".large")
        var small_screen = document.querySelector(".small")

        var large_display = getComputedStyle(large_screen).getPropertyValue("display")
        var small_display = getComputedStyle(small_screen).getPropertyValue("display")

        // Check if the options exist
        if(document.querySelector(".options") != null){

            // hide all options first
            var options = document.querySelectorAll(".options")
            options.forEach(opt => {
                opt.style.display = "none"
            })

            // Display the wanted option by getting its direct parent and transversing back to it

            var parent = elem.closest(".post-body")
            var main_option = parent.querySelector(".options")

            // Display the right option for large screen
            if(small_display === "none" && large_display === "flex") {
                if(getComputedStyle(main_option).getPropertyValue("display") === "none") {
                    main_option.style.display = "block"
                }else{
                    main_option.style.display = "none"
                }

            }else if(large_display === "none" && small_display === "flex") {
                // Setting the data to pass to the option class
                // This would be used to configure how it would display
                // And how its actions would be processed

                var data = {
                    token: parent.getAttribute("data-token"),
                    owner: parent.getAttribute("data-owner"),
                    title: parent.getAttribute("data-title"),
                    username: parent.getAttribute("data-username"),
                    photo: parent.getAttribute("data-photo"),
                    comment_state: parent.getAttribute("data-comments-state")
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
        var comment_state = parent.getAttribute("data-comments-state")

        var data = {
            part: "post",
            action: 'toggle_comment',
            val: {
                token: token,
                comment_state: comment_state
            }
        }

        func.request("../request.php", JSON.stringify(data), 'json')
        .then(val => {
            console.log(val)
        })
    }

}