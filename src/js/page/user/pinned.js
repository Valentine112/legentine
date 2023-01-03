window.addEventListener("load", function() {
    // Fetch the pinned users

    new User().fetchPin()

})

function unpin(self, other) {
    var data = {
        part: "user",
        action: 'pin',
        val: {
            other: other,
        }
    }

    new Func().request("../request.php", JSON.stringify(data), 'json')
    .then(val => {
        if(val.status === 1){

            if(val.content === "unpin") {
                self.closest('.pinnedUsers').remove()
            }
        }
        new Func().notice_box(val)
    })
}

function pinBox(data) {
    var photo = data['user']['photo'],
    id = data['user']['id'],
    fullname = data['user']['fullname'],
    username = data['user']['username'],
    token = data['pinnedToken']

    return `
        <div class="pinnedUsers">
            <div>
                <a href="profile?token=${id}">
                    <img src="../src/${photo}" alt="">
                </a>

            </div>

            <div class="name">
                <a href="profile?token=${id}">
                    <div>${fullname}</div>
                    <span>${username}</span>
                </a>
            </div>

            <div>
                <span onclick="unpin(this, '${id}')">Unpin</span>
            </div>
        </div>
    `
}