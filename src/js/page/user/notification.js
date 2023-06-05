
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
        if(val.status == 1) {
            var content = val.content

            content.forEach(elem => {
                var notification = new Notification(elem)
                document.getElementById("comments-notification").insertAdjacentHTML("beforeend", notification.main())
            })
        }

        new Func().notice_box(val)
    })

    // Fetch tops notification
    var postNotification = document.querySelector(".post-notification")
    var topsHolder = document.querySelector(".post-notification-cover")
    var data = {
        part: "notification",
        action: 'tops',
        val: {}
    }

    func.request("../request.php", JSON.stringify(data), 'json')
    .then(val => {
        var content = val.content
        console.log(content)
        // Empty the tops holder before adding any element
        // This would make sure that no content is repeated
        topsHolder.innerHTML = ""

        content.forEach(elem => {
            // Proceed to add the contents
            topsHolder.insertAdjacentHTML('beforeend', topsBox(elem))
            // Display the notification section
            postNotification.style.display = "block"
        })
        
    })

    /**
     * Wouldn't create a seen for top notifications
     * It would just be defualt unseen
     * Because a post can enter and leave the top spots periodically.
     * So if it enters and it's seen, then it's marked, but when it leaves and enters,
     * but the user hasn't seen it, it would still be marked as seen from the previous record
     */

    // Set the status of all the users notification to be 1, which is seen
    new Notifications().seenNotification()
})

function topsBox(data) {

    var post = data['post']
    var timeFrame = ""
    var status = ""

    // Checking the timeFrame
    if(data['type'] == "Weekly"){
        timeFrame = "this week"
    }
    else if(data['type'] == "General"){
        timeFrame = "Generally"
    }

    // Check if it has been seen
    if(post['status'] == 1) status = "seen"

    return `
        <div
        class="${status}"
        data-token=LT-${post['token']}
        >
            <span class="title">"</b>${post['title']}</b>"</span> is among the top <span class="${post['category'].toLowerCase()} category">${post['category']}</span> <span class="section">${timeFrame}</span> under <b><span>${data['section']}</span></b>
        </div>
    `
}