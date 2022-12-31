var user = ""

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
    .then(val => {
        console.log(val)

        // Check which section to show
    })

})


function privateBox() {
    
}