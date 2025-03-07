<script setup>
import { useTheme } from 'vuetify'
import ScrollToTop from '@core/components/ScrollToTop.vue'
import initCore from '@core/initCore'
import { initConfigStore, useConfigStore } from '@core/stores/config'
import { hexToRgb } from '@core/utils/colorConverter'
import { useRouter } from 'vue-router'
import { useCookies } from 'vue3-cookies'
import axios from 'axios'

const router = useRouter()
const { cookies } = useCookies()

const checkAuthToken = async () => {
  // Ambil token dari URL
  const urlParams = new URLSearchParams(window.location.search)
  const token = urlParams.get('token')

  if (token) {
    console.log('Token ditemukan di URL:', token)

    // Simpan token ke cookie agar bisa dipakai di request lain
    cookies.set('accessToken', token, { expires: '30d' })

    // Hapus token dari URL agar lebih bersih
    window.history.replaceState({}, document.title, window.location.pathname)

    // Set token di Axios untuk request API berikutnya
    axios.defaults.headers.common['Authorization'] = `Bearer ${token}`

    // Redirect ke dashboard
    router.push('/apps/academy/dashboard')
  } else {
    // Jika tidak ada token di URL, cek cookie
    const storedToken = cookies.get('accessToken')

    if (storedToken) {
      console.log('Token ditemukan di cookie:', storedToken)
      axios.defaults.headers.common['Authorization'] = `Bearer ${storedToken}`
      router.push('/apps/academy/dashboard')
    } else {
      console.log('Tidak ada token, tetap di halaman login')
    }
  }
}

// Jalankan saat komponen di-mount
checkAuthToken()

const { global } = useTheme()

initCore()
initConfigStore()

const configStore = useConfigStore()
</script>

<template>
  <VLocaleProvider :rtl="configStore.isAppRTL">
    <VApp :style="`--v-global-theme-primary: ${hexToRgb(global.current.value.colors.primary)}`">
      <RouterView />
      <ScrollToTop />
    </VApp>
  </VLocaleProvider>
</template>
