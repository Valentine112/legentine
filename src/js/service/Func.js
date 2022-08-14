class Func {

    constructor() {
        return this
    }

    stripSpace(data) {
        let a = data.replace(/^\s*/, "").replace(/\s*/, "");
        return a;
    }

    isEmpty(data) {
        let result
        if(typeof data === 'object'){
            result = Object.keys(data).length === 0
        }

        return result
    }

    newlineBr(data) {
        var reg = new RegExp('\r\n|\n|\r', 'gim');
        data = data.replace(reg, "<br />");

        return data
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

        var last_slash = full_path.lastIndexOf("/")
        var params = full_path.lastIndexOf("?")

        var page_path
        var parameter = {}

        // Check if parameters are not attached to the url
        // The plus 1 added is to remove the slash behind it
        if(params == -1){
            page_path = full_path.slice(last_slash + 1, full_path.length)
        }else{
            page_path = full_path.slice(last_slash + 1, params)

            /**
             * To get the parameters and create a key-value object from them
             * I got the value that existed after the question mark in the url
             * Then split it by the ampersnad operator first
             * Looped through each of the resulting value next
             * split the value by the equal to and made the 0 index as the key and the 1 as the value
             */
            var param = full_path.slice(params + 1, full_path.length)
            param = param.split("&")

            param.map(val => {
                var a = val.split("=")
                parameter[a[0]] = a[1]
            })
        }

        return {
            "full_path": full_path,
            "main_path": page_path,
            "parameter": parameter
        }
    }

    notice_box(data) {
        var type
        data.message = data.message.toLowerCase()
        var notice_modal = document.querySelector(".quick-notice")
        var error_text = notice_modal.querySelector(".error-text") 

        if(data.status === 0){
            if(data.type != null) type = data.type

            if(data.message == "fill"){
                error_text.innerHTML = data.content
            }
            if(data.message == "void"){
                error_text.innerHTML = "Something went wrong. . ."
            }

            remove_previous(error_text)


            // Show the notice
            notice_modal.querySelector("." + type + "").style.display = "block"
            error_text.classList.add(type)

            notice_modal.style.display = "block"
        }

        if(data.status === 1) {
            if(data.type != null) type = data.type

            if(data.message == "fill"){
                error_text.innerHTML = data.content

                remove_previous(error_text)

                // Show the notice
                notice_modal.querySelector("." + type + "").style.display = "block"
                error_text.classList.add(type)

                notice_modal.style.display = "block"
            }
        }

        setTimeout(() => {
            remove_previous(error_text)
            
            notice_modal.style.display = "none"
        }, 4000)
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

    intersect_show_image(data_attr, assignTo, thres, elem) {
        if('IntersectionObserver' in window) {
            let observer = new IntersectionObserver((entries, observer) => {
                entries.forEach((entry) =>{
                    if(entry.isIntersecting){
                        var res = entry.target.getAttribute(data_attr)
                        entry.target.setAttribute(assignTo, res);
                        observer.unobserve(entry.target);
                    }
                })
            }, {rootMargin:"0px 0px 0px 0px", threshold:thres});

            if(document.querySelector(elem) != null){
                document.querySelectorAll(elem).forEach(img => {
                    observer.observe(img)
                })
            }

        }else{
            document.querySelectorAll(elem).forEach((img) => {
                img.src = img.getAttribute(data_attr)
            })
        }
    }

    detectTransitionEnd(elem){
        var transitionEvents = ['transitionend', 'OTransitionEnd', 'webkitTransitionEnd'];

        transitionEvents.forEach(trans => {
            elem.addEventListener(trans, function() {
                return true
            })
        })
    }

    removeInitials(data) {
        data = data.replace("LT-", "")
        return data
    }

    dateFormatting(data) {
        var options = {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric',
        }

        return new Date(data).toLocaleDateString('en-us', options)

    }

    timeFormatting(date) {
        var result

        var time = new Date(date).getTime()
        var current_time = new Date().getTime()

        current_time = Number(current_time)

        var hour = 60 * 60,
        day = hour * 24,
        week = day * 7

        var diff = current_time - time;

        // Divided by 1000 to convert it back to seconds rather than milliseconds
        diff = Math.round(diff/1000)

        if(diff < 60) {
            result = "Just now";
        }
        else if(diff >= 60 && diff < 120){
            result = "a minute ago";
        }
        else if(diff >= 120 && diff < hour){
            result = Math.floor(diff / 60)+" minutes ago";
        }
        else if(diff >= hour && diff < (hour * 2)){
            result = "an hour ago";
        }
        else if(diff >= (hour * 2) && diff < day){
            result = Math.floor(diff / hour)+" hours ago";
        }
        else if(diff >= day && diff < (day * 2)){
            result = "a day ago";
        }
        else if(diff >= (day * 2) && diff < (day * 28)){
            result = Math.floor(diff / day)+ " days ago";
        }
        else if(diff >= week && diff < (week * 2)){
            result = "a week back";
        }
        else if(diff >= (week * 2) && diff <= (week * 4)){
            result = Math.floor(diff / week)+ " weeks back";
        }
        else{
            result = this.dateFormatting(date);
        }

        return result
    }

    fetch_mentions(data) {
        var mentions = []
        // Split the string first by spaces
        var data_splitted = data.split(" ")
        // Loop throught the splitted string
        data_splitted.forEach(val => {
            // Check if "@" is the first letter of each word
            if(val.indexOf("@") == 0){
                // Check if there is a string after the @
                if(this.stripSpace(val).length > 1) {
                    let mention = val.replace("@", "")

                    mentions.push(mention)
                }
            }
        })

        return mentions
    }

    domParser(str, type) {
        var parser = new DOMParser()
        var format = parser.parseFromString(str, type)
        return format.body.innerHTML
    }

}