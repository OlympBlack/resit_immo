<script setup lang="ts">
import { ref, computed, h, onMounted, resolveComponent } from 'vue'
import { useRouter } from 'vue-router'
import type { TableColumn } from '@nuxt/ui'
import type { Bien } from '../../types'
import { useBienStore } from '../../stores/bienStore'

const UBadge = resolveComponent('UBadge')
const UButton = resolveComponent('UButton')
const UDropdownMenu = resolveComponent('UDropdownMenu')

const router = useRouter()
const bienStore = useBienStore()
const toast = useToast()

const search = ref('')

onMounted(() => bienStore.fetchBiens())

const filteredBiens = computed(() => {
  if (!search.value) return bienStore.biens
  const q = search.value.toLowerCase()
  return bienStore.biens.filter(b =>
    b.titre.toLowerCase().includes(q)
    || b.adresse.toLowerCase().includes(q)
    || (b.ville ?? '').toLowerCase().includes(q),
  )
})

function getRowItems(bien: Bien) {
  return [[
    {
      label: 'Modifier',
      icon: 'i-lucide-pencil',
      onSelect() { router.push(`/biens/${bien.id}`) },
    },
  ], [
    {
      label: 'Supprimer',
      icon: 'i-lucide-trash',
      color: 'error',
      async onSelect() {
        await bienStore.deleteBien(bien.id)
        toast.add({ title: 'Bien supprimé', color: 'success' })
      },
    },
  ]]
}

const columns: TableColumn<Bien>[] = [
  {
    accessorKey: 'id',
    header: '#',
    size: 60,
  },
  {
    accessorKey: 'titre',
    header: 'Titre',
    cell: ({ row }) => h('div', [
      h('p', { class: 'font-medium text-highlighted' }, row.original.titre),
      h('p', { class: 'text-xs text-muted' }, row.original.adresse),
    ]),
  },
  {
    accessorKey: 'type',
    header: 'Type',
    cell: ({ row }) => h(UBadge, {
      color: row.original.type === 'maison' ? 'warning' : 'info',
      variant: 'subtle',
      class: 'capitalize',
    }, () => row.original.type),
  },
  {
    accessorKey: 'ville',
    header: 'Ville',
    cell: ({ row }) => row.original.ville ?? '—',
  },
  {
    accessorKey: 'prix',
    header: 'Loyer',
    cell: ({ row }) => h('span', { class: 'font-semibold' },
      `${Number(row.original.prix).toLocaleString('fr-FR')} €`),
  },
  {
    accessorKey: 'proprietaire',
    header: 'Propriétaire',
    cell: ({ row }) => row.original.proprietaire
      ? `${row.original.proprietaire.prenom} ${row.original.proprietaire.nom}`
      : '—',
  },
  {
    id: 'actions',
    cell: ({ row }) => h('div', { class: 'flex justify-end' },
      h(UDropdownMenu, {
        content: { align: 'end' },
        items: getRowItems(row.original),
      }, () => h(UButton, {
        icon: 'i-lucide-ellipsis-vertical',
        color: 'neutral',
        variant: 'ghost',
      })),
    ),
  },
]
</script>

<template>
  <UDashboardPanel id="biens">
    <template #header>
      <UDashboardNavbar title="Biens immobiliers">
        <template #leading>
          <UDashboardSidebarCollapse />
        </template>
        <template #right>
          <UButton
            to="/biens/create"
            icon="i-lucide-plus"
            size="sm"
          >
            Nouveau bien
          </UButton>
        </template>
      </UDashboardNavbar>
    </template>

    <template #body>
      <!-- Barre de recherche -->
      <div class="flex items-center gap-3 mb-4">
        <UInput
          v-model="search"
          icon="i-lucide-search"
          placeholder="Rechercher un bien…"
          class="max-w-sm"
        />
        <span class="text-sm text-muted ml-auto">
          {{ filteredBiens.length }} bien(s)
        </span>
      </div>

      <!-- Tableau -->
      <UTable
        :data="filteredBiens"
        :columns="columns"
        :loading="bienStore.loading"
        :ui="{
          base: 'table-fixed border-separate border-spacing-0',
          thead: '[&>tr]:bg-elevated/50',
          tbody: '[&>tr]:last:[&>td]:border-b-0',
          th: 'py-2 first:rounded-l-lg last:rounded-r-lg border-y border-default first:border-l last:border-r',
          td: 'border-b border-default',
        }"
      />

      <!-- Etat vide -->
      <div
        v-if="!bienStore.loading && filteredBiens.length === 0"
        class="text-center py-16"
      >
        <UIcon name="i-lucide-building-2" class="size-12 text-muted mx-auto mb-3" />
        <p class="text-muted text-sm">
          Aucun bien trouvé
        </p>
        <UButton to="/biens/create" class="mt-4" size="sm">
          Ajouter un bien
        </UButton>
      </div>
    </template>
  </UDashboardPanel>
</template>
