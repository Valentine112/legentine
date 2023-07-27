import { Routes, Route } from 'react-router-dom'
import Login from './pages/access/login'
import Dashboard from './pages/user'
import './App.css'
import './components/font/fonts.css'
import useAuth, { AuthProvider } from './hooks/useAuth'
import useDash, { DashProvider } from './hooks/useDash'

function App() {
  return (
    <div className='App'>
      <Routes>
        <Route path="/" element={
          <AuthProvider><Login /></AuthProvider>
        }
        />

        <Route path='/dashboard' element={
          <DashProvider>
            <Dashboard />
          </DashProvider>
        }>
        </Route>
      </Routes>
    </div>
  )
}


export default App
