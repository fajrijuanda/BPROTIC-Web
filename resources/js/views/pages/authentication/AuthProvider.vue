<script setup>
import { useTheme } from 'vuetify'
import { useRouter } from 'vue-router'

const { global } = useTheme()
const router = useRouter()

const authProviders = [
  // {
  //   provider: 'facebook',
  //   icon: 'tabler-brand-facebook-filled',
  //   color: '#4267b2',
  //   colorInDark: '#497CE2',
  // },
  // {
  //   provider: 'twitter', // Twitter tidak lagi mendukung Socialite, hapus jika tidak dipakai
  //   icon: 'tabler-brand-twitter-filled',
  //   color: '#1da1f2',
  //   colorInDark: '#1da1f2',
  // },
  // {
  //   provider: 'github',
  //   icon: 'tabler-brand-github-filled',
  //   color: '#272727',
  //   colorInDark: '#fff',
  // },
  {
    provider: 'google',
    icon: 'tabler-brand-google-filled',
    color: '#dd4b39',
    colorInDark: '#db4437',
  },
]

// Fungsi untuk login dengan OAuth
const loginWithProvider = (provider) => {
  window.location.href = `http://localhost:8000/api/auth/${provider}/redirect`
}
</script>

<template>
  <div class="d-flex justify-center flex-wrap gap-1">
    <VBtn
      v-for="link in authProviders"
      :key="link.provider"
      icon
      variant="text"
      size="small"
      :color="global.name.value === 'dark' ? link.colorInDark : link.color"
      @click="loginWithProvider(link.provider)"
    >
      <VIcon size="20" :icon="link.icon" />
    </VBtn>
  </div>
</template>
