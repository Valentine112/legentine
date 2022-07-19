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
        data.message = data.message.toLowerCase()
        if(data.status === 1){
            this.showServerMessage("", "success")
        }

        if(data.status === 0 && data.message == "fill"){
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

    clear_localstorage() {
        localStorage.removeItem("LT-username")
        localStorage.removeItem("LT-from")
        localStorage.removeItem("LT-token")
    }

    getPath() {
        var full_path = window.location.href;
        var page_path = full_path.slice(40, full_path.length)

        return {
            "full_path": full_path,
            "main_path": page_path
        }
    }

    notice_box(data) {
        var type
        data.message = data.message.toLowerCase()
        var notice_modal = document.querySelector(".quick-notice")

        if(data.status === 0 && data.message == "fill"){
            if(data.type != null) type = data.type
            // Show the notice
            notice_modal.querySelector("." + type + "").style.display = "block"
            var error_text = notice_modal.querySelector(".error-text") 

            remove_class(error_text)

            error_text.classList.add(type)
            error_text.innerHTML = data.content

            notice_modal.style.display = "block"
        }
    }

    approximate_count(data) {
        var result = "";
        if(data < 1000){
            result = data;
        }
        else if(data >= 1000 && data < 1000000){
            result = (data/1000).toFixed(1) + "K"
        }
        else if(data >= 1000000 && data < 1000000000){
            result = (data/1000000).toFixed(1) + "M";
        }else if(data >= 1000000000 && data < 1000000000000){
            result = Math.floor(data/1000000000).toFixed(1) + "B";
        }else{
            result = "over T";
        }

        return result;
    }
}