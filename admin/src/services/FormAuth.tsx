import { useState } from "react"
import { ResponseFormat } from "./ResponseFormat"

interface AuthData {
    value: string | number,
    auth: string
}

class FormAuth extends ResponseFormat {
    data: any
    required?: Array<any>|null

    constructor(data: object, required?: Array<any>|null) {
        super()

        // Required signifies the cases that are needed
        this.data = data
        this.required = required
    }

    async process() {
        this.status = 1;

        // Initialize the error to 0
        // Meaning that there is no error
        this.error = 0
        this.content = ""
        // This has to be done before processing the fields
        // So we put it in a promise

        let promise = new Promise(res => {
            if(Array.isArray(this.required))

                // Sort the list
                this.required.forEach(element => {

                    if(!this.data[element as keyof typeof this.data])
                        var obj = {
                            value: "",
                            auth: element
                        }

                        this.data[element as keyof typeof this.data] = obj
                })
            
            res(this.data)
        })

        await promise

        // Check if the object is empty
        // If it is, prompt to fill the form
        if(Object.keys(this.data).length < 1){
            this.content = "Please fill the forms"

        }
        else{
            Object.keys(this.data).map( (key) => {
                // Fetch the data authentication method
        
                var obj = this.data[key as keyof typeof this.data]
                var value = obj['value'] as string
        
                switch (obj['auth']){
                    case "username":
                        // Check that the length exceeds 0
                        // Check that there are no special characters excluding underscore

                        // RegEx for a valid username
                        let valid_username = /^[a-zA-Z0-9_]+$/
                    
                        if(!valid_username.test(value) || value.length < 1){
                            this.content = "Username is invalid"
                        }
        
                        break
        
                    case "email":
                        var result: boolean = false
                        // check that it doesn't start with a number
                        // check that there are letters before the @ sign
                        // check that there is letter after the @ sign and a dot after those letters
                        // check that there is letter after the dot

                        // Correct RegEx format for email
                        let valid_letter = /^[a-zA-Z\s]+$/
                        var first_letter = value.substring(0, 1)
                        //last_letter = data.substring((data.length - 1), data.length)

                        // check for the length i.e a@.c
                        // From this logic length should be at least 4

                        if(value.length < 4) {
                            result = false
                        }
                        // First character should be a letter
                        else if(!valid_letter.test(first_letter)) {
                            result = false
                        }
                        // Last character should be a letter
                        else if(!valid_letter.test(first_letter)) {
                            result = false
                        }
                        // . should be present
                        else if(!value.includes(".")) {
                            result = false
                        }
                        else{
                            result = true
                        }

                        if(!result)
                            this.error = 1
                            this.content = "Invalid email format e.g joe****@***.com"
        
                        break;
        
                    case "password":
                        // Check that password is above 7 characters
                        // Check that password has letters and numbers

                        // RegEx for a valid password
                        let valid_password = /\d+/g
                        if (value.match(valid_password) == null || value.length < 7){
                            this.content = "Password is invalid"
                        }
    
                        break
                
                    default:
                        break;
        
                }
            })
        }

        return this.deliver()
    }
}

export default FormAuth