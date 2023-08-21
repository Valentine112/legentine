import axios from "axios";
import { createContext, useContext, useEffect, useRef, useState } from "react";
import { useSearchParams } from "react-router-dom";
import { _VARIABLES } from "../services/global";

const DashContext = createContext({})

export const DashProvider = ({ children }) => {
    const path = useRef()
    const [searchParam, setSearchParam] = useSearchParams()
    const [entities, setEntities] = useState()
    // Get the initial path
    useEffect(() => {
        const pathActions = {
            "home": "part=user&action=fetchHome",
            "user": ""
        }

        const params = pathActions[searchParam.get("path")]

        axios.get(_VARIABLES.serverUrl + `dashboard?${params}`)
        .then(res => {
            setEntities(res)
        })
        .catch(err => console.log(err))

    }, [])

    const fetchPage = (e) => {
        e.preventDefault()
        // Check if the url path exist first
        if(searchParam.get("path") !== null) {
            // Proceed to reconifgure it
            setSearchParam({
                path: e.target.dataset.path.toLowerCase()
            })
        }
    }

    return (
        <DashContext.Provider
            value={{
                // Getters
                path,
                searchParam,
                entities,

                // Actions
                fetchPage
            }}
        >{children}</DashContext.Provider>
    )
}

const useDash = () => useContext(DashContext)
export default useDash