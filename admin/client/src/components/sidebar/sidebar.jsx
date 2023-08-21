import React from 'react'
import '../sidebar/sidebar.css'
import useDash from '../../hooks/useDash'

const Sidebar = () => {
    const pathList = ["Home", "User", "Post", "Comment", "Feedback"]
    const dash = useDash()

  return (
    <div className='sidebar-container row col-7 col-md-4 col-lg-3'>
        <div className='sidebar'>
            <div className='linkPosition'>
                <ul>
                    {pathList.map((val, ind) => (
                        <li key={val + "" + ind}>
                            <span
                                data-path={val} 
                                ref={dash.path} 
                                onClick={dash.fetchPage}
                                className={dash.searchParam.get("path") == val.toLowerCase() ? "active" : ""}
                            >
                            {val}</span>
                        </li>
                    ))}
                </ul>
            </div>
        </div>
    </div>
  )
}

export default Sidebar