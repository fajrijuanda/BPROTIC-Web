<script setup>
import { useTheme } from 'vuetify'
import { useRouter } from 'vue-router'

const { global } = useTheme()
const router = useRouter()

const authProviders = [
  {
    provider: 'google',
    icon: 'tabler-brand-google-filled',
    color: '#dd4b39',
    colorInDark: '#db4437',
  },
]

// Fungsi untuk login dengan OAuth
const loginWithProvider = async (provider) => {
  window.location.href = `http://localhost:8000/api/auth/${provider}/redirect`;
};
</script>

<template>
  <div class="d-flex align-center justify-center gap-2">
    <p class="text-body-2 font-weight-medium mb-0">Sign Up with</p>
    <VBtn v-for="link in authProviders" :key="link.provider" icon variant="text" size="small"
      :color="global.name.value === 'dark' ? link.colorInDark : link.color" @click="loginWithProvider(link.provider)"
      class="pa-0">
      <VIcon size="22" :icon="link.icon" />
    </VBtn>
  </div>
</template>
