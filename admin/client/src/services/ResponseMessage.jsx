export class ResponseMessage {
    
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
            "time": new Date
        }
    }
}