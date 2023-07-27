class Response {
    status
    type
    message
    content
    more

    deliver() { 
        return {
            "status": this.status,
            "type": this.type,
            "message": this.message,
            "content": this.content,
            "more": this.more,
            "date": new Date(),
            "time": new Date().getTime()
        };
    }
}

module.exports = Response