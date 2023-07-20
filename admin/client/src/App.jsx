import { Routes, Route } from 'react-router-dom'
import Login from './pages/access/login'
import './App.css'
import 'bootstrap/dist/css/bootstrap.css'
import './components/font/fonts.css'
import useAuth, { AuthProvider } from './hooks/useAuth'

function App() {
  return (
    <div className='App'>
      <AuthProvider>
        <Login />
      </AuthProvider>
    </div>
  )
}

export default App
