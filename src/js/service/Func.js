class Func {

    constructor() {
        return this
    }

    stripSpace(data) {
        let a = data.replace(/^\s*/, "").replace(/\s*/, "");
        return a;
    }

    async request(url, data, headerType) {
        var header = {
            'json': 'application/json',
            'standard': 'application/x-www-form-urlencoded'
        }

        var req = await fetch(url, {
            // Method for sending the data
            method: 'POST',
            // Prevent caching
            cach: 'no-cache',
            // No cors
            mod: 'cors',
            // Same origin
            credentials: 'same-origin',
            // Headers
            headers: {
                'Content-Type': header[headerType]
            },
            // Data to send to server
            body: data
        })
        
        return req.json()
    }

    togglePassword(action, form) {
        var elemType = form.getAttribute("type")
        if(elemType == "password") {
            form.type = "text"
            action.innerText = "hide"
        }
        else if(elemType == "text") {
            form.type = "password"
            action.innerText = "show"
        }
    }

    processResponse(data, type, type1) {
        if(data.status === 0 && data.message === "Fill"){
            // Message from server if it's filled
            this.showServerMessage(data.content, type)
        }else if(data.status != 1) {
            // Message from server if it's void
            this.showServerMessage("Something went wrong. . .", type1)
        }
    }

    showServerMessage(data, type) {
        var server_box = document.querySelector(".server-error span")
        server_box.classList = type
        server_box.innerText = data
    }

    buttonConfig(elem, time) {
        if(time === "before") {
            elem.setAttribute("disabled", "disabled")
            elem.style.opacity = "0.2"
        }

        if(time === "after") {
            elem.removeAttribute("disabled")
            elem.style.opacity = "1"
        }
    }
}