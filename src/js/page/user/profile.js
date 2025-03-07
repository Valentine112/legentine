var person = ""
var user = ""

window.addEventListener("load", async function () {

    var pathObj = new Func().getPath()
    var path = pathObj['main_path']

    var param = pathObj['parameter']['token'] != null ? pathObj['parameter']['token'] : ""

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

            person = content[0]['person']['id']
            user = content[0]['self']['user']

            // Display the delete button from viewing photo
            // Depending on whose profile this is

            if(user !== person) {
                document.querySelector(".imageOptions").style.display = "none"
            }
        }
    })

    new Post().fetch_post(path, "notes", param)
})

document.body.addEventListener("click", async function(e) {
    var elem = e.target
    var action = ""
    let type = ""
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

            // Creating the effect from the client side to display speed
            // Instead of waiting while the request is been processed
            type = elem.getAttribute("data-type")
            if(type === "List") {
                elem.setAttribute("data-type", "Unlist")
                elem.classList.remove("List")
                elem.classList.add("Unlist")
                elem.innerText = "Unlist"
            }

            if(type === "Unlist") {
                elem.setAttribute("data-type", "List")
                elem.classList.remove("Unlist")
                elem.classList.add("List")
                elem.innerText = "List"
            }
    
            new Func().request("../request.php", JSON.stringify(data), 'json')
            .then(val => {

                if(val.status === 1) {
                    /*if(val.content === "Unlisted") {
                        elem.classList.remove("Unlist")
                        elem.classList.add("List")

                        elem.innerText = "List"
                    }

                    if(val.content === "Listed") {
                        elem.classList.remove("List")
                        elem.classList.add("Unlist")

                        elem.innerText = "Unlist"
                    }*/
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
            type = elem.getAttribute("data-type")

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
                    // No need for this, the effect has already been done from the client side
                }
            })

            break;

        case "profileSection":
            var uploadPhotoBox = document.querySelector(".uploadPhotoBox")
            var parent = elem.closest(".headerSectionSub")
            type = elem.getAttribute("data-type")

            // Show loader
            document.querySelector(".article-content .content-loader").style.display = "block"

            //Check that we are not on the same page
            if(parent.querySelector(".active span").innerText != elem.querySelector("span").innerText) {

                // Toggle the active link
                parent.querySelector(".active").classList.remove("active")
                elem.classList.add("active")

                var postCover = document.getElementById("postCover")
                var photoSub = document.getElementById("photoSub")

                postCover.innerHTML = ""
                photoSub.innerHTML = ""

                // Display the photo box
                if(person === user) {
                    if(type === "photos") {
                        uploadPhotoBox.style.display = "block"
                    }
                }

                if(type === "notes") {
                    photoSub.style.display = "none"
                    postCover.style.display = "flex"

                    new Post().fetch_post(pathObj['main_path'], "notes", param)

                    uploadPhotoBox.style.display = "none"
                }

                if(type === "photos") {
                    postCover.style.display = "none"
                    photoSub.style.display = "flex"
                    
                    new User().fetchPhotos(param)
                }
            }

            break;

        case "deleteImage":

            var elemToken = elem.getAttribute("data-token"),

            parent = document.querySelector("[data-image-token=LT-" + elemToken + "]")
    
            var data = {
                part: "user",
                action: "deleteImage",
                val: {
                    token: ""
                }
            }

            // Close the viewing picture page
            closePicture()
    
            var delete_notice = document.querySelector(".delete-notice")
    
            var promise = new Promise(res => {
                res(
                    // Call the delete timer here
                    delete_notice.setAttribute("data-delete-token", elemToken),
                    call_animation(parent.closest(".photoBoxCover"), data)
                )
            })
            await promise

            break;
            
        default:
            break;
    }

})

function closePicture() {
    document.querySelector(".viewImageBox").style.display = "none"
    document.querySelector(".viewImages").innerHTML = ""
    document.getElementById("deleteImage").removeAttribute("data-token")
}

function showUpload(type) {
    var uploadBox = document.querySelector(".upload")
    var file = uploadBox.querySelector("#file")
    var checkBox = uploadBox.querySelector("#checkbox")

    file.setAttribute("data-type", type)
    file.value = ""
    if(type === "profilePicture") {
        file.removeAttribute("multiple")
        checkBox.setAttribute("disabled", "disabled")

    }else if(type === "uploadPicture") {
        file.setAttribute("multiple", "multiple")
        checkBox.removeAttribute("disabled")
    }

    uploadBox.style.display = "block"
}

function closeUpload() {
    document.querySelector(".upload").style.display = "none"
    document.getElementById("imageDisplay").src = ""
    if(cropper != "") cropper.destroy()
    document.querySelector(".imagePreview").innerHTML = ""
}

function photoBox(data) {

    var result = data['content']
    var photo = ""
    var count = ""
    
    if(result['mode'] == 1) {
        var photos = result['photo'].split("%%")
        photo = photos[0]
        photos.length <= 1 ? count = "" : count = "<img src='../src/icon/profile/stack.svg'>"

    }else if(result['mode'] == 0) {
        photo = result['photo']
        count = ""
    }

    return `
        <div class="photoBoxCover" data-token="LT-${result['token']}">
            <div class="multiple">${count}</div>
            <img
                src=" "
                class="lazy-load-image entity-body"
                data-image="../src/${photo}"
                data-src="${result['photo']}"
                data-token="LT-${result['token']}"
                data-image-token="LT-${result['token']}"
                data-user="${result['user']}"
                data-self="${data['self']}"
                onclick="viewImage(this)"
            >
        </div>
    `
}

function viewImage(self) {

    function ImageBox(path, ind) {
        return `
            <div class="imageCover">
                <img src="../src/${path}" alt="" data-ind="${ind}">
            </div>
        `
    }

    //var scrollImageInd = document.querySelector(".scrollImagePage")
    var currentImageInd = document.getElementById("currentImage")
    //var totalImageInd = document.getElementById("totalImage")
    var viewImagesBox = document.querySelector(".viewImages")
    var deleteImage = document.getElementById("deleteImage")

    var token = self.getAttribute("data-token")
    var images = self.getAttribute("data-src")
    var imageSect = images.split("%%")
    //var imageLen = imageSect.length

    token = new Func().removeInitials(token)

    // Show the imageIndex if the total image is greater than 1 and vice versa
    // Not adding it again, not relevant
    // if(imageLen < 2) {
    //     scrollImageInd.style.display = "none"
    // }else{
    //     scrollImageInd.style.display = "block"
    // }

    var viewImageBox = document.getElementById("viewImageBox")
    // Display the box
    viewImageBox.style.display = "block"

    // Set the total
    // Not relevant, not adding it as well
    // totalImageInd.innerText = imageLen

    // Add the pictures
    imageSect.forEach((elem, ind) => {
        viewImagesBox.insertAdjacentHTML("beforeend", ImageBox(elem, (ind + 1)))
    })

    // Add token to the delete button
    deleteImage.setAttribute("data-token", token)


    let observer = new IntersectionObserver((entries, observer) => {
        entries.forEach((entry) =>{
            if(entry.isIntersecting){
                var tar = entry.target
                var targetImageInd = tar.querySelector("img").getAttribute("data-ind")
                //observer.unobserve(entry.target);

                currentImageInd.innerText = targetImageInd
            }
        })
    }, {rootMargin:"0px 0px 0px 0px", threshold:0.75});

    document.querySelectorAll(".imageCover").forEach(img => {
        observer.observe(img)
    })
}


