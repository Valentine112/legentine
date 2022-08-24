<head>
    <style>
        .delete-notice{
            position: fixed;
            bottom: 8%;
            left: 0;
            right: 0;
            width: 90%;
            margin: auto;
            background-color: snow;
            border-radius: 5px;
            z-index: 5;
            font-family: var(--theme-font);
            display: none;
        }

        .delete-notice .delete-animation{
            position: absolute;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
            width: 100%;
            background-color: #444;
            border-radius: 5px;
            z-index: -1;
            cursor: pointer;
        }
        .delete-animation.animate{
            transition: width 5s linear;
            -o-transition: width 5s linear;
            -webkit-transition: width 5s linear;
            -moz-transition: width 5s linear;
            width: 0%!important;
        }

        .delete-notice .delete-cover{
            padding: 10px;
            border-radius: 5px;
            text-align: center;
            border: 1px solid snow;
            border-radius: 5px;
            cursor: pointer;
        }

        .delete-notice > div div{
            display: inline-block;
            vertical-align: top;
        }

        .delete-notice > div div:first-child{
            width: 20%;
        }
        .delete-notice > div div:last-child{
            width: 75%;
        }
        .delete-notice .delete-cover span{
            color: #fff;
            mix-blend-mode: difference;
        }
        .delete-notice img{
            height: 20px;
            width: 20px;
            margin: auto;
            mix-blend-mode: difference;
        }
        @media screen and (min-width: 768px) {
            .delete-notice{
                bottom: 2%;
                width: 35%;
            }
        }
        @media screen and (min-width: 992px) {
            .delete-notice{
                bottom: 10%;
                width: 25%;
            }
        }
    </style>
</head>
<div class="delete-notice" onclick="stop_animation('restore')">
    <div class="delete-animation"></div>
    <div class="delete-cover">
        <div>
            <img src="../src/icon/delete-notice-icon/cancel-delete.svg" alt="">
        </div>
        <div>
            <span>
                Click to cancel this process
            </span>
        </div>
    </div>
</div>

<script>
    var delete_notice = document.querySelector(".delete-notice")

    function call_animation(element, data) {

        // Deleting a previous request directly
        var process = delete_notice.getAttribute("data-process")
        var notice_token = delete_notice.getAttribute("data-delete-token")

        if(process == 1 && notice_token != null) {
            // A previous request was sent and hasn't been completed
            // Process that request before sending a new one

            data['val']['token'] = new Func().removeInitials(notice_token)

            new Func().request("../request.php", JSON.stringify(data), 'json')
            .then(val => {

                new Func().notice_box(val)
            })
            
        }

        // Process the new request
        delete_notice.setAttribute("data-delete-token", element.getAttribute("data-token"))

        var delete_animation = document.querySelector(".delete-animation")
        element.style.display = "none"

        delete_animation.classList.remove("animate")
        // Check if there was a previous request and 
        // Setting the data process to 1, meaning the process is still on
        delete_notice.setAttribute("data-process", 1)

        // Display delete notice
        delete_notice.style.display = "block"

        setTimeout(() =>{
            // Start animation
            delete_animation.classList.add("animate")
        }, 0050)

        var transitionEvents = ['transitionend', 'OTransitionEnd', 'webkitTransitionEnd', 'mozTransitionend'];

        // Checking if the transition has ended
        transitionEvents.forEach(trans => {
            delete_animation.addEventListener(trans, function() {
                var data_process = delete_notice.getAttribute("data-process")

                // If the data_process is 1, proceed with it
                if(data_process == 1) {
                    var token = delete_notice.getAttribute("data-delete-token")
                    token = new Func().removeInitials(token)

                    // Send the data to the server for processing
                    data['val']['token'] = token

                    console.log(data)

                    // reset the animation after all the data has been gotten
                    stop_animation("")

                    new Func().request("../request.php", JSON.stringify(data), 'json')
                    .then(val => {
                        console.log(val)
                        if(val.status === 1){
                            element.remove()
                        }
                        new Func().notice_box(val)
                    })
                }
                
            })
        })

    }

    function stop_animation(type) {
        var token = delete_notice.getAttribute("data-delete-token")
        var delete_animation = document.querySelector(".delete-animation")

        if(type === "restore"){
            // Display the post back
            document.querySelector("[data-token=" + token + "]").style.display = "block"
        }

        // Setting the data process to 0, meaning the process has cancelled
        delete_notice.setAttribute("data-process", 0)

        // Remove the data token
        delete_notice.removeAttribute("data-delete-token")

        // Get the width
        var delete_width = getComputedStyle(delete_animation).getPropertyValue("width")

        // Start animation
        delete_animation.classList.remove("animate")
        delete_animation.style.width = "100%"

        // Hide the delete box
        delete_notice.style.display = "none"
    }
</script>