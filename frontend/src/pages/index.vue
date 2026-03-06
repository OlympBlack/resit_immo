<script setup lang="ts">
import { ref, onMounted, computed } from 'vue'
import { useBienStore } from '../stores/bienStore'
import { useLocataireStore } from '../stores/locataireStore'
import { useContratStore } from '../stores/contratStore'
import { useAuthStore } from '../stores/authStore'

const authStore = useAuthStore()
const bienStore = useBienStore()
const locataireStore = useLocataireStore()
const contratStore = useContratStore()

onMounted(async () => {
  await Promise.all([
    bienStore.fetchBiens(),
    locataireStore.fetchLocataires(),
    contratStore.fetchContrats(),
  ])
})

const stats = computed(() => [
  {
    title: 'Biens',
    icon: 'i-lucide-building-2',
    value: bienStore.biens.length,
    color: 'primary',
    to: '/biens',
    description: 'biens immobiliers',
  },
  {
    title: 'Locataires',
    icon: 'i-lucide-users',
    value: locataireStore.locataires.length,
    color: 'success',
    to: '/locataires',
    description: 'locataires enregistrés',
  },
  {
    title: 'Contrats actifs',
    icon: 'i-lucide-file-check',
    value: contratStore.contratsActifs.length,
    color: 'warning',
    to: '/contrats',
    description: 'contrats en cours',
  },
  {
    title: 'Revenus mensuels',
    icon: 'i-lucide-euro',
    value: contratStore.contratsActifs
      .reduce((sum, c) => sum + Number(c.montant_mensuel), 0)
      .toLocaleString('fr-FR', { style: 'currency', currency: 'EUR' }),
    color: 'info',
    to: '/contrats',
    description: 'loyers actifs',
  },
])

// 5 derniers contrats actifs
const recentContrats = computed(() =>
  contratStore.contratsActifs.slice(0, 5),
)

const statutColor = (statut: string) => ({
  actif: 'success' as const,
  termine: 'neutral' as const,
  resilie: 'error' as const,
}[statut] ?? 'neutral')
</script>

<template>
  <UDashboardPanel id="home">
    <template #header>
      <UDashboardNavbar :title="`Bonjour, ${authStore.user?.name ?? ''} 👋`" :ui="{ right: 'gap-3' }">
        <template #leading>
          <UDashboardSidebarCollapse />
        </template>
        <template #right>
          <UButton
            to="/biens/create"
            icon="i-lucide-plus"
            size="sm"
            class="rounded-full"
          >
            Nouveau bien
          </UButton>
        </template>
      </UDashboardNavbar>
    </template>

    <template #body>
      <!-- Stats cards -->
      <UPageGrid class="lg:grid-cols-4 gap-4 mb-8">
        <UPageCard
          v-for="stat in stats"
          :key="stat.title"
          :icon="stat.icon"
          :title="stat.title"
          :to="stat.to"
          variant="subtle"
          :ui="{
            container: 'gap-y-1.5',
            wrapper: 'items-start',
            leading: 'p-2.5 rounded-full bg-primary/10 ring ring-inset ring-primary/25',
            title: 'font-normal text-muted text-xs uppercase',
          }"
        >
          <div class="text-2xl font-bold text-highlighted">
            {{ stat.value }}
          </div>
          <p class="text-xs text-muted">
            {{ stat.description }}
          </p>
        </UPageCard>
      </UPageGrid>

      <!-- Contrats récents -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Contrats actifs récents -->
        <UCard>
          <template #header>
            <div class="flex items-center justify-between">
              <h3 class="font-semibold text-highlighted flex items-center gap-2">
                <UIcon name="i-lucide-file-text" class="size-4 text-primary" />
                Contrats actifs récents
              </h3>
              <UButton to="/contrats" variant="ghost" size="xs" trailing-icon="i-lucide-arrow-right">
                Voir tout
              </UButton>
            </div>
          </template>

          <div v-if="contratStore.loading" class="flex justify-center py-8">
            <UIcon name="i-lucide-loader" class="size-6 animate-spin text-muted" />
          </div>

          <div v-else-if="recentContrats.length === 0" class="text-center py-8 text-muted text-sm">
            Aucun contrat actif
          </div>

          <ul v-else class="divide-y divide-default">
            <li
              v-for="contrat in recentContrats"
              :key="contrat.id"
              class="py-3 flex items-center justify-between gap-3"
            >
              <div class="min-w-0">
                <p class="text-sm font-medium text-highlighted truncate">
                  {{ contrat.bien?.titre ?? `Bien #${contrat.bien_id}` }}
                </p>
                <p class="text-xs text-muted">
                  {{ contrat.locataire?.prenom }} {{ contrat.locataire?.nom }}
                </p>
              </div>
              <div class="text-right shrink-0">
                <p class="text-sm font-semibold text-highlighted">
                  {{ Number(contrat.montant_mensuel).toLocaleString('fr-FR') }} €/mois
                </p>
                <UBadge :color="statutColor(contrat.statut)" variant="subtle" size="xs">
                  {{ contrat.statut }}
                </UBadge>
              </div>
            </li>
          </ul>
        </UCard>

        <!-- Biens récents -->
        <UCard>
          <template #header>
            <div class="flex items-center justify-between">
              <h3 class="font-semibold text-highlighted flex items-center gap-2">
                <UIcon name="i-lucide-building-2" class="size-4 text-primary" />
                Biens récents
              </h3>
              <UButton to="/biens" variant="ghost" size="xs" trailing-icon="i-lucide-arrow-right">
                Voir tout
              </UButton>
            </div>
          </template>

          <div v-if="bienStore.loading" class="flex justify-center py-8">
            <UIcon name="i-lucide-loader" class="size-6 animate-spin text-muted" />
          </div>

          <div v-else-if="bienStore.biens.length === 0" class="text-center py-8 text-muted text-sm">
            Aucun bien enregistré
          </div>

          <ul v-else class="divide-y divide-default">
            <li
              v-for="bien in bienStore.biens.slice(0, 5)"
              :key="bien.id"
              class="py-3 flex items-center justify-between gap-3"
            >
              <div class="min-w-0">
                <p class="text-sm font-medium text-highlighted truncate">
                  {{ bien.titre }}
                </p>
                <p class="text-xs text-muted">
                  {{ bien.ville ?? bien.adresse }}
                </p>
              </div>
              <div class="text-right shrink-0">
                <p class="text-sm font-semibold text-highlighted">
                  {{ Number(bien.prix).toLocaleString('fr-FR') }} €
                </p>
                <UBadge
                  :color="bien.type === 'maison' ? 'warning' : 'info'"
                  variant="subtle"
                  size="xs"
                >
                  {{ bien.type }}
                </UBadge>
              </div>
            </li>
          </ul>
        </UCard>
      </div>
    </template>
  </UDashboardPanel>
</template>
