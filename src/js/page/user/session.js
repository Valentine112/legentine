function option(value) {
    return `
        <option value="${value}" id="other-value" selected>${value}</option>
    `
}

function config_session_page(title, content, privacy, category) {
    // Set the title
    document.getElementById("title").value = title
    // Set the content
    document.getElementById("form-content").innerHTML = content

    // Set the content for when i want to use this page to edit the private post
    if(privacy == 1) {
        document.querySelector(".session-privacy").remove()
    }

    // Set the options

    if(document.querySelector("[value=" + category + "]") != null){
        document.querySelector("[value=" + category + "]").setAttribute("selected", "selected")
    }else{
        document.getElementById("category").insertAdjacentHTML("afterbegin", option(category))
    }
}

var token = ""

window.addEventListener("load", function() {
    var func = new Func()

    var done = document.querySelector(".session-button button")

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

            // Fetch the post frist before editing
            func.request("../request.php", JSON.stringify(data), 'json')
            .then(async function(val) {

                if(val.status === 1){
                    var post = val.content[0]['post'],
                    title = post['title'],
                    content = post['content'],
                    category = post['category'],
                    privacy = post['privacy']
                    token = post['token']
                    
                    var promise = new Promise(res => {
                        res(
                            config_session_page(title, content, privacy, category)
                        )
                    })
                    await promise
                    document.querySelector(".content-loader").style.display = "none"
                }

                func.notice_box(val)


                done.addEventListener("click", update_post)
            })
        }
    }else{
        // Hide the loader immediately
        document.querySelector(".content-loader").style.display = "none"

        // Set the token to empty
        token = ""
        
        // Create an event list to create a post
        done.addEventListener("click", create_post)
    }

})

function update_post() {
    post_action("update_post", privacy, token)
}

function create_post() {
    post_action("create_post", "", "")
}

// When button is clicked, process and send the form
async function post_action(type, post_privacy, token) {
    // Done button
    var done = document.querySelector(".session-button button")

    // Modal to show the user any notice
    var notice_modal = document.querySelector(".quick-notice")

    var title = document.getElementById("title")
    var content = document.getElementById("form-content")
    var category = document.getElementById("category")

    // Checking if its a private post
    if(post_privacy === 1){
        privacy = 1
    }else{
        privacy = document.getElementById("check").checked
    }

    var func = new Func

    // Check if the forms are empty
    var title_len = func.stripSpace(title.value).length
    var content_len = func.stripSpace(content.value).length

    var element_err = ""

    // This first condition was made to assign specific errors
    if(title_len < 1){
        title.focus()
        element_err = "Your title is missing"

    }else if(content_len < 200){
        element_err = "content should be at least 200 characters"
        content.focus()

    }

    // This second condition was made to assign general error
    if(title_len < 1 || content_len < 1){
        var error_message = element_err

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

        func.buttonConfig(done, "before")

        // Configure button to prevent multiple request
        //func.buttonConfig(this, "before")

        var data = {
            part: "post",
            action: type,
            val: {
                token: token,
                title: title.value,
                content: content.value,
                category: category.value,
                privacy: privacy
            }
        }

        func.request("../request.php", JSON.stringify(data), 'json')
        .then(val => {
            // Configure button to prevent multiple request
            func.buttonConfig(done, "after")

            if(val.status === 1) window.location = "home"

            func.notice_box(val)

        })
    }

}

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