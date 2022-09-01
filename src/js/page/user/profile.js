window.addEventListener("load", function () {

    var pathObj = new Func().getPath()
    var path = pathObj['main_path']

    var param = pathObj['parameter']['token'] != null ? pathObj['parameter']['token'] : ""

    new Post().fetch_post(path, "notes", param)

    // Fetch user info
    var data = {
        part: "user",
        action: 'fetchUser',
        val: {
            user: param,
        }
    }

    new Func().request("../request.php", JSON.stringify(data), 'json')
    .then(val => {
        console.log(val)
        if(val.status === 1) {
            var content = val.content

            var profile = new Profile(content)

            content.forEach((elem, ind) => {
                document.getElementById("profileHTML").innerHTML = profile.main(ind)

                profile.rating(ind)
            })
        }
    })
})

document.body.addEventListener("click", function(e) {
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

    var pathObj = new Func().getPath()
    var param = pathObj['parameter']['token'] != null ? pathObj['parameter']['token'] : ""

    console.log(elem)

    switch (action) {
        case "profileList":

            var data = {
                part: "user",
                action: 'unlist',
                val: {
                    user: param,
                }
            }
    
            new Func().request("../request.php", JSON.stringify(data), 'json')
            .then(val => {
                console.log(val)

                if(val.status === 1) {
                    if(val.content === "Unlisted") {
                        elem.classList.remove("Unlist")
                        elem.classList.add("List")

                        elem.innerText = "List"
                    }

                    if(val.content === "Listed") {
                        elem.classList.remove("List")
                        elem.classList.add("Unlist")

                        elem.innerText = "Unlist"
                    }
                }

                new Func().notice_box(val)
            })

            break;
    
        default:
            break;
    }

})