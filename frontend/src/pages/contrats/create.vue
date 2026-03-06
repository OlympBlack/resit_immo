<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useToast } from '@nuxt/ui/composables'
import type { Bien, Locataire } from '../../types'
import { useContratStore } from '../../stores/contratStore'
import api from '../../plugins/axios'

const router = useRouter()
const contratStore = useContratStore()
const toast = useToast()

const biens = ref<Bien[]>([])
const locataires = ref<Locataire[]>([])

onMounted(async () => {
  const [bRes, lRes] = await Promise.all([api.get('/biens'), api.get('/locataires')])
  biens.value = bRes.data.data
  locataires.value = lRes.data.data
})

const form = ref<any>({
  bien_id: '',
  locataire_id: '',
  date_debut: '',
  date_fin: '',
  montant_mensuel: '',
  statut: 'actif',
})

const errors = ref<Record<string, string>>({})

function validate(): boolean {
  errors.value = {}
  if (!form.value.bien_id) errors.value.bien_id = 'Le bien est requis.'
  if (!form.value.locataire_id) errors.value.locataire_id = 'Le locataire est requis.'
  if (!form.value.date_debut) errors.value.date_debut = 'La date de début est requise.'
  if (!form.value.montant_mensuel) errors.value.montant_mensuel = 'Le montant est requis.'
  return Object.keys(errors.value).length === 0
}

async function handleSubmit() {
  if (!validate()) return
  try {
    const payload = {
      ...form.value,
      date_fin: form.value.date_fin || null,
    }
    await contratStore.createContrat(payload as any)
    toast.add({ title: 'Contrat créé !', color: 'success', icon: 'i-lucide-check-circle' })
    router.push('/contrats')
  }
  catch (err: any) {
    const apiErrors = err.response?.data?.errors ?? {}
    errors.value = Object.fromEntries(Object.entries(apiErrors).map(([k, v]: any) => [k, v[0]]))
  }
}

const statutOptions = [
  { label: 'Actif', value: 'actif' },
  { label: 'Terminé', value: 'termine' },
  { label: 'Résilié', value: 'resilie' },
]
</script>

<template>
  <UDashboardPanel id="contrats-create">
    <template #header>
      <UDashboardNavbar title="Nouveau contrat">
        <template #leading><UDashboardSidebarCollapse /></template>
        <template #right>
          <UButton to="/contrats" variant="ghost" icon="i-lucide-arrow-left" size="sm">Retour</UButton>
        </template>
      </UDashboardNavbar>
    </template>

    <template #body>
      <div class="max-w-2xl mx-auto">
        <UCard>
          <template #header>
            <h2 class="font-semibold text-highlighted flex items-center gap-2">
              <UIcon name="i-lucide-file-plus" class="size-5 text-primary" />
              Nouveau contrat de location
            </h2>
          </template>

          <form class="space-y-5" @submit.prevent="handleSubmit">
            <!-- Bien -->
            <UFormField label="Bien concerné" name="bien_id" :error="errors.bien_id" required>
              <USelect
                v-model="form.bien_id"
                :items="biens.map(b => ({ label: `${b.titre} — ${b.ville ?? b.adresse}`, value: b.id }))"
                placeholder="Sélectionner un bien"
                icon="i-lucide-building-2"
                class="w-full"
              />
            </UFormField>

            <!-- Locataire -->
            <UFormField label="Locataire" name="locataire_id" :error="errors.locataire_id" required>
              <USelect
                v-model="form.locataire_id"
                :items="locataires.map(l => ({ label: `${l.prenom} ${l.nom}`, value: l.id }))"
                placeholder="Sélectionner un locataire"
                icon="i-lucide-user"
                class="w-full"
              />
            </UFormField>

            <!-- Dates -->
            <div class="grid grid-cols-2 gap-4">
              <UFormField label="Date de début" name="date_debut" :error="errors.date_debut" required>
                <UInput v-model="form.date_debut" type="date" class="w-full" />
              </UFormField>
              <UFormField label="Date de fin" name="date_fin" hint="Facultatif">
                <UInput v-model="form.date_fin" type="date" class="w-full" />
              </UFormField>
            </div>

            <!-- Montant + statut -->
            <div class="grid grid-cols-2 gap-4">
              <UFormField label="Loyer mensuel (€)" name="montant_mensuel" :error="errors.montant_mensuel" required>
                <UInput v-model="form.montant_mensuel" type="number" icon="i-lucide-euro" class="w-full" />
              </UFormField>
              <UFormField label="Statut" name="statut">
                <USelect v-model="form.statut" :items="statutOptions" class="w-full" />
              </UFormField>
            </div>

            <div class="flex justify-end gap-3 pt-2">
              <UButton to="/contrats" variant="ghost" color="neutral">Annuler</UButton>
              <UButton type="submit" :loading="contratStore.loading" icon="i-lucide-save">
                Créer le contrat
              </UButton>
            </div>
          </form>
        </UCard>
      </div>
    </template>
  </UDashboardPanel>
</template>
