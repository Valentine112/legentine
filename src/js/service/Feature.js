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
            if(val.status === 1) {
                if(val.content === "feature") {
                    // Setting the modifications if the request was sent through a button

                    if(elemType == "button"){
                        elem.innerText = "Cancel feature"
                        elem.style.cssText = "background-color: #ff465b; color: #fff";
                    }
                }

                if(val.content === "unfeature") {
                    // Setting the modifications if the request was sent through a button

                    if(elemType == "button"){
                        elem.innerText = "Feature"
                        elem.style.cssText = "background-color: #fff; color: #000";
                    }
                }
            }

            this.func.notice_box(val)
        })
    }

}