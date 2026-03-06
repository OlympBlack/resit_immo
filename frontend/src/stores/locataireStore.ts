/**
 * Store Pinia — Locataires
 */
import { defineStore } from 'pinia'
import { ref } from 'vue'
import api from '../plugins/axios'
import type { Locataire, LocataireForm } from '../types'

export const useLocataireStore = defineStore('locataires', () => {
    const locataires = ref<Locataire[]>([])
    const locataire = ref<Locataire | null>(null)
    const loading = ref(false)
    const error = ref<string | null>(null)

    async function fetchLocataires(): Promise<void> {
        loading.value = true
        error.value = null
        try {
            const response = await api.get('/locataires')
            locataires.value = response.data.data
        }
        catch {
            error.value = 'Impossible de charger les locataires.'
        }
        finally {
            loading.value = false
        }
    }

    async function fetchLocataire(id: number): Promise<void> {
        loading.value = true
        try {
            const response = await api.get(`/locataires/${id}`)
            locataire.value = response.data.data
        }
        catch {
            error.value = 'Locataire introuvable.'
        }
        finally {
            loading.value = false
        }
    }

    async function createLocataire(form: LocataireForm): Promise<Locataire> {
        loading.value = true
        error.value = null
        try {
            const response = await api.post('/locataires', form)
            locataires.value.unshift(response.data.data)
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

    async function deleteLocataire(id: number): Promise<void> {
        loading.value = true
        try {
            await api.delete(`/locataires/${id}`)
            locataires.value = locataires.value.filter(l => l.id !== id)
        }
        catch {
            error.value = 'Erreur lors de la suppression.'
            throw new Error()
        }
        finally {
            loading.value = false
        }
    }

    return { locataires, locataire, loading, error, fetchLocataires, fetchLocataire, createLocataire, deleteLocataire }
})
