/**
 * Store Pinia — Contrats de location
 */
import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import api from '../plugins/axios'
import type { Contrat, ContratForm } from '../types'

export const useContratStore = defineStore('contrats', () => {
    const contrats = ref<Contrat[]>([])
    const contrat = ref<Contrat | null>(null)
    const loading = ref(false)
    const error = ref<string | null>(null)

    // Getter — uniquement les contrats actifs
    const contratsActifs = computed(() =>
        contrats.value.filter(c => c.statut === 'actif'),
    )

    async function fetchContrats(): Promise<void> {
        loading.value = true
        error.value = null
        try {
            const response = await api.get('/contrats')
            contrats.value = response.data.data
        }
        catch {
            error.value = 'Impossible de charger les contrats.'
        }
        finally {
            loading.value = false
        }
    }

    async function fetchContrat(id: number): Promise<void> {
        loading.value = true
        try {
            const response = await api.get(`/contrats/${id}`)
            contrat.value = response.data.data
        }
        catch {
            error.value = 'Contrat introuvable.'
        }
        finally {
            loading.value = false
        }
    }

    async function createContrat(form: ContratForm): Promise<Contrat> {
        loading.value = true
        error.value = null
        try {
            const response = await api.post('/contrats', form)
            contrats.value.unshift(response.data.data)
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

    async function updateContrat(id: number, form: Partial<ContratForm>): Promise<Contrat> {
        loading.value = true
        error.value = null
        try {
            const response = await api.put(`/contrats/${id}`, form)
            const updated = response.data.data
            const idx = contrats.value.findIndex(c => c.id === id)
            if (idx !== -1) contrats.value[idx] = updated
            contrat.value = updated
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

    async function updateStatut(id: number, statut: Contrat['statut']): Promise<void> {
        loading.value = true
        try {
            const response = await api.patch(`/contrats/${id}`, { statut })
            const idx = contrats.value.findIndex(c => c.id === id)
            if (idx !== -1) contrats.value[idx] = response.data.data
        }
        catch (err: any) {
            error.value = 'Erreur lors de la mise à jour du statut.'
            throw err
        }
        finally {
            loading.value = false
        }
    }

    async function deleteContrat(id: number): Promise<void> {
        loading.value = true
        try {
            await api.delete(`/contrats/${id}`)
            contrats.value = contrats.value.filter(c => c.id !== id)
        }
        catch {
            error.value = 'Erreur lors de la suppression.'
            throw new Error()
        }
        finally {
            loading.value = false
        }
    }

    return {
        contrats, contrat, loading, error, contratsActifs,
        fetchContrats, fetchContrat, createContrat, updateContrat, updateStatut, deleteContrat,
    }
})
