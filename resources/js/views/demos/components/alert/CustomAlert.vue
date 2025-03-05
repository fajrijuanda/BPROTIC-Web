<script setup>
import { ref } from "vue";
import { VIcon } from "vuetify/components";

const show = ref(false);
const title = ref("");
const message = ref("");
const icon = ref("");
const isError = ref(false); // Menentukan apakah ini error atau tidak

const open = (alertType, alertTitle, alertMessage) => {
  show.value = true;
  title.value = alertTitle;
  message.value = alertMessage;
  isError.value = alertType === "error";

  if (alertType === "success") {
    icon.value = "tabler-check-circle"; // Ikon sukses
  } else if (alertType === "error") {
    icon.value = "tabler-alert-circle"; // Ikon error
  }

  // Jika sukses, otomatis hilang dalam 2 detik
  if (!isError.value) {
    setTimeout(() => {
      close();
    }, 2000);
  }
};

const close = () => {
  show.value = false;
};

defineExpose({ open });
</script>

<template>
  <VDialog v-model="show" width="400" transition="dialog-bottom-transition">
    <VCard class="pa-6 text-center">
      <VCardText>
        <!-- Ikon dengan warna sesuai status -->
        <VIcon :icon="icon" size="48" :class="isError ? 'text-error' : 'text-success'" />

        <!-- Judul & Pesan -->
        <h3 class="text-h5 mt-4">{{ title }}</h3>
        <p class="mt-2">{{ message }}</p>
      </VCardText>

      <!-- Tombol OK hanya muncul jika error -->
      <VCardActions v-if="isError" class="justify-center">
        <VBtn color="primary" @click="close">OK</VBtn>
      </VCardActions>
    </VCard>
  </VDialog>
</template>

<style scoped>
.text-success {
  color: #4caf50; /* Hijau untuk sukses */
}

.text-error {
  color: #f44336; /* Merah untuk error */
}
</style>
