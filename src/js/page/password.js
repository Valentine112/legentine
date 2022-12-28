window.addEventListener("load", function () {
    var error_box = document.querySelector(".error-message span")

    // Button to change the password
    var change_btn = document.getElementById("change-btn")
    change_btn.addEventListener("click", function() {
        var password = document.getElementById("password")

        // Call the form to check if the password is up to standard
        if(new Form().validate(password.value, 'password')) {

            // Configure button to prevent multiple request
            new Func().buttonConfig(this, "before")

            error_box.innerText = ""
            var token = localStorage.getItem('LT-token')

            // Sending the password and token to the server for processing
            var data = {
                part: 'login',
                action: 'new-password',
                val: {
                    token: token != null ? token : "",
                    password: password.value
                }
            }

            new Func().request("request.php", JSON.stringify(data), "json")
            .then(val => {
                // Configure button to prevent multiple request
                new Func().buttonConfig(this, "after")

                // Redirect user to login page if all is okay
                if(val.status === 1) window.location = "login"

                new Func().processResponse(val, "error", "error")
            })
        }
        else{
            // What happens if the value does not meet the password standard
            password.focus()
            error_box.innerText = password_error
        }
    })
})

// Toggle password visibility
function togglePassword(self) {
    new Func().togglePassword(self, document.querySelectorAll(".form"))
}