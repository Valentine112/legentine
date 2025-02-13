
window.addEventListener("load", function() {
    var func = new Func()

    // Confirm that there is parameter in the link which holds the feature token

    var param = func.getPath()
    
    if(!func.isEmpty(param['parameter'])) {
        if(param['parameter']['token'] != null) {
            // Send the request to fetch the feature from the token

            var data = {
                part: "feature",
                action: "fetchFeature",
                val: {
                    from: param['main_path'],
                    token: param['parameter']['token']
                }
            }

            // Fetch the post frist before editing
            func.request("../request.php", JSON.stringify(data), 'json')
            .then(async function(val) {
                if(val.status == 1) {
                    let content = val.content
                    //Set the header
                    let res = new Promise(res => {
                        res(
                            document.getElementById("composingPost").innerHTML = content.post,
                            document.getElementById("form-content").value = content.feature['content']
                        )
                    })
                    await res
                    document.querySelector(".content-loader").style.display = "none"
                }
            })

            document.querySelector(".compose-button button").addEventListener("click", compose)
        }
    }

})

async function compose() {
    var func = new Func
    let token = func.getPath()['parameter']['token']
    let content = document.getElementById("form-content")
    // The user can send an empty request, rather than deleting
    // Configure button to prevent multiple request
    func.buttonConfig(this, "before")

    var data = {
        part: "feature",
        action: "compose",
        val: {
            content: content.value,
            feature: token
        }
    }

    func.request("../request.php", JSON.stringify(data), 'json')
    .then(val => {
        console.log(val)
        // Configure button to prevent multiple request
        func.buttonConfig(this, "after")

        if(val.status === 1) window.location = `read?token=${val.content.post}`

        func.notice_box(val)

    })
}