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

            function check_visibility() {
                if(getComputedStyle(main_option).getPropertyValue("display") === "none") {
                    main_option.style.display = "block"
                }else{
                    main_option.style.display = "none"
                }
            }
            if(main_option != null) {
                check_visibility()
            }else{
                // For small screen options
                document.querySelector(".article-content").insertAdjacentHTML("beforebegin", new Options().main())
            }
            //if(elem.closest())
        }
    }
}