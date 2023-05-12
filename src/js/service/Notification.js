class Notifications {

    constructor() {
        this.func = new Func()

        this.path = this.func.getPath()['main_path']
        return this
    }

    seenNotification(elem) {
        var data = {
            part: "notification",
            action: 'seen',
            val: {}
        }



        this.func.request("../request.php", JSON.stringify(data), 'json')
        .then(val => {

        })
    }

}