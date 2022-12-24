var user = ""
var person = ""

window.addEventListener("load", async function () {

    var func = new Func()

    var pathObj = func.getPath()
    var path = pathObj['main_path']

    var param = pathObj['parameter']['token'] != null ? pathObj['parameter']['token'] : ""

    // Fetch user info
    var data = {
        part: "user",
        action: 'fetchUser',
        val: {
            user: param,
        }
    }

    func.request("../request.php", JSON.stringify(data), 'json')
    .then(val => {
        if(val.status === 1) {
            var content = val.content

            var userData = content[0]['person']
            user = content[0]['self']['user']

            person = userData['id']

            var quote = userData['quote']

            if(func.stripSpace(quote).length > 1) {
                document.getElementById("quote").value = quote
            }

            /**
             * Telling the user if they are eligible to change their username
             * This would be displayed on the client side and server side to save the user the hassle
             */

            var usernameInfo = document.getElementById("usernameInfo")

            // Multiplied the usernameChange time by 1000 to corrolate with the client time
            var usernameChange = userData['usernameChange'] * 1000
            // Set the time limit to 14 days
            var timeLimit = ((3600 * 24) * 14)

            var currentTime = new Date().getTime()

            // Converted to seconds since i would be working with seconds
            var timeDifference = ((currentTime - usernameChange) / 1000)

            if(timeDifference < timeLimit) {
                // Get the remaining time
                // Convert from milliseconds

                var remainingTime = timeDifference

                // Hard code getting hours, minutes and seconds
                let days = 3600 * 24
                let hr = 3600
                let minute = 60

                var daysLeft = remainingTime / days

                var daysLeft1 = Math.floor(daysLeft)

                // Uncomment the next comment if you want the hours and minute left
                /*
                var remainingDays = remainingTime % days

                var hrsLeft = remainingDays / hr
                var remainingHrs = remainingDays % hr

                var hrsLeft1 = Math.floor(hrsLeft)

                var minLeft = Math.floor(remainingHrs / minute)

                console.log(minLeft)
                */

                var format = 14 - daysLeft1

                usernameInfo.innerText = "You have " + format + "day(s) left until you can change your username again"

                func.buttonConfig(document.querySelector(".username button"), "before")
            }
        }
    })


})

function TogglePasswordVisibility(self) {
    var forms = self.closest(".form").querySelectorAll(".formPassword")

    new Func().togglePassword(self, forms)
}

function writingQuote(self) {
    var gauger = document.getElementById("innerTextGuage");
    var text_length = self.value.length;
    const value_to_add = 100/self.getAttribute("maxlength");
    gauger.style.width = `${text_length * value_to_add}%`
}

function saveSetup(self) {
    var func = new Func()
    var result

    var parent = self.closest(".mainSub")
    var parentType = parent.getAttribute("data-type")

    // Fetch user info
    var data = {
        part: "user",
        action: 'fetchUser',
        val: {}
    }

    func.buttonConfig(self, "before")

    switch (parentType) {
        case "quote":
            data.action = "changeQuote"
            data.val.content = document.getElementById("quote").value

            func.request("../request.php", JSON.stringify(data), 'json')
            .then(val => {
                func.buttonConfig(self, "after")

                func.notice_box(val)
                result = val
            })

            break;

        case "username":
            data.action = "changeUsername"
            data.val.username = document.getElementById("username").value
            data.val.password = parent.querySelector(".confirmPassword").value

            func.request("../request.php", JSON.stringify(data), 'json')
            .then(val => {
                func.buttonConfig(self, "after")

                func.notice_box(val)
                result = val
            })

            break;

        case "password":
            data.action = "changePassword"
            data.val.newPassword = document.getElementById("newPassword").value
            data.val.oldPassword = parent.querySelector(".confirmPassword").value

            func.request("../request.php", JSON.stringify(data), 'json')
            .then(val => {
                func.buttonConfig(self, "after")

                func.notice_box(val)
                result = val
            })

            break;
    
        default:
            break;
    }


}