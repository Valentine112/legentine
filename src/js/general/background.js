window.addEventListener("load", () => {

    var func = new Func()

    var pathObj = func.getPath()
    var path = pathObj['main_path']

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
            switch (path) {
                case "home":

                    action = "more_post"
                    // Get the current category
                    var filter = document.querySelector(".category").querySelector(".active")
                    filter = filter.getAttribute("value");

                    (filter == "all") ? more = "" : more = filter

                    break;

                case "profile":

                    break;
            
                default:
                    break;
            }

            var data = {
                part: "moreData",
                action: "more_post",
                val: {
                    filter: last_element_token,
                    more: more
                }
            }


            new Func().request("../request.php", JSON.stringify(data), 'json')
            .then(val => {

                if(val.status == 1) {
                    if(path == "home") {
                        var content = val.content
                        var postCover = document.getElementById("postCover")
                        
                        content.forEach(elem => {
                            var token = elem['post']['token'];

                            if(document.querySelector("[data-token=LT-" + token + "]") == null) {
                                var post = new PostHTML(elem, path, "../")
                                postCover.insertAdjacentHTML("beforeend", post.main())
                            }
                        })
                    }
                }
            })
        }
    })



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