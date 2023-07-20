import { createContext, useContext, useState } from "react";
import { ResponseMessage as response} from "../services/ResponseMessage";

const AuthContext = createContext({})

export const AuthProvider = ({ children }) => {
    const res = new response

    const [username, setUsername] = useState("")
    const [password, setPassword] = useState("")
    const [message, setMessage] = useState(res.deliver())
    const [clientError, setClientError] = useState(false)

    const login = () => {
        // check if form is not empty
        if(username.length < 1 || password.length < 1){
            res.status = 1
            res.type = 1
            res.message = "fill"
            res.content = "Invalid username or password"

            setMessage(res.deliver())

        }else{

        }

        // Config wether the error box should be displayed
        res.message === "fill" ? setClientError(true) : setClientError(false)
    }

    return (
        <AuthContext.Provider
            value={{
                // Getters
                username,
                password,
                clientError,

                // Setters
                setUsername,
                setPassword,
                setClientError,

                //Error
                message,

                // Actions
                login
            }}
        >{children}</AuthContext.Provider>
    )
}

const useAuth = () => useContext(AuthContext)
export default useAuth