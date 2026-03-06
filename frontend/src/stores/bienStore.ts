/**
 * Store Pinia — Biens immobiliers
 */
import { defineStore } from 'pinia'
import { ref } from 'vue'
import api from '../plugins/axios'
import type { Bien, BienForm } from '../types'

export const useBienStore = defineStore('biens', () => {
    const biens = ref<Bien[]>([])
    const bien = ref<Bien | null>(null)
    const loading = ref(false)
    const error = ref<string | null>(null)

    async function fetchBiens(): Promise<void> {
        loading.value = true
        error.value = null
        try {
            const response = await api.get('/biens')
            biens.value = response.data.data
        }
        catch (err: any) {
            error.value = 'Impossible de charger les biens.'
        }
        finally {
            loading.value = false
        }
    }

    async function fetchBien(id: number): Promise<void> {
        loading.value = true
        error.value = null
        try {
            const response = await api.get(`/biens/${id}`)
            bien.value = response.data.data
        }
        catch (err: any) {
            error.value = 'Bien introuvable.'
        }
        finally {
            loading.value = false
        }
    }

    async function createBien(form: BienForm): Promise<Bien> {
        loading.value = true
        error.value = null
        try {
            const response = await api.post('/biens', form)
            biens.value.unshift(response.data.data)
            return response.data.data
        }
        catch (err: any) {
            error.value = err.response?.data?.message ?? 'Erreur lors de la création.'
            throw err
        }
        finally {
            loading.value = false
        }
    }

    async function updateBien(id: number, form: Partial<BienForm>): Promise<Bien> {
        loading.value = true
        error.value = null
        try {
            const response = await api.put(`/biens/${id}`, form)
            const updated = response.data.data
            const idx = biens.value.findIndex(b => b.id === id)
            if (idx !== -1) biens.value[idx] = updated
            bien.value = updated
            return updated
        }
        catch (err: any) {
            error.value = err.response?.data?.message ?? 'Erreur lors de la mise à jour.'
            throw err
        }
        finally {
            loading.value = false
        }
    }

    async function deleteBien(id: number): Promise<void> {
        loading.value = true
        try {
            await api.delete(`/biens/${id}`)
            biens.value = biens.value.filter(b => b.id !== id)
        }
        catch (err: any) {
            error.value = 'Erreur lors de la suppression.'
            throw err
        }
        finally {
            loading.value = false
        }
    }

    return { biens, bien, loading, error, fetchBiens, fetchBien, createBien, updateBien, deleteBien }
})
