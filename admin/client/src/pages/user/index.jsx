import React from 'react'
import 'bootstrap/dist/css/bootstrap.css'
import Sidebar from '../../components/sidebar/sidebar'
import useDash from '../../hooks/useDash'

const Dashboard = () => {
  const dash = useDash()

  return (
    <div>
      <Sidebar></Sidebar>
      <main>

      </main>
    </div>
  )
}

export default Dashboard