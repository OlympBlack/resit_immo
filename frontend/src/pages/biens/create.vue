<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useToast } from '@nuxt/ui/composables'
import { useBienStore } from '../../stores/bienStore'
import api from '../../plugins/axios'

const router = useRouter()
const bienStore = useBienStore()
const toast = useToast()

interface ProprietaireSimple { id: number; nom: string; prenom: string }
const proprietaires = ref<ProprietaireSimple[]>([])

onMounted(async () => {
  const res = await api.get('/proprietaires')
  proprietaires.value = res.data.data
})

const form = ref<any>({
  titre: '',
  type: '',
  adresse: '',
  ville: '',
  prix: '',
  proprietaire_id: '',
})

const errors = ref<Record<string, string>>({})

function validate(): boolean {
  errors.value = {}
  if (!form.value.titre) errors.value.titre = 'Le titre est requis.'
  if (!form.value.type) errors.value.type = 'Le type est requis.'
  if (!form.value.adresse) errors.value.adresse = "L'adresse est requise."
  if (!form.value.prix) errors.value.prix = 'Le prix est requis.'
  if (!form.value.proprietaire_id) errors.value.proprietaire_id = 'Le propriétaire est requis.'
  return Object.keys(errors.value).length === 0
}

async function handleSubmit() {
  if (!validate()) return
  try {
    await bienStore.createBien(form.value as any)
    toast.add({ title: 'Bien créé avec succès !', color: 'success', icon: 'i-lucide-check-circle' })
    router.push('/biens')
  }
  catch (err: any) {
    const apiErrors = err.response?.data?.errors ?? {}
    errors.value = Object.fromEntries(
      Object.entries(apiErrors).map(([k, v]: any) => [k, v[0]]),
    )
  }
}

const typeOptions = [
  { label: 'Appartement', value: 'appartement' },
  { label: 'Maison', value: 'maison' },
]
</script>

<template>
  <UDashboardPanel id="biens-create">
    <template #header>
      <UDashboardNavbar title="Nouveau bien">
        <template #leading>
          <UDashboardSidebarCollapse />
        </template>
        <template #right>
          <UButton to="/biens" variant="ghost" icon="i-lucide-arrow-left" size="sm">
            Retour
          </UButton>
        </template>
      </UDashboardNavbar>
    </template>

    <template #body>
      <div class="max-w-2xl mx-auto">
        <UCard>
          <template #header>
            <h2 class="font-semibold text-highlighted flex items-center gap-2">
              <UIcon name="i-lucide-building-2" class="size-5 text-primary" />
              Informations du bien
            </h2>
          </template>

          <form class="space-y-5" @submit.prevent="handleSubmit">
            <!-- Titre -->
            <UFormField label="Titre" name="titre" :error="errors.titre" required>
              <UInput
                v-model="form.titre"
                placeholder="Ex: Appartement T3 centre-ville"
                icon="i-lucide-tag"
                class="w-full"
              />
            </UFormField>

            <!-- Type + Prix sur 2 colonnes -->
            <div class="grid grid-cols-2 gap-4">
              <UFormField label="Type" name="type" :error="errors.type" required>
                <USelect
                  v-model="form.type"
                  :items="typeOptions"
                  placeholder="Choisir un type"
                  class="w-full"
                />
              </UFormField>

              <UFormField label="Loyer mensuel (€)" name="prix" :error="errors.prix" required>
                <UInput
                  v-model="form.prix"
                  type="number"
                  placeholder="850"
                  icon="i-lucide-euro"
                  class="w-full"
                />
              </UFormField>
            </div>

            <!-- Adresse -->
            <UFormField label="Adresse" name="adresse" :error="errors.adresse" required>
              <UInput
                v-model="form.adresse"
                placeholder="5 Avenue Victor Hugo"
                icon="i-lucide-map-pin"
                class="w-full"
              />
            </UFormField>

            <!-- Ville -->
            <UFormField label="Ville" name="ville">
              <UInput
                v-model="form.ville"
                placeholder="Lyon"
                icon="i-lucide-building"
                class="w-full"
              />
            </UFormField>

            <!-- Propriétaire -->
            <UFormField label="Propriétaire" name="proprietaire_id" :error="errors.proprietaire_id" required>
              <USelect
                v-model="form.proprietaire_id"
                :items="proprietaires.map(p => ({ label: `${p.prenom} ${p.nom}`, value: p.id }))"
                placeholder="Sélectionner un propriétaire"
                icon="i-lucide-user"
                class="w-full"
              />
            </UFormField>

            <!-- Actions -->
            <div class="flex items-center justify-end gap-3 pt-2">
              <UButton
                to="/biens"
                variant="ghost"
                color="neutral"
              >
                Annuler
              </UButton>
              <UButton
                type="submit"
                :loading="bienStore.loading"
                icon="i-lucide-save"
              >
                Créer le bien
              </UButton>
            </div>
          </form>
        </UCard>
      </div>
    </template>
  </UDashboardPanel>
</template>
