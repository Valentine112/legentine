import { Routes, Route } from 'react-router-dom'
import Login from './pages/access/login'
import './App.css'

function App() {
  return (
    <Routes>
      <Route path="/" element={<Login />} />
      <Route path="/home" element={<Home />} />
    </Routes>
  )
}

export default App
