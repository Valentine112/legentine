import { createContext, useContext, useRef } from "react";
import { useSearchParams } from "react-router-dom";

const DashContext = createContext({})

export const DashProvider = ({ children }) => {
    const path = useRef()
    const [url] = useSearchParams()

    const fetchPage = (e) => {
        e.preventDefault()
        const pathElem = path.current
        console.log(url.get("home"))

        console.log(pathElem)
    }

    return (
        <DashContext.Provider
            value={{
                // Getters
                path,

                // Actions
                fetchPage
            }}
        >{children}</DashContext.Provider>
    )
}

const useDash = () => useContext(DashContext)
export default useDash