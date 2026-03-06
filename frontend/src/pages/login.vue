<route lang="yaml">
meta:
  layout: auth
</route>

<script setup lang="ts">
import { reactive } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../stores/authStore'

const router = useRouter()
const authStore = useAuthStore()

const form = reactive({
  email: '',
  password: '',
})

async function handleLogin() {
  try {
    await authStore.login(form.email, form.password)
    router.push('/')
  }
  catch {}
}
</script>

<template>
  <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-primary-950 to-primary-800 dark:from-gray-950 dark:to-gray-900 p-4">
    <div class="w-full max-w-md">
      <!-- Logo / Brand -->
      <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center size-16 rounded-2xl bg-white/10 backdrop-blur ring-1 ring-white/20 mb-4">
          <UIcon name="i-lucide-home" class="size-8 text-white" />
        </div>
        <h1 class="text-3xl font-bold text-white">
          RESIT_Immo
        </h1>
        <p class="text-white/60 mt-1 text-sm">
          Gestion locative professionnelle
        </p>
      </div>

      <!-- Carte formulaire -->
      <UCard
        class="shadow-2xl"
        :ui="{
          root: 'ring-1 ring-white/10 bg-white/5 backdrop-blur dark:bg-gray-900/80',
          body: 'p-8',
        }"
      >
        <div class="mb-6">
          <h2 class="text-xl font-semibold text-highlighted">
            Connexion
          </h2>
          <p class="text-sm text-muted mt-1">
            Entrez vos identifiants pour accéder au tableau de bord
          </p>
        </div>

        <!-- Alerte erreur -->
        <UAlert
          v-if="authStore.error"
          color="error"
          variant="soft"
          icon="i-lucide-alert-circle"
          :title="authStore.error"
          class="mb-4"
        />

        <form class="space-y-4" @submit.prevent="handleLogin">
          <!-- Email -->
          <UFormField label="Adresse email" name="email" required>
            <UInput
              v-model="form.email"
              type="email"
              placeholder="vous@exemple.fr"
              icon="i-lucide-mail"
              size="lg"
              class="w-full"
              autocomplete="email"
            />
          </UFormField>

          <!-- Mot de passe -->
          <UFormField label="Mot de passe" name="password" required>
            <UInput
              v-model="form.password"
              type="password"
              placeholder="••••••••"
              icon="i-lucide-lock"
              size="lg"
              class="w-full"
              autocomplete="current-password"
            />
          </UFormField>

          <!-- Bouton connexion -->
          <UButton
            type="submit"
            size="lg"
            block
            :loading="authStore.loading"
            icon="i-lucide-log-in"
            class="mt-2"
          >
            Se connecter
          </UButton>
        </form>

        <!-- Compte démo -->
        <div class="mt-6 pt-4 border-t border-default">
          <p class="text-xs text-muted text-center mb-3">
            Compte de démonstration
          </p>
          <div class="bg-muted/30 rounded-lg p-3 text-xs font-mono text-muted space-y-1">
            <div>📧 admin@resit-immo.fr</div>
            <div>🔑 password123</div>
          </div>
        </div>
      </UCard>
    </div>
  </div>
</template>
