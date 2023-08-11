import React, { useEffect } from 'react'
import 'bootstrap/dist/css/bootstrap.css'
import Sidebar from '../../components/sidebar/sidebar'
import useDash from '../../hooks/useDash'
import './index.css'
import Home from '../../components/pages/home'

const Dashboard = () => {
  const dash = useDash()
  const path = dash.searchParam.get("path")

  return (
    <div className='dashboard'>
      <Sidebar></Sidebar>
      <main className='col-12 col-md-8 col-lg-9 container dashboard-main'>
        <nav className='mb-3'>
          <div className='row justify-content-between align-items-center'>
            <div className='col-3'>
              <span>
                {
                  path != null ? path.split("")[0].toUpperCase() + path.substr(1, path.length) : null
                }
              </span>
            </div>

            <div className='col-3'>
              <button className='btn btn-danger p-4 pb-1 pt-1'>Logout</button>
            </div>
          </div>
        </nav>

        {
          path === "home" ? <Home /> :
          path === "user" ? "" : ""
        }
      </main>
    </div>
  )
}

export default Dashboard