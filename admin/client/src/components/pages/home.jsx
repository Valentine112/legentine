import React, { useEffect, useState } from 'react'
import './css/home.css'
import { LineChart, Line, CartesianGrid, XAxis, YAxis, ResponsiveContainer } from 'recharts';
import useDash from '../../hooks/useDash';

const Home = () => {
  const dash = useDash()
  const [data, setData] = useState([])
  useEffect(() => {
    if(dash.entities != undefined) {
      let payload = dash.entities
      // Checi if the status is 1
      if(payload.data.status === 1) {
        // Check if there is data
        if(payload.data.content.users.content.length > 0) {
          // Fetch the initial year
          const users = payload.data.content.users.content
          //console.log(users)
          const dates = []
          users.map((v, i) => {
            if(i > 2) dates.push(new Date(v.date))
          })


          console.log(dates)
          let result = Math.min.apply(null, dates)
          
          console.log(new Date(result).getFullYear())
        }
      }
    }

  }, [dash.entities])

  /*setData([
    {name: 'Page A', uv: 400, pv: 2400, amt: 2400},
    {name: 'Page A', uv: 300, pv: 2400, amt: 2400},
    {name: 'Page A', uv: 200, pv: 2400, amt: 2400},
    {name: 'Page A', uv: 300, pv: 2400, amt: 2400}
  ])*/

  return (
    <div className='home container'>
      <div className='box-holder row justify-content-around'>
        <div className='boxes col-11 col-md-8 col-lg-10'>
          <section className='section-header'>
            Users
          </section>
          <section>
            <div>
              <ResponsiveContainer>
                <LineChart width={400} height={400} data={data}>
                  <Line type="monotone" dataKey="uv" stroke="#8884d8" />
                  <CartesianGrid stroke="#ccc" />
                  <XAxis dataKey="name" />
                  <YAxis />
                </LineChart>
              </ResponsiveContainer>
            </div>
          </section>
        </div>
      </div>
    </div>
  )
}

export default Home