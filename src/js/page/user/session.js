function config_edit_post(title, content, privacy) {
    // Set the title
    document.getElementById("title").value = title
    // Set the content
    document.getElementById("form-content").innerHTML = content

    // Set the content for when i want to use this page to edit the private post
    if(privacy == 1) {
        document.querySelector(".session-privacy").remove()
    }

    // Set the options
}

window.addEventListener("load", function() {
    var func = new Func()
    // Modal to show the user any notice
    var notice_modal = document.querySelector(".quick-notice")
    var done = document.querySelector(".session-button button")

    // Check there is a parameter in the link
    // If there is, this would mean that the user wants to update a post

    var param = func.getPath()
    
    if(param['parameter'] != {}){
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

            // Fetch the post frist before editing
            func.request("../request.php", JSON.stringify(data), 'json')
            .then(async function(val) {
                console.log(val)

                if(val.status === 1){
                    var post = val.content[0]['post'],
                    title = post['title'],
                    content = post['content'],
                    category = post['category'],
                    privacy = post['privacy']
                    
                    var promise = new Promise(res => {
                        res(
                            config_edit_post(title, content, privacy)
                        )
                    })
                    await promise
                    document.querySelector(".content-loader").style.display = "none"
                }

                func.notice_box(val)
            })
        }
    }

    // When button is clicked, process and send the form
    done.addEventListener("click", async function() {
        var title = document.getElementById("title")
        var content = document.getElementById("form-content")
        var privacy = document.getElementById("check")
        var category = document.getElementById("category")

        var func = new Func

        // Check if the forms are empty
        var title_len = func.stripSpace(title.value).length
        var content_len = func.stripSpace(content.value).length

        var element_err = ""

        // This first condition was made to assign specific errors
        if(title_len < 1){
            title.focus()
            element_err = "title"

        }else if(content_len < 1){
            element_err = "content"
            content.focus()

        }

        // This second condition was made to assign general error
        if(title_len < 1 || content_len < 1){
            var error_message = `Your ${element_err} is missing`

            notice_modal.style.display = "block"
            var notice_message = notice_modal.querySelector(".notice-message span")

            // remove all classes before adding a new one
            // For this to work, make sure the element has no initial class which is used in some other place
            // But to be safe, make sure the element has no previous class
            var promise = new Promise(res => {
                res(
                    remove_class(notice_message),

                    notice_modal.querySelector(".warning").style.display = "block",
                    notice_message.classList.add("warning"),
                    notice_message.innerText = error_message
                )
            })
            await promise;

            // Hide the notice after 3s
            hide_noticeModal(notice_modal)
        }else{

            // Configure button to prevent multiple request
            //func.buttonConfig(this, "before")

            var data = {
                part: "post",
                action: 'create_post',
                val: {
                    title: title.value,
                    content: content.value,
                    category: category.value,
                    privacy: privacy.checked
                }
            }

            func.request("../request.php", JSON.stringify(data), 'json')
            .then(val => {
                // Configure button to prevent multiple request
                new Func().buttonConfig(this, "after")

                if(val.status === 1) window.location = "home"

                func.notice_box(val)
                //new Func().processResponse(val, "error", "error")

            })
        }

    })

}, true)

// Privacy effect
function privacy(self) {
    var check_box = document.getElementById("check")
    if(!check_box.checked) {
        self.style.borderLeft = "20px solid #000";
        self.innerText = "Private"
    }else{
        self.style.borderLeft = "20px solid dodgerblue";
        self.innerText = "Public"
    }
}

// Other category

function other_category(self) {
    var arr = ["Rap", "Song", "Poem", "Comedy", "Story"]
    if(!arr.includes(self.value))
        document.getElementById("others-category").style.display = "block"
        document.getElementById("form-category").focus()
}