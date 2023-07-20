import React from 'react'
import ReactDOM from 'react-dom/client'
import { BrowserRouter } from 'react-router-dom'
import App from './App'
import './index.css'

ReactDOM.createRoot(document.getElementById('root')).render(
  <React.StrictMode>
    <BrowserRouter>
      <App />
    </BrowserRouter>
  </React.StrictMode>,
)

/**
 * Recently updated
 * ---
import * as React from "react"
import { createRoot } from "react-dom/client"
import {
  createBrowerRouter,
  RouterProvider,
  Route,
} from "react-router-dom"
import Login from "./pages/access/login"

const router = createBrowerRouter([
  {
    path: "/",
    element: <Login />
  }
])

createRoot(document.getElementById("root")).render(
  <RouterProvider router={router} />
)

*/