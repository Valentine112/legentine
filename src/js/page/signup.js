window.addEventListener("load", function () {

    /** 
    * Attempt to signup
    * Check and validate users input
    */
    var signup_btn = document.getElementById("signup-btn")

    signup_btn.addEventListener("click", function () {

        var name = document.getElementById("name")
        var username = document.getElementById("username")
        var email = document.getElementById("email")
        var password = document.getElementById("password")

        // Validate form
        var form = new Form,
        name_check = form.validate(name.value, 'name'),
        username_check = form.validate(username.value, 'username'),
        email_check = form.validate(email.value, 'email'),
        password_check = form.validate(password.value, 'password')
        // Validate end //

        switch (false) {
            case name_check:
                name.focus()
                break
            
            case username_check:
                username.focus()
                break

            case email_check:
                email.focus()
                break

            case password_check:
                password.focus()
                break

            default:
                var data = {
                    action: "signup",
                    fullname: name.value,
                    username: username.value,
                    email: email.value,
                    password: password.value
                }

                data = JSON.stringify(data)

                new Func().request('request.php', data, 'json')
                .then(data => {
                    console.log(data)
                })

                break;
        }


        // Disable button to prevent user from sending multiple request
        //this.setAttribute("disabled", "disabled")
        //this.style.opacity = "0.2"
        // Disable end //

    }, true)
})