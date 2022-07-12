window.addEventListener("load", function() {

    // Confirmation action
    var confirm = document.querySelector(".confirm-btn button")

    confirm.addEventListener("click", function () {
        // valid variable to determine if the proper conditions were met
        var valid = false

        var data = []
        var forms = document.querySelectorAll(".form")

        // Check wether all the forms are filled
        for (const elem in forms) {
            if (Object.hasOwnProperty.call(forms, elem)) {
                let element = forms[elem];

                // Storing each value from each form
                data.push(element.value)

                if(element.value.length < 1) {
                    element.focus()

                    valid = false
                    break
                }else{
                    valid = true
                }
            }
        }

        // Get the token from localstorage and send back to the server
        // Check if it exist and verify users input
        var token = localStorage.getItem('LT-token')
        var from_page = localStorage.getItem('LT-from')
        if(valid) {
            // Configure button to prevent multiple request
            new Func().buttonConfig(this, "before")

            data = data.join("")

            data = {
                action: "confirm",
                val: {
                    code: data,
                    token: token != null ? token : ""
                }
            }

            // Sending data depending on the page the user is coming from
            if(from_page == "signup") {
                data.part = "signup"
            }

            if(from_page == "login") {
                data.part = "login"
            }

            if(from_page == "forgot") {
                data.action = "forgot-checkCode"
                data.part = "login"
            }


            new Func().request("request.php", JSON.stringify(data), 'json')
            .then(val => {

                // Configure button to prevent multiple request
                new Func().buttonConfig(this, "after")

                var response = val
                if(response.status === 1) {
                    
                    // Set the username in localstorage for the welcome page
                    localStorage.setItem("LT-username", response.content)
                    
                    // Check where the user is coming from and give the appropriate response

                    if(from_page == "signup"){
                        window.location = "welcome"

                        localStorage.removeItem("LT-token")

                    }else if(from_page == "login"){
                        localStorage.removeItem("LT-token")
                        
                        // Add the new device
                        var data = {
                            part: "login",
                            action: "addDevice",
                            val: val.content
                        }

                        new Func().request("request.php", JSON.stringify(data), 'json')
                        .then(val1 => {
                            if(val1.status === 1) {
                                // Clear the localstorage
                                new Func().clear_localstorage()

                                // redirect to dashboard
                                window.location = "user/home"
                            }
                            new Func().processResponse(response, "error", "error")
                        })

                    }
                    else if(from_page == "forgot"){
                        // Redirect to change password page
                        window.location = "password"
                    }

                }

                document.querySelector(".form-holder").reset()
                new Func().processResponse(response, "error", "error")
            })
        }

    })

    // Resend action
    var resend = document.querySelector(".resend-btn button")
    resend.addEventListener("click", function() {
        var from_page = localStorage.getItem('LT-from')

        /**
         * Only the token would be sent to the server along with the action
         * If the token does not correspond or exist as a key in the session.json file
         * Then user is not supposed to be on this page
         * As he/she didn't pass through the signup page or didn't signup properly
         * So, if it doesn't correspond, redirect or specify that something went wrong and redirect
         */
        // Configure button to prevent multiple request
        new Func().buttonConfig(this, "before")

        var token = localStorage.getItem('LT-token')
        data = {
            action: "resend",
            val: {
                token: token != null ? token : ""
            }
        }

        // Set the part for the request file depending on the users destination
        if(from_page == "signup") {
            data.part = "signup"
        }

        if(from_page == "login") {
            data.part = "login"
        }

        if(from_page == "forgot") {
            data.part = "login"
        }

        new Func().request("request.php", JSON.stringify(data), 'json')
        .then(val => {
            // Configure button to prevent multiple request
            new Func().buttonConfig(this, "after")

            new Func().processResponse(val, "error", "error")

        })
    })

    // Make the focus automatically move to the next empty form
    var forms = document.querySelectorAll(".form")

    for (let i = 0; i < forms.length; i++) {
        let element = forms[i];
        
        // Listened for the keyup event here
        element.addEventListener("keyup", function() {

            //Checked if the current element value on focus is filled
            if(element.value.length >= 1) {
                
                /**
                 *  If it is, i looped through all the forms again to find an empty form
                 *  And focus on that one if not filled
                 *  If you noticed, i had to refetch the forms again for this
                 *  If i used the one fetched outside of the keyup function
                 *  Its value wouldn't be updated
                 *  And that would obviously cause inaccuracy
                */
                for (const elem in document.querySelectorAll(".form")) {
                    if (Object.hasOwnProperty.call(forms, elem)) {
                        let elemI = forms[elem]

                        if(elemI.value.length < 1) {
                            elemI.focus()

                            break
                        }
                    }
                }
            }
        })
    }
})