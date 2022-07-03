class Func {

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

    
}