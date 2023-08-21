import React, { useEffect, useState } from 'react'
import './css/home.css'
import { LineChart, Line, CartesianGrid, XAxis, YAxis, ResponsiveContainer } from 'recharts';
import useDash from '../../hooks/useDash';

const Home = () => {
  const dash = useDash()
  const states = {
    category: {},
    userData: [],
    postData: [],
    users: {},
    posts: {}
  }

  const [category, setCategory] = useState({})
  const [userData, setUserData] = useState([])
  const [postData, setPostData] = useState([])
  const [users, setUsers] = useState({})
  const [posts, setPosts] = useState({})

  useEffect(() => {
    if(dash.entities !== undefined) {
      let payload = dash.entities
      // Checi if the status is 1
      if(payload.data.status === 1) {
        // Check if user data is not empty

        async function configData(data, type) {
          // Fetch the initial year
          //const data = payload.data.content.post.content
          // Set the users to be used later on this page
          type === "post" ? setPosts(data) :
          type === "user" ? setUsers(data) : null

          const dates = []
          const postCategory = {}

          let promise = new Promise(res => {
            data.map((v, i) => {
              dates.push(new Date(v.date.trim()).getFullYear())

              if(type === "post") {
                let categ = v.category
                postCategory[categ] = postCategory[categ] == null ? 1 : postCategory[categ] + 1
              }

              // Check if the loop has end to resolve it
              if(i === data.length - 1) res("")
            })
          })

          await promise
          setCategory(postCategory)

          // First created only unique values
          // This returns an object which is converted to an array and sorted out
          let dateSort = new Set(dates)
          dateSort = Array.from(dateSort).sort()

          console.log({dateSort})

          // Fetch all the category for posts and get their total
          // count and process the dates
          let count = {}

          dateSort.forEach(async (elem, ind) => {
            console.log(ind)
            // Initialize the data, so it doesn't keep adding data on re-render
            setUserData([])
            setPostData([])

            // Counting each seperate elements
            let n = 1
            const promise = new Promise(res => {
              dates.forEach(e => {
                if(e === elem) res(count[elem] = n++)
              })
            })

            await promise
            // Set the postData
            if(type === "post") {
              setPostData(prev => ([
                ...prev,
                { name: elem, uv: count[elem], pv: 2400, amt: 2400 }
              ]))
            }
            // Set the userData
            if(type === "user") {
              setUserData(prev => ([
                ...prev,
                {name: elem, uv: count[elem], pv: 2400, amt: 2400}
              ]))
            }

          })

        }

        const user = payload.data.content.users.content
        const post = payload.data.content.post.content
        // Check if user data is not empty
        if(user.length > 0) {
          configData(user, "user")
        }

        // Check if post data is not empty
        if(post.length > 0) {
          configData(post, "post")
        }
        
      }
    }

  }, [dash.entities, dash.path])

  useEffect(() => {
    //console.log({userData})
  }, [userData])

  return (
    <div className='home container'>
      <div className='box-holder row justify-content-around'>
        
        {/* User chart */}
        <div className='boxes col-11 col-md-8 col-lg-10'>
          <section className='section-header'>
            <p>Users</p>
          </section>
          <section>
            <div>
              <ResponsiveContainer>
                <LineChart width={400} height={400} data={userData}>
                  <Line type="monotone" dataKey="uv" stroke="#8884d8" />
                  <CartesianGrid stroke="#ccc" />
                  <XAxis dataKey="name" />
                  <YAxis />
                </LineChart>
              </ResponsiveContainer>
            </div>
          </section>
          <div>
            <p>Total users - {users.length}</p>
          </div>
        </div>

        {/* Post chart */}
        <div className='boxes col-11 col-md-8 col-lg-10 mt-3'>
          <section className='section-header'>
            <p>Posts</p>
          </section>
          <section>
            <div>
              <ResponsiveContainer>
                <LineChart width={400} height={400} data={postData}>
                  <Line type="monotone" dataKey="uv" stroke="#8884d8" />
                  <CartesianGrid stroke="#ccc" />
                  <XAxis dataKey="name" />
                  <YAxis />
                </LineChart>
              </ResponsiveContainer>
            </div>
          </section>
          <div className='row justify-content-left chart-details'>
            <div className='col-12'>
              <p>Total post - {posts.length}</p>
            </div>
            {
              Object.keys(category).map((elem, ind) => ( 
                <div 
                  className='col-12 col-md-3 col-lg-2 category'
                  key={"category" + ind}
                >
                  <p>{elem} - {category[elem]}</p>
                </div>
              ))
            }
          </div>
        </div>

        <div>

        </div>

        <div className=''>

        </div>

        {/* Active users, Feedbacks,  */}
      </div>
    </div>
  )
}

export default Home