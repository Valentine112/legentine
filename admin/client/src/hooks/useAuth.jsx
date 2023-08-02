import { createContext, useContext, useState, useRef } from "react";
import axios from 'axios'
import { _VARIABLES } from "../services/global";
import { useNavigate } from "react-router-dom";


const AuthContext = createContext({})

export const AuthProvider = ({ children }) => {
    const navigate = useNavigate()
    
    const username = useRef()
    const password = useRef()
    const [message, setMessage] = useState({})
    const [clientError, setClientError] = useState(false)

    let res = {}

    const login = async () => {
        let usernameValue = username.current.value
        let passwordValue = password.current.value
        // check if form is not empty
        if(usernameValue.length < 1 || passwordValue.length < 1){
            res.status = 1
            res.type = 1
            res.message = "fill"
            res.content = "Invalid username or password"

            setMessage(res)

        }else{
            // Send the data to the backend
            const payload = {
                part: "login",
                action: "login",
                val: {
                    username: usernameValue,
                    password: passwordValue
                }
            }

            let response  = (await axios.post(_VARIABLES.serverUrl, payload)).data
            if(response.status === 1 && response.content === "login") {
                // Redirect user
                navigate("/dashboard?path=home", {replace: false})
            }
            res = response
            setMessage(res)
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