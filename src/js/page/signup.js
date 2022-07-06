window.addEventListener("load", function () {

    /** 
    * Attempt to signup
    * Check and validate users input
    */
    var signup_btn = document.getElementById("signup-btn"),
    error_message = document.querySelectorAll(".error-message"),

    // Saving the error message in variables
    fullname_error = "Fullname should only have letters and space",
    username_error = "Username only accepts letters,numbers and underscore",
    email_error = "Email address should be in format john****@****.com",
    password_error = "Password should contain both letters and numbers and should be greater than 7"


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

                // Disable button to prevent user from sending multiple request
                this.setAttribute("disabled", "disabled")
                this.style.opacity = "0.2"

                var data = {
                    part: 'signup',
                    action: "verify",
                    fullname: name.value,
                    username: username.value,
                    email: email.value,
                    password: password.value
                }

                data = JSON.stringify(data)

                new Func().request('request.php', data, 'json')
                .then(val => {

                    var response = val
                    if(response.status === 1) {
                        localStorage.setItem('LT-token', response.content['key'])
                        window.location = "confirm"

                    }else if(response.status === 0 && response.message === "fill"){
                        document.querySelector(".server-error").innerText = response.content
                    }else{
                        document.querySelector(".server-error").innerText = "Something went wrong. . ."
                    }

                    // Enable button after a response has being received
                    this.removeAttribute("disabled")
                    this.style.opacity = "1"
                })

                break;
        }

    }, true)
})

// Toggling the password visibility

function togglePassword(self) {
    (new Func).togglePassword(self, self.closest(".password-div").querySelector(".form"))
}