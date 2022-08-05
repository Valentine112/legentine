window.addEventListener("load", function() {
    var post_photo = this.document.querySelector(".photo img")
    var post_body = this.document.querySelector(".post-body")
    var username = this.document.querySelector(".post-username")
    var title = this.document.querySelector(".post-title")
    var content = this.document.querySelector(".post-content span")
    var reaction_box = this.document.querySelector(".reaction-box")
    var reaction_count = this.document.querySelector(".reaction-count span")
    var category = this.document.querySelector(".category span")
    var reader = this.document.querySelector(".reader span")
    var date = this.document.querySelector(".date span")

    var func = new Func()

    // Check there is a parameter in the link
    // If there is, this would mean that the user wants to update a post
    var param = func.getPath()

    
    if(!func.isEmpty(param['parameter'])) {
        if(param['parameter']['token'] != null) {
            // Fetch post content

            var data = {
                part: "post",
                action: "fetch_post",
                val: {
                    from: param['main_path'],
                    filter: param['parameter'],
                }
            }

            // Fetch the post first, then comments next
            func.request("../request.php", JSON.stringify(data), 'json')
            .then(async function(val) {
                console.log(val)

                if(val.status === 1) {
                    var other = val.content[0]['other']
                    var post = val.content[0]['post']
                    var more =  val.content[0]['more']
                    var comment = val.content[0]['comments']

                    // The post token
                    post_body.setAttribute("data-token", "LT-" + post['token'])

                    post_photo.setAttribute("src", '../' + other['photo'])
                    username.innerText = other['username']
                    title.innerText = post['title']
                    content.innerText = post['content']

                    // Set react button
                    reaction_box.innerHTML = config_react(more)
                    reaction_count.innerText = post['stars'] == 0 ? "" : post['stars']

                    // Category
                    category.innerText = post['category']
                    // Reader
                    reader.innerText = post['readers']
                    // Date
                    date.innerText = func.dateFormatting(post['date'])

                    console.log(comment)

                    // Comments
                    comment.forEach(elem => {
                        comment_box = new Comment(elem)
                        document.querySelector(".comment-content").insertAdjacentHTML("afterbegin", comment_box.main())
                    })

                }

                func.notice_box(val)
            })

        }
    }
})

function config_react(data) {
    if(data['starred']){
        return `
            <img src="../src/icon/post-icon/star.svg" alt="" class="reaction star active" data-action="react">

            <img src="../src/icon/post-icon/unstar.svg" alt="" class="reaction unstar" data-action="react">
        `
    }else{
        return `
            <img src="../src/icon/post-icon/unstar.svg" alt="" class="reaction unstar active" data-action="react">

            <img src="../src/icon/post-icon/star.svg" alt="" class="reaction star" data-action="react">
        `
    }
}