import React from 'react'
import { NavLink } from 'react-router-dom'
import '../sidebar/sidebar.css'
import useDash from '../../hooks/useDash'

const Sidebar = () => {
    const dash = useDash()
  return (
    <div className='sidebar-container row col-7 col-md-4 col-lg-3'>
        <div className='sidebar'>
            <div className='linkPosition'>
                <ul>
                    <li>
                        <NavLink to="/dashboard" ref={dash.path} onClick={dash.fetchPage}>Dashboard</NavLink>
                    </li>
                    <li>
                        <NavLink to="/user">Users</NavLink>
                    </li>
                    <li>
                        <NavLink to="/post">Posts</NavLink>
                    </li>
                    <li>
                        <NavLink to="/comment">Comments</NavLink>
                    </li>
                    <li>
                        <NavLink to="/feedback">Feedbacks</NavLink>
                    </li>
                </ul>
            </div>
        </div>
    </div>
  )
}

export default Sidebar