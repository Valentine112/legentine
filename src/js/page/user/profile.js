window.addEventListener("load", async function () {

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

    switch (action) {
        case "profileList":
            // Unlist and list user

            var data = {
                part: "user",
                action: 'unlist',
                val: {
                    user: param,
                }
            }
    
            new Func().request("../request.php", JSON.stringify(data), 'json')
            .then(val => {

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

        case "rateUser":

            var parent = elem.parentNode
            var child = Array.from(parent.children)

            var summedRating = parent.getAttribute("data-sum")
            var totalRating = parent.getAttribute("data-total")
            var rated = parent.getAttribute("data-rated")

            totalRating = Number(totalRating)

            var rate = child.indexOf(elem) + 1

            // Get the total number of ratings
            var newTotal = rated == "" ? totalRating + 1 : totalRating

            summedRating = Number(summedRating)

            // Get the sum of rating
            // If user has rated before, Subtract the previous rating from the summedrating
            summedRating = rated == "" ? summedRating : summedRating - Number(rated)

            var printRating = new Func().printRatings(rate)

            parent.innerHTML = ""

            new Profile("").displayRatingStars(printRating, parent)

            parent.setAttribute("data-rated", rate)
            parent.setAttribute("data-total", newTotal)

            var data = {
                part: "user",
                action: 'rateUser',
                val: {
                    other: param,
                    rating: rate
                }
            }


            new Func().request("../request.php", JSON.stringify(data), 'json')
            .then(val => {
                if(val.status === 1) {
                    parent.innerHTML = ""

                    var content = val.content

                    var serverSideCalc = parseFloat(content['calcRating'].toFixed(1))

                    document.getElementById("ratingNumber").innerText = serverSideCalc

                    var printRating = new Func().printRatings(content['rated'])

                    new Profile("").displayRatingStars(printRating, parent)

                    parent.setAttribute("data-sum", content['summedRating'])
                    parent.setAttribute("data-total", content['totalRating'])
                    parent.setAttribute("data-rated", content['rated'])

                }

                new Func().notice_box(val)
            })

            break;

        case "pin":
            var parent = elem.parentNode
            var type = elem.getAttribute("data-type")

            // First display the effect from the client side
            elem.classList.remove("active")

            if(type === "pinned") {
                parent.querySelector(".unpin").classList.add("active")

            }else if(type === "unpin") {
                parent.querySelector(".pinned").classList.add("active")

            }

            var data = {
                part: "user",
                action: 'pin',
                val: {
                    other: param,
                }
            }

            new Func().request("../request.php", JSON.stringify(data), 'json')
            .then(val => {
                if(val.status === 1){
                    var unpin = parent.querySelector(".unpin")
                    var pin = parent.querySelector(".pinned")

                    if(val.content === "pin") {
                        unpin.classList.remove("active")

                        pin.classList.add("active")
                    }

                    if(val.content === "unpin") {
                        pin.classList.remove("active")
                        
                        unpin.classList.add("active")
                    }
                }
            })

            break;
            
        default:
            break;
    }

})

// Rate user
function rateUser() {

}