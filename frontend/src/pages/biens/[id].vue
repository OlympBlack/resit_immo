<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useToast } from '@nuxt/ui/composables'
import { useBienStore } from '../../stores/bienStore'
import api from '../../plugins/axios'

const route = useRoute('/biens/[id]')
const router = useRouter()
const bienStore = useBienStore()
const toast = useToast()

const id = Number(route.params.id)

interface ProprietaireSimple { id: number; nom: string; prenom: string }
const proprietaires = ref<ProprietaireSimple[]>([])

const form = ref<any>({
  titre: '',
  type: '',
  adresse: '',
  ville: '',
  prix: '',
  proprietaire_id: '',
})

const errors = ref<Record<string, string>>({})
const notFound = ref(false)

onMounted(async () => {
  const [pRes] = await Promise.all([
    api.get('/proprietaires'),
    bienStore.fetchBien(id),
  ])
  proprietaires.value = pRes.data.data

  if (bienStore.bien) {
    const b = bienStore.bien
    form.value = {
      titre: b.titre,
      type: b.type,
      adresse: b.adresse,
      ville: b.ville ?? '',
      prix: b.prix,
      proprietaire_id: b.proprietaire_id,
    }
  }
  else {
    notFound.value = true
  }
})

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
    await bienStore.updateBien(id, form.value as any)
    toast.add({ title: 'Bien mis à jour !', color: 'success', icon: 'i-lucide-check-circle' })
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
  <UDashboardPanel id="biens-edit">
    <template #header>
      <UDashboardNavbar :title="`Modifier — ${bienStore.bien?.titre ?? 'Bien'}`">
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
        <!-- Not found -->
        <UAlert
          v-if="notFound"
          color="error"
          icon="i-lucide-alert-circle"
          title="Bien introuvable"
          description="Ce bien n'existe pas ou a été supprimé."
          class="mb-4"
        />

        <!-- Loading -->
        <div v-else-if="bienStore.loading && !form.titre" class="flex justify-center py-16">
          <UIcon name="i-lucide-loader" class="size-8 animate-spin text-muted" />
        </div>

        <!-- Formulaire -->
        <UCard v-else>
          <template #header>
            <h2 class="font-semibold text-highlighted flex items-center gap-2">
              <UIcon name="i-lucide-pencil" class="size-5 text-primary" />
              Modifier le bien
            </h2>
          </template>

          <form class="space-y-5" @submit.prevent="handleSubmit">
            <UFormField label="Titre" name="titre" :error="errors.titre" required>
              <UInput v-model="form.titre" icon="i-lucide-tag" class="w-full" />
            </UFormField>

            <div class="grid grid-cols-2 gap-4">
              <UFormField label="Type" name="type" :error="errors.type" required>
                <USelect v-model="form.type" :items="typeOptions" class="w-full" />
              </UFormField>
              <UFormField label="Loyer (€)" name="prix" :error="errors.prix" required>
                <UInput v-model="form.prix" type="number" icon="i-lucide-euro" class="w-full" />
              </UFormField>
            </div>

            <UFormField label="Adresse" name="adresse" :error="errors.adresse" required>
              <UInput v-model="form.adresse" icon="i-lucide-map-pin" class="w-full" />
            </UFormField>

            <UFormField label="Ville" name="ville">
              <UInput v-model="form.ville" icon="i-lucide-building" class="w-full" />
            </UFormField>

            <UFormField label="Propriétaire" name="proprietaire_id" :error="errors.proprietaire_id" required>
              <USelect
                v-model="form.proprietaire_id"
                :items="proprietaires.map(p => ({ label: `${p.prenom} ${p.nom}`, value: p.id }))"
                icon="i-lucide-user"
                class="w-full"
              />
            </UFormField>

            <div class="flex items-center justify-end gap-3 pt-2">
              <UButton to="/biens" variant="ghost" color="neutral">
                Annuler
              </UButton>
              <UButton type="submit" :loading="bienStore.loading" icon="i-lucide-save">
                Enregistrer
              </UButton>
            </div>
          </form>
        </UCard>
      </div>
    </template>
  </UDashboardPanel>
</template>
