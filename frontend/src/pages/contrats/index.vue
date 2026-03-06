<script setup lang="ts">
import { ref, computed, h, onMounted, resolveComponent } from 'vue'
import type { TableColumn } from '@nuxt/ui'
import type { Contrat } from '../../types'
import { useContratStore } from '../../stores/contratStore'

const UBadge = resolveComponent('UBadge')
const UButton = resolveComponent('UButton')
const UDropdownMenu = resolveComponent('UDropdownMenu')

const contratStore = useContratStore()
const toast = useToast()

const filtre = ref<'tous' | 'actif' | 'termine' | 'resilie'>('actif')

onMounted(() => contratStore.fetchContrats())

const filtered = computed(() => {
  if (filtre.value === 'tous') return contratStore.contrats
  return contratStore.contrats.filter(c => c.statut === filtre.value)
})

const statutColor = (s: string) => ({ actif: 'success', termine: 'neutral', resilie: 'error' }[s] ?? 'neutral') as any

function getRowItems(contrat: Contrat) {
  return [[
    {
      label: 'Marquer terminé',
      icon: 'i-lucide-check',
      disabled: contrat.statut !== 'actif',
      async onSelect() {
        await contratStore.updateStatut(contrat.id, 'termine')
        toast.add({ title: 'Contrat terminé', color: 'success' })
      },
    },
    {
      label: 'Résilier',
      icon: 'i-lucide-x-circle',
      color: 'warning',
      disabled: contrat.statut !== 'actif',
      async onSelect() {
        await contratStore.updateStatut(contrat.id, 'resilie')
        toast.add({ title: 'Contrat résilié', color: 'warning' })
      },
    },
  ], [
    {
      label: 'Supprimer',
      icon: 'i-lucide-trash',
      color: 'error',
      async onSelect() {
        await contratStore.deleteContrat(contrat.id)
        toast.add({ title: 'Supprimé', color: 'success' })
      },
    },
  ]]
}

const columns: TableColumn<Contrat>[] = [
  { accessorKey: 'id', header: '#', size: 60 },
  {
    accessorKey: 'bien',
    header: 'Bien',
    cell: ({ row }) => h('div', [
      h('p', { class: 'font-medium text-highlighted' }, row.original.bien?.titre ?? `Bien #${row.original.bien_id}`),
      h('p', { class: 'text-xs text-muted' }, row.original.bien?.ville ?? ''),
    ]),
  },
  {
    accessorKey: 'locataire',
    header: 'Locataire',
    cell: ({ row }) => row.original.locataire
      ? `${row.original.locataire.prenom} ${row.original.locataire.nom}`
      : '—',
  },
  {
    accessorKey: 'date_debut',
    header: 'Début',
    cell: ({ row }) => new Date(row.original.date_debut).toLocaleDateString('fr-FR'),
  },
  {
    accessorKey: 'date_fin',
    header: 'Fin',
    cell: ({ row }) => row.original.date_fin
      ? new Date(row.original.date_fin).toLocaleDateString('fr-FR')
      : 'Indéterminée',
  },
  {
    accessorKey: 'montant_mensuel',
    header: 'Loyer',
    cell: ({ row }) => h('span', { class: 'font-semibold' },
      `${Number(row.original.montant_mensuel).toLocaleString('fr-FR')} €`),
  },
  {
    accessorKey: 'statut',
    header: 'Statut',
    cell: ({ row }) => h(UBadge, {
      color: statutColor(row.original.statut),
      variant: 'subtle',
      class: 'capitalize',
    }, () => row.original.statut),
  },
  {
    id: 'actions',
    cell: ({ row }) => h('div', { class: 'flex justify-end' },
      h(UDropdownMenu, { content: { align: 'end' }, items: getRowItems(row.original) },
        () => h(UButton, { icon: 'i-lucide-ellipsis-vertical', color: 'neutral', variant: 'ghost' }))),
  },
]

const filtreOptions = [
  { label: 'Actifs', value: 'actif' },
  { label: 'Terminés', value: 'termine' },
  { label: 'Résiliés', value: 'resilie' },
  { label: 'Tous', value: 'tous' },
]
</script>

<template>
  <UDashboardPanel id="contrats">
    <template #header>
      <UDashboardNavbar title="Contrats de location">
        <template #leading><UDashboardSidebarCollapse /></template>
        <template #right>
          <UButton to="/contrats/create" icon="i-lucide-plus" size="sm">Nouveau contrat</UButton>
        </template>
      </UDashboardNavbar>
    </template>

    <template #body>
      <div class="flex items-center gap-3 mb-4">
        <USelect
          v-model="filtre"
          :items="filtreOptions"
          class="w-40"
        />
        <span class="text-sm text-muted ml-auto">{{ filtered.length }} contrat(s)</span>
      </div>

      <UTable
        :data="filtered"
        :columns="columns"
        :loading="contratStore.loading"
        :ui="{
          base: 'table-fixed border-separate border-spacing-0',
          thead: '[&>tr]:bg-elevated/50',
          th: 'py-2 first:rounded-l-lg last:rounded-r-lg border-y border-default first:border-l last:border-r',
          td: 'border-b border-default',
        }"
      />

      <div v-if="!contratStore.loading && filtered.length === 0" class="text-center py-16">
        <UIcon name="i-lucide-file-text" class="size-12 text-muted mx-auto mb-3" />
        <p class="text-muted text-sm">Aucun contrat pour ce filtre</p>
        <UButton to="/contrats/create" class="mt-4" size="sm">Créer un contrat</UButton>
      </div>
    </template>
  </UDashboardPanel>
</template>
