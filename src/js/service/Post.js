class Post {
    
    constructor() {
        this.func = new Func()

        return this
    }

    toggle_options(self){
        console.log(self)
    }

    fetch_post(from, filter) {
        var result = ""

        var data = {
            part: "post",
            action: "fetch_post",
            val: {
                from: from,
                filter: filter
            }
        }

        this.func.request("../request.php", JSON.stringify(data), 'json')
        .then(val => {
            console.log(val)
            // Configure button to prevent multiple request

            if(val.status === 1){
                var content = val.content
                content.forEach(elem => {
                    var post = new PostHTML(elem, from, "../")

                    document.querySelector(".article-content").insertAdjacentHTML("beforeend", post.main())
                })
            }

            //this.func.notice_box(val)
        })

    }
}