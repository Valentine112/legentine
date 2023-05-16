
var user = ""
var person = ""

window.addEventListener("load", async function () {
    var func = new Func()

    var pathObj = func.getPath()
    var path = pathObj['main_path']

    var param = pathObj['parameter']['token'] != null ? pathObj['parameter']['token'] : ""

    // Fetch user info
    var data = {
        part: "notification",
        action: 'fetchNotification',
        val: {}
    }

    func.request("../request.php", JSON.stringify(data), 'json')
    .then(val => {
        console.log(val)
        if(val.status == 1) {
            var content = val.content

            content.forEach(elem => {
                var notification = new Notification(elem)
                document.getElementById("comments-notification").insertAdjacentHTML("beforeend", notification.main())
            })
        }

        new Func().notice_box(val)
    })

    // Set the status of all the users notification to be 1, which is seen
    var data = {
        part: "notification",
        action: 'seen',
        val: {
            content: "",
            filter: false,
            table: "notification"
        }
    }

    func.request("../request.php", JSON.stringify(data), 'json')
})