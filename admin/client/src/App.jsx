import { Routes, Route } from 'react-router-dom'
import Login from './pages/access/login'
import Dashboard from './pages/user'
import './App.css'
import './components/font/fonts.css'
import { AuthProvider } from './hooks/useAuth'
import { DashProvider } from './hooks/useDash'

function App() {
  return (
    <div className='App'>
      <Routes>
        <Route path="/" element={
          <AuthProvider>
            <Login />
          </AuthProvider>
        }/>

        <Route path='/dashboard' element={
          <DashProvider>
            <Dashboard />
          </DashProvider>
        }/>
      </Routes>
    </div>
  )
}


export default App
