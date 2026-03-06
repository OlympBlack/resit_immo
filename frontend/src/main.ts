import './assets/css/main.css'

import { createApp } from 'vue'
import type { RouteRecordRaw } from 'vue-router'
import { createRouter, createWebHistory } from 'vue-router'
import { routes, handleHotUpdate } from 'vue-router/auto-routes'
import { setupLayouts } from 'virtual:generated-layouts'
import { createPinia } from 'pinia'
import ui from '@nuxt/ui/vue-plugin'
import { useAuthStore } from './stores/authStore'

import App from './App.vue'

const app = createApp(App)
const pinia = createPinia()

const router = createRouter({
  routes: setupLayouts(routes as RouteRecordRaw[]),
  history: createWebHistory(),
})

// ─── Middleware de protection des routes ──────────────────────────────────────
router.beforeEach(async (to) => {
  const authStore = useAuthStore(pinia)
  const token = authStore.token
  const isPublic = ['/login'].includes(to.path)

  // Si on a un token mais pas d'utilisateur, on essaie de le charger
  if (token && !authStore.user && !isPublic) {
    try {
      await authStore.fetchMe()
    } catch {
      return { path: '/login' }
    }
  }

  if (!token && !isPublic) {
    return { path: '/login' }
  }

  if (token && to.path === '/login') {
    return { path: '/' }
  }
})

app.use(pinia)
app.use(router)
app.use(ui)

app.mount('#app')

// Hot update des routes en développement
if (import.meta.hot) {
  handleHotUpdate(router)
}
