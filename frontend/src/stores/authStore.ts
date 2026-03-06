/**
 * Store Pinia — Authentification
 * Gère : login, logout, persistance du token et de l'utilisateur
 */
import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import api from '../plugins/axios'
import type { AuthUser } from '../types'

export const useAuthStore = defineStore('auth', () => {
    // ─── State ───────────────────────────────────────────────────────────────
    const token = ref<string | null>(localStorage.getItem('auth_token'))
    const user = ref<AuthUser | null>(
        JSON.parse(localStorage.getItem('auth_user') ?? 'null'),
    )
    const loading = ref(false)
    const error = ref<string | null>(null)

    // ─── Getters ──────────────────────────────────────────────────────────────
    const isAuthenticated = computed(() => !!token.value)

    // ─── Actions ──────────────────────────────────────────────────────────────

    async function login(email: string, password: string): Promise<void> {
        loading.value = true
        error.value = null
        try {
            const response = await api.post('/auth/login', { email, password })
            const { token: newToken, user: newUser } = response.data

            token.value = newToken
            user.value = newUser

            // Persistance dans localStorage
            localStorage.setItem('auth_token', newToken)
            localStorage.setItem('auth_user', JSON.stringify(newUser))
        }
        catch (err: any) {
            error.value = err.response?.data?.message
                ?? err.response?.data?.errors?.email?.[0]
                ?? 'Identifiants incorrects.'
            throw err
        }
        finally {
            loading.value = false
        }
    }

    async function logout(): Promise<void> {
        try {
            await api.post('/auth/logout')
        }
        catch { }
        finally {
            token.value = null
            user.value = null
            localStorage.removeItem('auth_token')
            localStorage.removeItem('auth_user')
        }
    }

    async function fetchMe(): Promise<void> {
        try {
            const response = await api.get('/auth/me')
            user.value = response.data.user
            localStorage.setItem('auth_user', JSON.stringify(user.value))
        }
        catch {
            token.value = null
            user.value = null
            localStorage.removeItem('auth_token')
            localStorage.removeItem('auth_user')
        }
    }

    return { token, user, loading, error, isAuthenticated, login, logout, fetchMe }
})
