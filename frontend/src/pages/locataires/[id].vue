<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useToast } from '@nuxt/ui/composables'
import type { LocataireForm } from '../../types'
import { useLocataireStore } from '../../stores/locataireStore'

const route = useRoute('/locataires/[id]')
const router = useRouter()
const locataireStore = useLocataireStore()
const toast = useToast()

const id = Number(route.params.id)

const form = ref<LocataireForm>({
  nom: '',
  prenom: '',
  email: '',
  telephone: '',
})

const errors = ref<Record<string, string>>({})
const notFound = ref(false)

onMounted(async () => {
  await locataireStore.fetchLocataire(id)

  if (locataireStore.locataire) {
    const l = locataireStore.locataire
    form.value = {
      nom: l.nom,
      prenom: l.prenom,
      email: l.email ?? '',
      telephone: l.telephone ?? '',
    }
  }
  else {
    notFound.value = true
  }
})

function validate(): boolean {
  errors.value = {}
  if (!form.value.nom) errors.value.nom = 'Le nom est requis.'
  if (!form.value.prenom) errors.value.prenom = 'Le prénom est requis.'
  return Object.keys(errors.value).length === 0
}

async function handleSubmit() {
  if (!validate()) return
  try {
    await locataireStore.updateLocataire(id, form.value)
    toast.add({ title: 'Locataire mis à jour !', color: 'success', icon: 'i-lucide-check-circle' })
    router.push('/locataires')
  }
  catch (err: any) {
    const apiErrors = err.response?.data?.errors ?? {}
    errors.value = Object.fromEntries(
      Object.entries(apiErrors).map(([k, v]: any) => [k, v[0]]),
    )
  }
}
</script>

<template>
  <UDashboardPanel id="locataires-edit">
    <template #header>
      <UDashboardNavbar :title="`Modifier — ${locataireStore.locataire?.prenom ?? ''} ${locataireStore.locataire?.nom ?? 'Locataire'}`">
        <template #leading>
          <UDashboardSidebarCollapse />
        </template>
        <template #right>
          <UButton to="/locataires" variant="ghost" icon="i-lucide-arrow-left" size="sm">
            Retour
          </UButton>
        </template>
      </UDashboardNavbar>
    </template>

    <template #body>
      <div class="max-w-xl mx-auto">
        <!-- Not found -->
        <UAlert
          v-if="notFound"
          color="error"
          icon="i-lucide-alert-circle"
          title="Locataire introuvable"
          description="Ce locataire n'existe pas ou a été supprimé."
          class="mb-4"
        />

        <!-- Loading -->
        <div v-else-if="locataireStore.loading && !form.nom" class="flex justify-center py-16">
          <UIcon name="i-lucide-loader" class="size-8 animate-spin text-muted" />
        </div>

        <!-- Formulaire -->
        <UCard v-else>
          <template #header>
            <h2 class="font-semibold text-highlighted flex items-center gap-2">
              <UIcon name="i-lucide-pencil" class="size-5 text-primary" />
              Modifier le locataire
            </h2>
          </template>

          <form class="space-y-4" @submit.prevent="handleSubmit">
            <div class="grid grid-cols-2 gap-4">
              <UFormField label="Prénom" name="prenom" :error="errors.prenom" required>
                <UInput v-model="form.prenom" placeholder="Alice" class="w-full" />
              </UFormField>
              <UFormField label="Nom" name="nom" :error="errors.nom" required>
                <UInput v-model="form.nom" placeholder="Durand" class="w-full" />
              </UFormField>
            </div>

            <UFormField label="Email" name="email" :error="errors.email">
              <UInput
                v-model="form.email"
                type="email"
                icon="i-lucide-mail"
                placeholder="alice@example.com"
                class="w-full"
              />
            </UFormField>

            <UFormField label="Téléphone" name="telephone">
              <UInput
                v-model="form.telephone"
                icon="i-lucide-phone"
                placeholder="07 12 34 56 78"
                class="w-full"
              />
            </UFormField>

            <div class="flex items-center justify-end gap-3 pt-2">
              <UButton to="/locataires" variant="ghost" color="neutral">
                Annuler
              </UButton>
              <UButton type="submit" :loading="locataireStore.loading" icon="i-lucide-save">
                Enregistrer
              </UButton>
            </div>
          </form>
        </UCard>
      </div>
    </template>
  </UDashboardPanel>
</template>
