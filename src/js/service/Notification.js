class Notifications {

    constructor() {
        this.func = new Func()

        this.path = this.func.getPath()['main_path']
        return this
    }

    seenNotification() {
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

        this.func.request("../request.php", JSON.stringify(data), 'json')
    }

    viewNotification(elem) {
        var parent = elem.closest(".entity-body").getAttribute("data-token")
        var elemToken = this.func.removeInitials(parent)

        var arr = {
            "type": "notification",
            "token": elemToken
        }

        var box = [arr]

        var data = {
            part: "notification",
            action: 'view',
            val: {
                content: box,
                filter: true
            }
        }

        this.func.request("../request.php", JSON.stringify(data), 'json')
    }

}