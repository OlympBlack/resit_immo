<script setup lang="ts">
import { ref, computed } from 'vue'
import type { DropdownMenuItem } from '@nuxt/ui'
import { useColorMode } from '@vueuse/core'
import { useAuthStore } from '../stores/authStore'

defineProps<{ collapsed?: boolean }>()
const emit = defineEmits<{ logout: [] }>()

const colorMode = useColorMode()
const authStore = useAuthStore()

const user = computed(() => ({
  name: authStore.user?.name ?? 'Utilisateur',
  email: authStore.user?.email ?? '',
  avatar: {
    alt: authStore.user?.name ?? 'U',
  },
}))

const items = computed<DropdownMenuItem[][]>(() => ([
  [{
    type: 'label',
    label: user.value.name,
    avatar: user.value.avatar,
  }],
  [{
    label: 'Apparence',
    icon: 'i-lucide-sun-moon',
    children: [{
      label: 'Clair',
      icon: 'i-lucide-sun',
      type: 'checkbox',
      checked: colorMode.value === 'light',
      onSelect(e: Event) {
        e.preventDefault()
        colorMode.value = 'light'
      },
    }, {
      label: 'Sombre',
      icon: 'i-lucide-moon',
      type: 'checkbox',
      checked: colorMode.value === 'dark',
      onSelect(e: Event) {
        e.preventDefault()
        colorMode.value = 'dark'
      },
    }],
  }],
  [{
    label: 'Se déconnecter',
    icon: 'i-lucide-log-out',
    color: 'error',
    onSelect() {
      emit('logout')
    },
  }],
]))
</script>

<template>
  <UDropdownMenu
    :items="items"
    :content="{ align: 'center', collisionPadding: 12 }"
    :ui="{ content: collapsed ? 'w-48' : 'w-(--reka-dropdown-menu-trigger-width)' }"
  >
    <UButton
      v-bind="{
        ...user,
        label: collapsed ? undefined : user?.name,
        trailingIcon: collapsed ? undefined : 'i-lucide-chevrons-up-down',
      }"
      color="neutral"
      variant="ghost"
      block
      :square="collapsed"
      class="data-[state=open]:bg-elevated"
      :ui="{ trailingIcon: 'text-dimmed' }"
    />
  </UDropdownMenu>
</template>
