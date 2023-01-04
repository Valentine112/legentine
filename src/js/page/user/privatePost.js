
var user = ""
var person = ""

window.addEventListener("load", async function () {var func = new Func()

    var pathObj = func.getPath()
    var path = pathObj['main_path']

    var param = pathObj['parameter']['token'] != null ? pathObj['parameter']['token'] : ""

    // Fetch user info
    var data = {
        part: "personal",
        action: 'fetch',
        val: {
            user: param,
        }
    }

    func.request("../request.php", JSON.stringify(data), 'json')
    .then(val => {
        console.log(val)
        result  = val
                
        if(val.status === 1){
            var content = val.content
            var postCover = document.getElementById("postCover")
            
            content.forEach(elem => {
                var post = new PostHTML(elem, path, "../")
                postCover.insertAdjacentHTML("beforeend", post.main())
            })
        }

        new Func().notice_box(val)
    })
})