window.addEventListener("load", function () {

    var pathObj = new Func().getPath()
    var path = pathObj['main_path']

    var param = pathObj['parameter']['token'] != null ? pathObj['parameter']['token'] : ""

    new Post().fetch_post(path, "notes", param)

    // Fetch user info
    var data = {
        part: "user",
        action: 'unlist',
        val: {
            user: user,
        }
    }
})