class Feature {

    constructor() {
        this.func = new Func()

        return this
    }

    request(elem) {
        var elemType = elem.tagName.toLowerCase()
        var post = ""
        //this.func.buttonConfig(elem, 'before')

        // METHOD 1
        // Fetching the post from the url
        var param = this.func.getPath()
        if(!this.func.isEmpty(param['parameter'])) {
            if(param['parameter']['token'] != null) {
                post = param['parameter']
            }
        }

        var data = {
            part: "feature",
            action: 'request',
            val: {
                post: post['token'],
            }
        }

        this.func.request("../request.php", JSON.stringify(data), 'json')
        .then(val => {
            console.log(val)

            this.func.notice_box(val)
        })
    }

}