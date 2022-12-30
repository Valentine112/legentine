window.addEventListener("load", function() {
    var path = new Func().getPath()['main_path']

    new User().fetchUnlisted()

})

function unlistedBox(data) {
    var person = data['person']
    return `
        <div class="unlistedCover post-body"
            data-user="LT-${person['id']}"
        >
            <div class="photo">
                <img src="../src/${person['photo']}" alt="">
            </div>

            <div class="name">
                <div><span>${person['fullname']}</span></div>
                <div><span>${person['username']}</span></div>
            </div>

            <div class="list">
                <span data-action="unlist_user">List</span>
            </div>
        </div>
    `
}