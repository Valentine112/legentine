window.addEventListener("load", () => {

    var login_btn = document.getElementById("login-btn")
    login_btn.addEventListener("click", function() {

        var user = document.getElementById("user")
        var password = document.getElementById("password")
        var remember = document.getElementById("save-details-id")

        var func = new Func();
        if(func.stripSpace(user.value).length < 1) {
            user.focus()
        }
        else if(func.stripSpace(password.value).length < 1) {
            password.focus()
        }
        else{
            // Configure button to prevent multiple request
            //new Func().buttonConfig(this, "before")

            data = {
                part: 'login',
                action: "login",
                val: {
                    user: user.value,
                    password: password.value,
                    remember: remember.checked
                }
            }

            new Func().request("request.php", JSON.stringify(data), "json")
            .then(val => {
                console.log(val)
                // Configure button to prevent multiple request
                new Func().buttonConfig(this, "after")

                if(val.status === 1 && val.message == "double"){
                    if(val.content == "Success") {
                        // Take user to dashboard

                    }else if(val.content == "Auth") {
                        // Redirect to confirm page
                        
                    }
                }

                new Func().processResponse(val, "error", "error")

            })
        }
    })
})