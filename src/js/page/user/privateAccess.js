var user = ""
var data = ""

window.addEventListener("load", async function () { 
    // Fetch user info
    var data = {
        part: "user",
        action: 'fetchUser',
        val: {
            user: "",
        }
    }

    new Func().request("../request.php", JSON.stringify(data), 'json')
    .then(async val => {
        if(val.status === 1) {
            data = val.content[0];

            var promise = new Promise(res => {
                res(
                    document.getElementById("privateAccess").innerHTML = privateBox(data)
                )
            })
            await promise
        }
    })

})


function privateBox(data) {

    var login = `
        <section class="login section">
            <div class="header">
                <h2>Access your private post</h2>
            </div>
            <div class="form">
                <div>
                    <input type="password" placeholder="..." autofocus id="pin" class="inputForm">
                </div>

                <div>
                    <button onclick="submitPrivate(this)" data-type="login">Submit</button>
                </div>
            </div>
            <div class="footer">
                <span onclick="switchForgot(this)">Forgot Pin</span>
            </div>
        </section>
    `

    var create = `
        <section class="create section">
            <span style="color: orange">Pin should be at least four numbers</span>
            <div class="header">
                <h2>Create pin for your private post</h2>
            </div>
            <div class="form">
                <div>
                    <input type="password" placeholder="Account password" autofocus id="password" class="inputForm">
                </div>

                <div>
                    <input type="number" placeholder="..." autofocus id="pin" class="inputForm">
                </div>

                <div>
                    <button onclick="submitPrivate(this)" data-type="create">Submit</button>
                </div>
            </div>
        </section>
    `

    var forgot = `
        <section class="forgot section">
            <span style="color: orange">Pin should be at least four numbers</span>
            <div class="header">
                <h2>Recover pin for private post</h2>
            </div>
            <div class="form">
                <div>
                    <input type="password" placeholder="Account password" autofocus id="password" class="inputForm">
                </div>

                <div>
                    <input type="number" placeholder="New Pin" id="pin" class="inputForm">
                </div>

                <div>
                    <button onclick="submitPrivate(this)" data-type="forgot">Submit</button>
                </div>
            </div>
            <div class="footer">
                <span onclick="switchLogin(this)">Login</span>
            </div>
        </section>
    `

    // Returning data on load, not manually
    if(typeof data == "object") {
        var person = data['person']

        if(new Func().stripSpace(person['privateCode']).length < 1) {
            return create
        }else{
            return login
        }
    }else{
        // Using the string to point to the variable with the eval function
        data = eval(data)
        
        return data
    }
}

// Switch the page to forgot from the login part
function switchForgot(self) {
    self.closest(".section").remove()

    document.getElementById("privateAccess").innerHTML = privateBox("forgot")

    document.querySelectorAll(".inputForm")[0].focus()
}

// Switch the page to login from the forgot section
function switchLogin(self) {
    self.closest(".section").remove()

    document.getElementById("privateAccess").innerHTML = privateBox("login")

    document.querySelectorAll(".inputForm")[0].focus()
}

function submitPrivate(self) {
    var parent = self.closest(".section")

    var type = self.getAttribute("data-type")
    var pin = parent.querySelector("#pin")

    if(new Func().stripSpace(pin.value).length >= 4) {

        // Fetch user info
        var data = {
            part: "personal",
            action: '',
            val: {
                pin: pin.value,
            }
        }

        if(type == "create") {
            data.action = "create"
            data.val['password'] = parent.querySelector("#password").value
        }

        if(type == "login") {
            data.action = "login"
        }

        if(type == "forgot") {
            data.action = "forgot"
            data.val['password'] = parent.querySelector("#password").value
        }

        new Func().buttonConfig(self, "before")
            
        new Func().request("../request.php", JSON.stringify(data), 'json')
        .then(val => {
            new Func().buttonConfig(self, "after")
            if(val.status === 1) {
                window.location = "privatePost"
            }
            new Func().notice_box(val)
        })
    }else{
        pin.focus()
    }
}