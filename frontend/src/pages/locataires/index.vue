<script setup lang="ts">
import { ref, computed, h, onMounted, resolveComponent } from 'vue'
import type { TableColumn } from '@nuxt/ui'
import type { Locataire } from '../../types'
import { useLocataireStore } from '../../stores/locataireStore'

const UButton = resolveComponent('UButton')
const UDropdownMenu = resolveComponent('UDropdownMenu')

const locataireStore = useLocataireStore()
const toast = useToast()
const search = ref('')

onMounted(() => locataireStore.fetchLocataires())

const filtered = computed(() => {
  if (!search.value) return locataireStore.locataires
  const q = search.value.toLowerCase()
  return locataireStore.locataires.filter(l =>
    l.nom.toLowerCase().includes(q)
    || l.prenom.toLowerCase().includes(q)
    || (l.email ?? '').toLowerCase().includes(q),
  )
})

function getRowItems(loc: Locataire) {
  return [[
    {
      label: 'Supprimer',
      icon: 'i-lucide-trash',
      color: 'error',
      async onSelect() {
        await locataireStore.deleteLocataire(loc.id)
        toast.add({ title: 'Locataire supprimé', color: 'success' })
      },
    },
  ]]
}

const columns: TableColumn<Locataire>[] = [
  { accessorKey: 'id', header: '#', size: 60 },
  {
    accessorKey: 'nom',
    header: 'Locataire',
    cell: ({ row }) => h('div', [
      h('p', { class: 'font-medium text-highlighted' }, `${row.original.prenom} ${row.original.nom}`),
      h('p', { class: 'text-xs text-muted' }, row.original.email ?? '—'),
    ]),
  },
  {
    accessorKey: 'telephone',
    header: 'Téléphone',
    cell: ({ row }) => row.original.telephone ?? '—',
  },
  {
    id: 'actions',
    cell: ({ row }) => h('div', { class: 'flex justify-end' },
      h(UDropdownMenu, {
        content: { align: 'end' },
        items: getRowItems(row.original),
      }, () => h(UButton, { icon: 'i-lucide-ellipsis-vertical', color: 'neutral', variant: 'ghost' })),
    ),
  },
]
</script>

<template>
  <UDashboardPanel id="locataires">
    <template #header>
      <UDashboardNavbar title="Locataires">
        <template #leading>
          <UDashboardSidebarCollapse />
        </template>
        <template #right>
          <UButton to="/locataires/create" icon="i-lucide-plus" size="sm">
            Nouveau locataire
          </UButton>
        </template>
      </UDashboardNavbar>
    </template>

    <template #body>
      <div class="flex items-center gap-3 mb-4">
        <UInput v-model="search" icon="i-lucide-search" placeholder="Rechercher…" class="max-w-sm" />
        <span class="text-sm text-muted ml-auto">{{ filtered.length }} locataire(s)</span>
      </div>

      <UTable
        :data="filtered"
        :columns="columns"
        :loading="locataireStore.loading"
        :ui="{
          base: 'table-fixed border-separate border-spacing-0',
          thead: '[&>tr]:bg-elevated/50',
          th: 'py-2 first:rounded-l-lg last:rounded-r-lg border-y border-default first:border-l last:border-r',
          td: 'border-b border-default',
        }"
      />

      <div v-if="!locataireStore.loading && filtered.length === 0" class="text-center py-16">
        <UIcon name="i-lucide-users" class="size-12 text-muted mx-auto mb-3" />
        <p class="text-muted text-sm">Aucun locataire trouvé</p>
        <UButton to="/locataires/create" class="mt-4" size="sm">Ajouter un locataire</UButton>
      </div>
    </template>
  </UDashboardPanel>
</template>
