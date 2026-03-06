<script setup lang="ts">
import { computed, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import type { NavigationMenuItem } from '@nuxt/ui'
import { useAuthStore } from '../stores/authStore'

const authStore = useAuthStore()
const route = useRoute()
const router = useRouter()
const open = ref(false)

const close = () => { open.value = false }

const links = [[
  {
    label: 'Dashboard',
    icon: 'i-lucide-layout-dashboard',
    to: '/',
    onSelect: close,
  },
  {
    label: 'Biens',
    icon: 'i-lucide-building-2',
    to: '/biens',
    onSelect: close,
  },
  {
    label: 'Locataires',
    icon: 'i-lucide-users',
    to: '/locataires',
    onSelect: close,
  },
  {
    label: 'Contrats',
    icon: 'i-lucide-file-text',
    to: '/contrats',
    onSelect: close,
  },
], [
  {
    label: 'Documentation API',
    icon: 'i-lucide-book-open',
    to: 'http://localhost:8000/api/documentation',
    target: '_blank',
  },
]] satisfies NavigationMenuItem[][]

const groups = computed(() => [{
  id: 'links',
  label: 'Navigation',
  items: links.flat(),
}])

async function handleLogout() {
  await authStore.logout()
  router.push('/login')
}
</script>

<template>
  <UDashboardGroup unit="rem" storage="local">
    <UDashboardSidebar
      id="default"
      v-model:open="open"
      collapsible
      resizable
      class="bg-elevated/25"
      :ui="{ footer: 'lg:border-t lg:border-default' }"
    >
      <!-- Header — Logo -->
      <template #header="{ collapsed }">
        <div class="flex items-center gap-2 px-1 py-1">
          <div class="flex items-center justify-center size-8 rounded-lg bg-primary text-white shrink-0">
            <UIcon name="i-lucide-home" class="size-5" />
          </div>
          <span v-if="!collapsed" class="font-bold text-highlighted text-sm truncate">
            RESIT_Immo
          </span>
        </div>
      </template>

      <!-- Navigation -->
      <template #default="{ collapsed }">
        <UDashboardSearchButton :collapsed="collapsed" class="bg-transparent ring-default" />

        <UNavigationMenu
          :collapsed="collapsed"
          :items="links[0]"
          orientation="vertical"
          tooltip
          popover
        />

        <UNavigationMenu
          :collapsed="collapsed"
          :items="links[1]"
          orientation="vertical"
          tooltip
          class="mt-auto"
        />
      </template>

      <!-- Footer — User -->
      <template #footer="{ collapsed }">
        <UserMenu :collapsed="collapsed" @logout="handleLogout" />
      </template>
    </UDashboardSidebar>

    <UDashboardSearch :groups="groups" />

    <RouterView />
  </UDashboardGroup>
</template>
