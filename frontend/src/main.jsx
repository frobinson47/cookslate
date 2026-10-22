import React from 'react'
import ReactDOM from 'react-dom/client'
import './sentry.js'
import App from './App.jsx'
import './index.css'
import './styles/print.css'
import { reloadForNewVersion } from './utils/staleAssets'

window.addEventListener('vite:preloadError', (event) => {
  event.preventDefault()
  reloadForNewVersion()
})

ReactDOM.createRoot(document.getElementById('root')).render(
  <React.StrictMode>
    <App />
  </React.StrictMode>,
)
