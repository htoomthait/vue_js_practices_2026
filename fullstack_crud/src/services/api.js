import axios from 'axios'

const backendUrl = (import.meta.env.VITE_API_BASE_URL || 'http://localhost:8000').replace(
  /\/+$/,
  '',
)

const api = axios.create({
  baseURL: `${backendUrl}/api`,
})

export default api
