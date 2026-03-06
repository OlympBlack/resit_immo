/**
 * Plugin Axios — configure l'instance globale avec :
 *  - Base URL de l'API Laravel
 *  - Intercepteur pour injecter le token Bearer automatiquement
 *  - Intercepteur de réponse pour gérer les 401
 */
import axios from 'axios'
import { useRouter } from 'vue-router'

const api = axios.create({
    baseURL: import.meta.env.VITE_API_URL ?? 'http://localhost:8000/api',
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
    },
    timeout: 10000,
})

// ─── Intercepteur de requête — injecte le Bearer Token ───────────────────────
api.interceptors.request.use(
    (config) => {
        const token = localStorage.getItem('auth_token')
        if (token) {
            config.headers.Authorization = `Bearer ${token}`
        }
        return config
    },
    (error) => Promise.reject(error),
)

// ─── Intercepteur de réponse — gère les 401 ──────────────────────────────────
api.interceptors.response.use(
    (response) => response,
    (error) => {
        if (error.response?.status === 401) {
            // Token expiré ou invalide → vider le storage et rediriger
            localStorage.removeItem('auth_token')
            localStorage.removeItem('auth_user')
            window.location.href = '/login'
        }
        return Promise.reject(error)
    },
)

export default api
