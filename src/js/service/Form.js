// Saving the error message in variables
const fullname_error = "Fullname should only have letters and space",
username_error = "Username only accepts letters,numbers and underscore",
email_error = "Email address should be in format john****@****.com",
password_error = "Password should contain both letters and numbers and should be greater than 7"

class Form {

    constructor() {
        return this
    }

    validate(data, type) {

        var result = false,
        valid_letter = /^[a-zA-Z\s]+$/,
        valid_username = /^[a-zA-Z0-9_]+$/,
        valid_password = /\d+/g
        var func = new Func

        switch (type) {
            case 'name':
                if(!valid_letter.test(data) || func.stripSpace(data).length < 1) {
                    result = false
                }else {
                    result = true
                }
                break;

            case 'username':
                if(!valid_username.test(data) || func.stripSpace(data).length < 1) {
                    result = false
                }else {
                    result = true
                }
                break;
            
            case 'email':
                var first_letter = data.substring(0, 1)
                //last_letter = data.substring((data.length - 1), data.length)

                // check for the length i.e a@.c
                // From this logic length should be at least 4

                if(func.stripSpace(data).length < 4) {
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
                else if(!data.includes(".")) {
                    result = false
                }
                else{
                    result = true
                }
                break;
            
            case 'password':
                if (data.match(valid_password) == null || func.stripSpace(data).length < 7) {
                    result = false
                }
                else{
                    result = true
                }
                break;

            default:
                result = false
                break;
        }

        return result
    }




}