window.addEventListener("load", function () {

    var recover = document.getElementById("recover-btn")

    recover.addEventListener("click", function() {

        var form = document.querySelector(".form")
        var func = new Func()

        if(func.stripSpace(form.value).length < 1) {
            form.focus()
        }else{
            // Disable button
            func.buttonConfig(this, "before")

        }
    })
})