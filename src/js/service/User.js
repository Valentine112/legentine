class User {

    constructor() {
        this.func = new Func()

        return this
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
            console.log(val)
            if(val.status === 1) {
                var content = val.content

                content.forEach(elem => {
                    document.getElementById("postCover").insertAdjacentHTML("afterbegin", pinBox(elem))
                })
            }
        })
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