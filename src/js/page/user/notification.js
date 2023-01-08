
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

        new Func().notice_box(val)
    })
})