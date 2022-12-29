class Feature {

    constructor() {
        this.func = new Func()

        return this
    }

    fetchFeature() {
        var data = {
            part: "feature",
            action: 'fetchRequest',
            val: {
                type: 'request'
            }
        }


        this.func.request("../request.php", JSON.stringify(data), 'json')
        .then(val => {
            if(val.status === 1) {
                var content = val.content['content']

                content.forEach(elem => {
                    document.getElementById("featureBox").insertAdjacentHTML("beforeend", featureBox(elem))
                })
            }

            this.func.notice_box(val)
        })
    }

    fetchHistory() {
        var data = {
            part: "feature",
            action: 'fetchHistory',
            val: {}
        }


        this.func.request("../request.php", JSON.stringify(data), 'json')
        .then(val => {
            console.log(val)
            if(val.status === 1) {
                var content = val.content['content']

                content.forEach(elem => {
                    document.getElementById("featureBox").insertAdjacentHTML("beforeend", historyBox(elem, val.content['self']))
                })
            }

            this.func.notice_box(val)
        })
    }

    request(elem) {
        var elemType = elem.tagName.toLowerCase()
        var post = ""
        this.func.buttonConfig(elem, 'before')

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
            this.func.buttonConfig(elem, 'before')
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

    async confirmFeature(elem) {
        var path = new Func().getPath()['main_path']
        console.log(path)

        this.func.buttonConfig(elem, 'before')
        var parent = elem.closest(".featureCover")

        var type = elem.getAttribute("data-type"),
        token = this.func.removeInitials(parent.getAttribute("data-token"))

        var data = {
            part: "feature",
            action: 'confirmFeature',
            val: {
                type: type,
                token: token,
            }
        }

        if(path == "featureHistory"){
            data.val['feature'] = this.func.removeInitials(parent.getAttribute("data-feature"))
        }

        var delete_notice = document.querySelector(".delete-notice")

        var promise = new Promise(res => {
            res(
                delete_notice.setAttribute("data-delete-token", token),
                call_animation(parent, data)
            )
        })
        await promise

        /*

        this.func.request("../request.php", JSON.stringify(data), 'json')
        .then(val => {
            this.func.buttonConfig(elem, 'before')

            console.log(val)

            if(val.status === 1){
                if(path == "featureRequest"){
                    parent.remove()
                }
            }

            this.func.notice_box(val)
        })*/
    }

}