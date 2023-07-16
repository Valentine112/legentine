export class ResponseFormat {
    status?: number
    error?: number
    content?: string
    timeStamp?: Date

    deliver() {
        return this
    }
}