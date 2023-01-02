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
        console.log(val)
        if(val.status === 1) {
            data = val.content[0];

            var promise = new Promise(res => {
                res(
                    document.getElementById("privateAccess").innerHTML = privateBox(data)
                )
            })
            await promise
            // Focus the password
            //document.getElementById("pin").focus()
        }
        // Check which section to show
    })

})


function privateBox(data) {

    var login = `
        <section class="login">
            <div class="header">
                <h2>Access your private post</h2>
            </div>
            <div class="form">
                <div>
                    <input type="password" placeholder="..." autofocus id="pin">
                </div>

                <div>
                    <button>Submit</button>
                </div>
            </div>
            <div class="footer">
                <span onclick="switchForgot(this)">Forgot Pin</span>
            </div>
        </section>
    `

    var create = `
        <section class="create">
            <div class="header">
                <h2>Create pin for your private post</h2>
            </div>
            <div class="form">
                <div>
                    <input type="number" placeholder="..." autofocus id="pin">
                </div>

                <div>
                    <button>Submit</button>
                </div>
            </div>
        </section>
    `

    var forgot = `
        <section class="forgot">
            <div class="header">
                <h2>Recover pin for private post</h2>
            </div>
            <div class="form">
                <div>
                    <input type="password" placeholder="Account password" autofocus id="password">
                </div>

                <div>
                    <input type="number" placeholder="New Pin" id="pin">
                </div>

                <div>
                    <button>Submit</button>
                </div>
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
    }
}