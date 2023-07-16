import { Routes, Route } from 'react-router-dom'
import Login from './pages/access/login'
import './App.css'
import 'bootstrap/dist/css/bootstrap.css'
import './components/font/fonts.css'
import Notify from './services/Notify'

function App() {
  return (
    <div className='App'>
      <Routes>
        <Route path="/" element={<Login />} />
      </Routes>
      <Notify />
    </div>
  )
}

export default App
