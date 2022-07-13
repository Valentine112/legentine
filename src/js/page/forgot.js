window.addEventListener("load", function () {

    var recover = document.getElementById("recover-btn")

    recover.addEventListener("click", function() {

        var form = document.querySelector(".form")
        var func = new Func()

        if(func.stripSpace(form.value).length < 1) {
            form.focus()
        }else{
            // Disable button
            func.buttonConfig(this, "before")
            var data = {
                part: 'login',
                action: "forgot",
                val: {
                    user: form.value
                }
            }

            func.request("request.php", JSON.stringify(data), "json")
            .then(val => {
                // Enable button
                func.buttonConfig(this, "after")
                if(val.status === 1){
                    // Redirect to confirm page
                    // Set where the user is coming from
                    
                    localStorage.setItem("LT-token", val.content['key'])
                    localStorage.setItem("LT-from", "forgot")

                    window.location = "confirm"
                }

                func.processResponse(val, "error", "error")
                
            })
        }
    })
})