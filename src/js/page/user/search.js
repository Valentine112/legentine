var results = []
var searchResult = document.querySelector(".search-result")
window.addEventListener("load", function() {

    var func = new Func()

    // Check there is a parameter in the link
    // If there is, this would mean that the user wants to update a post
    var param = func.getPath()

    // Check if there is a parameter in the url
    // Then check if the keyword exist
    if(!func.isEmpty(param['parameter'])) {
        if(param['parameter']['keyword'] != null) {

            var keyword = param['parameter']

            var data = {
                part: "user",
                action: 'search',
                val: {
                    content: keyword['keyword'],
                    limit: 20
                }
            }

            var searchResult = document.querySelector(".search-result")

            new Func().request("../request.php", JSON.stringify(data), 'json')
            .then(val => {

                results = val
                if(val.status === 1) {
                    searchResult.innerHTML = ""

                    var content = val.content
                    var search = new SearchBox(content)

                    content.forEach((val, ind) => {
                        if(val['type'] === "people") {
                            searchResult.insertAdjacentHTML('beforeend', search.people(ind))
                        }

                        if(val['type'] === "post") {
                            searchResult.insertAdjacentHTML('beforeend', search.post(ind))
                        }
                    })
                }
            })
        }
    }

    // Filtering the search based on types
    var filter = document.querySelectorAll(".filter")
    filter.forEach(elem => {
        elem.addEventListener("click", () => {
            var type = elem.getAttribute("data-type")
            // Remove the active class and assign to the clicked one
            this.document.querySelector(".active").classList.remove("active")

            elem.classList.add("active")
            console.log(searchResult)

            searchResult.innerHTML = ""

            console.log(searchResult)

            if(results.status === 1) {
                content = results.content
                var search = new SearchBox(content)

                // Display just people
                if(type === "people"){
                    content.forEach((elem, ind) => {
                        if(elem['type'] === "people") {
                            searchResult.insertAdjacentHTML('beforeend', search.people(ind))
                        }
                    })
                }

                // Display just post
                else if(type === "post"){
                    content.forEach((elem, ind) => {
                        if(elem['type'] === "post") {
                            searchResult.insertAdjacentHTML('beforeend', search.post(ind))
                        }
                    })
                }

                // Display everything
                else{
                    content.forEach((elem, ind) => {
                        if(elem['type'] === "people") {
                            searchResult.insertAdjacentHTML('beforeend', search.people(ind))
                        }

                        if(elem['type'] === "post") {
                            searchResult.insertAdjacentHTML('beforeend', search.post(ind))
                        }
                    })
                }
            }
            
        })
    })
})

