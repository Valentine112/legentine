window.addEventListener("load", function () {

    /** 
    * Attempt to signup
    * Check and validate users input
    */
    var signup_btn = document.getElementById("signup-btn"),
    error_message = document.querySelectorAll(".error-message")


    // Submitting the form, after all has been filled
    signup_btn.addEventListener("click", function () {

        // Initialise the error message to empty
        error_message.forEach(elem => {
            elem.querySelector("span").innerText = ""
        })

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

        switch (false) {
            case name_check:
                name.focus()

                name.parentElement.querySelector(".error-message span")
                .innerText = fullname_error
                break
            
            case username_check:
                username.focus()

                username.parentElement.querySelector(".error-message span").innerText = username_error
                break

            case email_check:
                email.focus()

                email.parentElement.querySelector(".error-message span").innerText = email_error
                break

            case password_check:
                password.focus()

                password.closest(".password-div").nextElementSibling.querySelector("span")
                .innerText = password_error
                break

            default:
                /**
                 * Default to show that all the conditions have been met
                 * Default signifies true
                 */

                var data = {
                    part: 'signup',
                    action: "verify",
                    val: {
                        fullname: name.value,
                        username: username.value,
                        email: email.value,
                        password: password.value 
                    }
                }

                data = JSON.stringify(data)

                // Configure button to prevent multiple request
                new Func().buttonConfig(this, "before")

                new Func().request('request.php', data, 'json')
                .then(val => {
                    // Configure button to prevent multiple request
                    new Func().buttonConfig(this, "after")
                    console.log(val)  

                    var response = val
                    if(response.status === 1) {
                        localStorage.setItem('LT-token', response.content['key'])
                        localStorage.setItem("LT-from", "signup")
                        
                        window.location = "confirm"

                    }
                    
                    new Func().processResponse(response, "error", "error")
                })

                break;
        }

    }, true)
})

// Toggling the password visibility

function togglePassword(self) {
    (new Func).togglePassword(self, self.closest(".password-div").querySelectorAll(".form"))
}