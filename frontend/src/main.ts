import './assets/css/main.css'

import { createApp } from 'vue'
import type { RouteRecordRaw } from 'vue-router'
import { createRouter, createWebHistory } from 'vue-router'
import { routes, handleHotUpdate } from 'vue-router/auto-routes'
import { setupLayouts } from 'virtual:generated-layouts'
import { createPinia } from 'pinia'
import ui from '@nuxt/ui/vue-plugin'

import App from './App.vue'

const app = createApp(App)
const pinia = createPinia()

const router = createRouter({
  routes: setupLayouts(routes as RouteRecordRaw[]),
  history: createWebHistory(),
})

// ─── Middleware de protection des routes ──────────────────────────────────────
const PUBLIC_ROUTES = ['/login']

router.beforeEach((to) => {
  const token = localStorage.getItem('auth_token')
  const isPublic = PUBLIC_ROUTES.includes(to.path)

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
