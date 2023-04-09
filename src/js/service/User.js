class User {

    constructor() {
        this.func = new Func()

        this.path = this.func.getPath()['main_path']
        return this
    }

    fetchUnlisted() {
        var data = {
            part: "user",
            action: 'fetchUnlisted',
            val: {}
        }



        this.func.request("../request.php", JSON.stringify(data), 'json')
        .then(val => {

            if(val.status === 1) {
                val.content.forEach(elem => {
                    document.querySelector(".unlistedBox").insertAdjacentHTML("beforeend", unlistedBox(elem))
                })
            }

        })
    }

    unlist_user(elem) {
        var parent = elem.closest(".post-body"),
        user = this.func.removeInitials(parent.getAttribute("data-user"))

        var data = {
            part: "user",
            action: 'unlist',
            val: {
                user: user,
            }
        }

        this.func.request("../request.php", JSON.stringify(data), 'json')
        .then(val => {
            if(val.status === 1) {
                if(val.content === "Unlisted") {
                    // Remove all post from the user
                    document.querySelectorAll("[data-user=LT-" + user + "]").forEach(elem => {
                        elem.remove()
                    })
                    
                }

                // Remove the blocked user
                if(val.content === "Listed" && this.path === "unlisted") {
                    document.querySelector("[data-user=LT-" + user + "]").remove()
                }
            }

            this.func.notice_box(val)
        })
    }

    fetchPhotos(user) {

        var data = {
            part: "user",
            action: 'fetchPhotos',
            val: {
                user: user,
            }
        }


        this.func.request("../request.php", JSON.stringify(data), 'json')
        .then(val => {
            if(val.status === 1) {
                var photoSub = document.getElementById("photoSub")

                var content = val.content
                content.forEach(elem => {
                    photoSub.insertAdjacentHTML("afterbegin", photoBox(elem))
                })
            }

            this.func.notice_box(val)
        })

    }

    fetchPin() {
        var data = {
            part: "user",
            action: 'fetchPin',
            val: {}
        }


        this.func.request("../request.php", JSON.stringify(data), 'json')
        .then(val => {
            if(val.status === 1) {
                var content = val.content

                content.forEach(elem => {
                    document.getElementById("postCover").insertAdjacentHTML("afterbegin", pinBox(elem))
                })
            }
        })
    }

    sendFeedback(elem, feedback) {
        if(this.func.stripSpace(feedback.value).length >= 20) {

            var data = {
                part: "user",
                action: 'sendFeedback',
                val: {
                    feedback: feedback.value
                }
            }
    
            this.func.request("../request.php", JSON.stringify(data), 'json')
            .then(val => {
                this.func.notice_box(val)
            })
        }else{
            feedback.focus()
        }
    }
}

function pinBox(data) {
    var photo = data['user']['photo'],
    fullname = data['user']['fullname'],
    username = data['user']['username'],
    token = data['pinnedToken']['token']

    return `
        <div class="pinnedUsers">
            <div>
                <a href="">
                    <img src="../src/${photo}" alt="">
                </a>

            </div>

            <div class="name">
                <a href="">
                    <div>${fullname}</div>
                    <span>${username}</span>
                </a>
            </div>

            <div>
                <span onclick="unpin(this, '${token}')">Unpin</span>
            </div>
        </div>
    `
}