<script setup>
import { useGenerateImageVariant } from '@core/composable/useGenerateImageVariant'
import { VNodeRenderer } from '@layouts/components/VNodeRenderer'
import { themeConfig } from '@themeConfig'
import registerMultiStepIllustrationDark from '@images/illustrations/register-multi-step-illustration-dark.png'
import registerMultiStepIllustrationLight from '@images/illustrations/register-multi-step-illustration-light.png'
import registerMultiStepBgDark from '@images/pages/register-multi-step-bg-dark.png'
import registerMultiStepBgLight from '@images/pages/register-multi-step-bg-light.png'
import { ref, computed, onMounted } from 'vue'
import axios from 'axios'
// import Swal from "sweetalert2/dist/sweetalert2.js";

const registerMultiStepBg = useGenerateImageVariant(registerMultiStepBgLight, registerMultiStepBgDark)
const Swal = (await import("sweetalert2")).default;

definePage({
  meta: {
    layout: 'blank',
    public: true,
  },
})

const currentStep = ref(0)
const isPasswordVisible = ref(false)
const isConfirmPasswordVisible = ref(false)
const registerMultiStepIllustration = useGenerateImageVariant(registerMultiStepIllustrationLight, registerMultiStepIllustrationDark)
const majors = ref([])
const stepErrors = ref({
  firstName: "",
  lastName: "",
  nim: "",
  class: "",
  major_id: "",
  mobile: "",
});


// Fetch data jurusan dari API saat komponen dimuat
onMounted(async () => {
  try {
    const response = await axios.get('/api/majors')

    // Pastikan response data adalah array
    if (Array.isArray(response.data)) {
      majors.value = response.data
    } else {
      console.error('Invalid API response format:', response.data)
    }
  } catch (error) {
    console.error('Error fetching majors:', error)
  }
})



const radioContent = [
  {
    title: "Internet of Things",
    desc: "Build smart solutions with IoT technology.",
    value: "1", // Sesuaikan dengan ID di database
    icon: "tabler-device-laptop",
  },
  {
    title: "Software",
    desc: "Develop powerful applications for various needs.",
    value: "2", // Sesuaikan dengan ID di database
    icon: "tabler-code",
  },
  {
    title: "Game",
    desc: "Create engaging and interactive games.",
    value: "3", // Sesuaikan dengan ID di database
    icon: "tabler-device-gamepad",
  },
];


const softwareOptions = [
  { title: 'PHP Native', value: 1 },
  { title: 'Laravel', value: 2 },
  { title: 'Ionic', value: 3 },
  { title: 'UI/UX', value: 4 },
];


const showSoftwareOptions = computed(() => form.value.selectedInterest === '2')

const items = [
  {
    title: 'Account',
    subtitle: 'Account Details',
    icon: 'tabler-file-analytics',
  },
  {
    title: 'Personal',
    subtitle: 'Enter Information',
    icon: 'tabler-user',
  },
  {
    title: 'Interest',
    subtitle: 'Select Interest',
    icon: 'tabler-book',
  },
]

const form = ref({
  username: "",
  email: "",
  password: "",
  confirmPassword: "",
  firstName: "",
  lastName: "",
  mobile: "",
  class: "",
  major_id: null,
  nim: "",
  selectedInterest: null, // Harus null agar tidak mengirimkan "0"
  reason: "",
  selectedSoftware: null, // Jika tidak ada sub interest, biarkan null
});

const rulesUsername = [
  v => !!v || 'Username wajib diisi',
  v => v.length <= 15 || 'Max 15 characters'
]

const rulesEmail = [
  v => !!v || 'Email wajib diisi',
  v => /.+@.+\..+/.test(v) || 'Format email tidak valid',
];


const rulesPassword = [
  v => !!v || 'Password wajib diisi',
  v => (v && v.length >= 8) || 'Password minimal 8 karakter',
  v => /[A-Z]/.test(v) || 'Minimal 1 huruf besar',
  v => /[a-z]/.test(v) || 'Minimal 1 huruf kecil',
  v => /\d/.test(v) || 'Minimal 1 angka',
  v => /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(v) || 'Minimal 1 simbol',
];

const rulesConfirmPassword = [
  v => !!v || 'Konfirmasi password wajib diisi',
  v => (v && v.length >= 8) || 'Password minimal 8 karakter',
  v => v === form.value.password || 'Konfirmasi password tidak cocok',
];

const rulesFirstName = [
  v => !!v || 'First Name wajib diisi',
  v => v.length >= 2 || 'Minimal 2 karakter',
  v => /^[a-zA-Z\s]+$/.test(v) || 'Hanya boleh huruf dan spasi',
];

const rulesLastName = [
  v => !!v || 'Last Name wajib diisi',
  v => v.length >= 2 || 'Minimal 2 karakter',
  v => /^[a-zA-Z\s]+$/.test(v) || 'Hanya boleh huruf dan spasi',
];

const rulesNIM = [
  v => !!v || 'NIM wajib diisi',
  v => /^\d+$/.test(v) || 'NIM harus berupa angka',
  v => v.length === 14 || 'NIM harus tepat 14 digit',
];

const rulesClass = [
  v => !!v || 'Class wajib diisi',
  v => /^[a-zA-Z0-9]+$/.test(v) || 'Class hanya boleh huruf dan angka',
];

const rulesMajor = [
  v => !!v || 'Major wajib dipilih',
];

const rulesMobile = [
  v => !!v || 'Mobile wajib diisi',
  v => /^\d+$/.test(v) || 'Nomor HP harus berupa angka',
  v => v.length >= 10 && v.length <= 13 || 'Nomor HP harus 10-13 digit',
];

const rulesReason = [
  v => !!v || 'Reason wajib diisi',
  v => v.length >= 10 || 'Reason minimal 10 karakter',
  v => v.length <= 255 || 'Reason maksimal 255 karakter',
];


// Validasi password
const passwordErrors = computed(() => {
  if (!form.value.password) return []
  const errors = []
  if (!/[a-z]/.test(form.value.password)) errors.push('Minimal 1 huruf kecil')
  if (!/[A-Z]/.test(form.value.password)) errors.push('Minimal 1 huruf besar')
  if (!/\d/.test(form.value.password)) errors.push('Minimal 1 angka')
  if (!/[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(form.value.password)) errors.push('Minimal 1 simbol')
  return errors
})

// Validasi konfirmasi password
const confirmPasswordErrors = computed(() => {
  if (!form.value.confirmPassword) return []
  if (form.value.password !== form.value.confirmPassword) return ['Password tidak cocok']
  return []
})

const isStepValid = stepIndex => {
  switch (stepIndex) {
    case 0:
      return (
        form.value.username &&
        form.value.email &&
        form.value.password &&
        form.value.confirmPassword &&
        passwordErrors.value.length === 0 && // Pastikan password memenuhi aturan
        confirmPasswordErrors.value.length === 0 // Pastikan confirm password cocok
      )
    case 1:
      return (
        form.value.firstName &&
        form.value.lastName &&
        form.value.mobile &&
        form.value.class &&
        form.value.major_id &&
        form.value.nim
      )
    case 2:
      if (form.value.selectedInterest === '99') {
        return form.value.selectedSoftware !== null && form.value.reason
      }
      return form.value.reason
    default:
      return false
  }
}


const validateStep = () => {
  stepErrors.value = {} // Reset error sebelum validasi ulang

  if (currentStep.value === 0) {
    // Validasi Username
    if (!form.value.username) {
      stepErrors.value.username = "Username wajib diisi"
    } else if (form.value.username.length > 15) {
      stepErrors.value.username = "Username maksimal 15 karakter"
    }

    // Validasi Email
    if (!form.value.email) {
      stepErrors.value.email = "Email wajib diisi"
    } else if (!form.value.email.includes("@")) {
      stepErrors.value.email = "Email harus mengandung @"
    }

    // Validasi Password
    if (!form.value.password) stepErrors.value.password = "Password wajib diisi"
    if (!form.value.confirmPassword) stepErrors.value.confirmPassword = "Konfirmasi password wajib diisi"

    // Cek aturan password
    if (passwordErrors.value.length > 0) stepErrors.value.password = passwordErrors.value.join(", ")
    if (confirmPasswordErrors.value.length > 0) stepErrors.value.confirmPassword = confirmPasswordErrors.value.join(", ")

    // Jika ada error, tidak lanjut ke step berikutnya
    if (Object.keys(stepErrors.value).length > 0) return
  }

  if (currentStep.value === 1) {
    if (!form.value.firstName) stepErrors.value.firstName = "Nama depan wajib diisi";
    if (!form.value.lastName) stepErrors.value.lastName = "Nama belakang wajib diisi";
    if (!form.value.nim) stepErrors.value.nim = "NIM wajib diisi";
    else if (!/^\d+$/.test(form.value.nim)) stepErrors.value.nim = "NIM harus berupa angka";

    if (!form.value.class) stepErrors.value.class = "Kelas wajib diisi";
    if (!form.value.major_id) stepErrors.value.major_id = "Jurusan wajib dipilih";
    if (!form.value.mobile) stepErrors.value.mobile = "Nomor HP wajib diisi";

    if (Object.keys(stepErrors.value).length > 0) return;
  }


  if (currentStep.value === 2) {
    // Validasi step 2 (Interest)
    if (!form.value.reason) stepErrors.value.reason = "Alasan wajib diisi"
    if (form.value.selectedInterest === '99' && !form.value.selectedSoftware) {
      stepErrors.value.selectedSoftware = "Pilih software yang diinginkan"
    }
    if (form.value.selectedSoftware) {
      requestData.sub_interest_id = form.value.selectedSoftware; // Simpan ID, bukan nama
    }
    if (Object.keys(stepErrors.value).length > 0) return
  }

  // Jika validasi lolos, lanjut ke step berikutnya
  currentStep.value++
}


const formErrors = ref({}); // Menyimpan error dari backend

// const alertRef = ref(null);

const onSubmit = async () => {
  try {
    const requestData = {
      username: form.value.username,
      email: form.value.email,
      password: form.value.password,
      password_confirmation: form.value.confirmPassword,
      firstName: form.value.firstName,
      lastName: form.value.lastName,
      mobile: form.value.mobile,
      class: form.value.class,
      major_id: form.value.major_id,
      nim: form.value.nim,
      interest_id: form.value.selectedInterest,
      sub_interest_id: form.value.selectedSoftware,
      reason: form.value.reason,
      role: "Student",
    };

    const response = await axios.post("/api/register", requestData, {
      headers: { "Content-Type": "application/json" },
    });

    if (response.data.success) {
      // ✅ SweetAlert sukses tanpa tombol OK, auto-close dalam 2 detik
      Swal.fire({
        title: "Registrasi Berhasil!",
        icon: "success",
        timer: 2000,
        showConfirmButton: false,
        allowOutsideClick: false,
      });

      // ⏳ Redirect ke halaman login setelah 2 detik
      setTimeout(() => {
        window.location.href = "/login";
      }, 2000);
    }
  } catch (error) {
    console.error("Error response:", error.response?.data || error.message);

    if (error.response && error.response.status === 422) {
      formErrors.value = error.response.data.errors;
      const errorMessages = Object.values(error.response.data.errors).flat().join("\n");

      // ❌ SweetAlert error dengan tombol OK
      Swal.fire({
        title: "Registrasi Gagal!",
        text: errorMessages,
        icon: "error",
        confirmButtonText: "OK",
      });
    } else {
      // ❌ SweetAlert error jika terjadi kesalahan sistem
      Swal.fire({
        title: "Terjadi Kesalahan!",
        text: error.response?.data?.message || "Gagal melakukan registrasi.",
        icon: "error",
        confirmButtonText: "OK",
      });
    }
  }
};

</script>

<template>
  <RouterLink to="/">
    <div class="auth-logo d-flex align-center gap-x-3">
      <VNodeRenderer :nodes="themeConfig.app.logo" />
      <h1 class="auth-title">
        {{ themeConfig.app.title }}
      </h1>
    </div>
  </RouterLink>

  <VRow no-gutters class="auth-wrapper">
    <VCol md="4" class="d-none d-md-flex">
      <div class="d-flex justify-center align-center w-100 position-relative">
        <VImg :src="registerMultiStepIllustration" class="illustration-image flip-in-rtl" />
        <img class="bg-image position-absolute w-100 flip-in-rtl" :src="registerMultiStepBg"
          alt="register-multi-step-bg" height="340">
      </div>
    </VCol>

    <VCol cols="12" md="8" class="auth-card-v2 d-flex align-center justify-center pa-10"
      style="background-color: rgb(var(--v-theme-surface));">
      <CustomAlert ref="alertRef" />
      <VCard flat class="mt-12 mt-sm-10">
        <!-- Stepper dengan validasi klik -->
        <AppStepper v-model:current-step="currentStep" :items="items" :readonly="!isStepValid(currentStep)"
          :direction="$vuetify.display.smAndUp ? 'horizontal' : 'vertical'" icon-size="22"
          class="stepper-icon-step-bg mb-12" />

        <VWindow v-model="currentStep" class="disable-tab-transition" style="max-inline-size: 681px;">
          <VForm>
            <VWindowItem>
              <h4 class="text-h4">
                Account Information
              </h4>
              <p class="text-body-1 mb-6">
                Enter Your Account Details
              </p>

              <VRow>
                <VCol cols="12" md="6">
                  <AppTextField v-model="form.username" label="Username" placeholder="Johndoe"
                    :error-messages="stepErrors.username" :rules="rulesUsername" counter="15"
                    prepend-inner-icon="tabler-user" />
                </VCol>

                <VCol cols="12" md="6">
                  <AppTextField v-model="form.email" label="Email" placeholder="johndoe@email.com"
                    :error-messages="stepErrors.email" :rules="rulesEmail" prepend-inner-icon="tabler-mail" />
                </VCol>

                <VCol cols="12" md="6">
                  <AppTextField v-model="form.password" label="Password" placeholder="············"
                    :type="isPasswordVisible ? 'text' : 'password'"
                    :append-inner-icon="isPasswordVisible ? 'tabler-eye-off' : 'tabler-eye'"
                    @click:append-inner="isPasswordVisible = !isPasswordVisible" :error-messages="stepErrors.password"
                    :rules="rulesPassword" counter prepend-inner-icon="tabler-lock" />
                </VCol>

                <VCol cols="12" md="6">
                  <AppTextField v-model="form.confirmPassword" label="Confirm Password" placeholder="············"
                    :type="isConfirmPasswordVisible ? 'text' : 'password'"
                    :append-inner-icon="isConfirmPasswordVisible ? 'tabler-eye-off' : 'tabler-eye'"
                    @click:append-inner="isConfirmPasswordVisible = !isConfirmPasswordVisible"
                    :error-messages="stepErrors.confirmPassword" :rules="rulesConfirmPassword" counter
                    prepend-inner-icon="tabler-lock" />
                </VCol>
              </VRow>

            </VWindowItem>

            <VWindowItem>
              <h4 class="text-h4">Personal Information</h4>
              <p>Enter Your Personal Information</p>

              <VRow>
                <VCol cols="12" md="6">
                  <AppTextField v-model="form.firstName" label="First Name" placeholder="John"
                    :error-messages="stepErrors.firstName" :rules="rulesFirstName" prepend-inner-icon="tabler-user" />
                </VCol>

                <VCol cols="12" md="6">
                  <AppTextField v-model="form.lastName" label="Last Name" placeholder="Doe"
                    :error-messages="stepErrors.lastName" :rules="rulesLastName" prepend-inner-icon="tabler-user" />
                </VCol>

                <VCol cols="12" md="6">
                  <AppTextField v-model="form.nim" label="NIM" type="number" placeholder="Enter your NIM"
                    :error-messages="stepErrors.nim" :rules="rulesNIM" prepend-inner-icon="tabler-id" />
                </VCol>

                <VCol cols="12" md="6">
                  <AppTextField v-model="form.class" type="text" label="Class" placeholder="Example: IF22A"
                    :error-messages="stepErrors.class" :rules="rulesClass" prepend-inner-icon="tabler-school" />
                </VCol>

                <VCol cols="12" md="6">
                  <AppSelect v-model="form.major_id" label="Major" :items="majors.length ? majors : []"
                    item-title="name" item-value="id" placeholder="Select Major" :error-messages="stepErrors.major_id"
                    :rules="rulesMajor" prepend-inner-icon="tabler-book" />
                </VCol>

                <VCol cols="12" md="6">
                  <AppTextField v-model="form.mobile" type="number" label="Mobile" placeholder="08XX-XXXX-XXXX"
                    :error-messages="stepErrors.mobile" :rules="rulesMobile" prepend-inner-icon="tabler-phone" />
                </VCol>
              </VRow>


            </VWindowItem>

            <VWindowItem>
              <h4 class="text-h4">Select Interest</h4>
              <p class="text-body-1 mb-5">Select an interest that best suits your goal</p>

              <CustomRadiosWithIcon v-model:selected-radio="form.selectedInterest" :radio-content="radioContent"
                :grid-column="{ sm: '4', cols: '12' }">
                <template #default="{ item }">
                  <div class="text-center">
                    <VIcon :icon="item.icon" size="40" class="mb-2 text-primary" />
                    <h5 class="text-h5 mb-2">{{ item.title }}</h5>
                    <p class="clamp-text mb-2">{{ item.desc }}</p>
                  </div>
                </template>
              </CustomRadiosWithIcon>

              <VRow v-if="showSoftwareOptions" class="mt-4">
                <VCol v-for="option in softwareOptions" :key="option.value" cols="12" sm="3">
                  <VCard class="text-center"
                    :class="{ 'bg-primary text-white': form.selectedSoftware === option.value }" color="grey-lighten-3"
                    variant="outlined" @click="form.selectedSoftware = option.value">
                    <VCardText>
                      <h4
                        :class="{ 'text-white': form.selectedSoftware === option.value, 'text-black': form.selectedSoftware !== option.value }">
                        {{ option.title }}
                      </h4>
                    </VCardText>
                  </VCard>
                </VCol>
              </VRow>

              <h4 class="text-h4 mt-12">Interest Information</h4>
              <p class="text-body-1 mb-6">Enter additional details</p>

              <VRow>
                <VCol cols="12">
                  <AppTextField v-model="form.reason" type="text" label="Reason" placeholder="Type your reason"
                    :error-messages="stepErrors.reason" :rules="rulesReason" prepend-inner-icon="tabler-message" clearable />
                </VCol>
              </VRow>
            </VWindowItem>
          </VForm>
        </VWindow>

        <div class="d-flex flex-wrap justify-space-between gap-x-4 mt-6">
          <VBtn color="secondary" :disabled="currentStep === 0" variant="tonal" @click="currentStep--">
            <VIcon icon="tabler-arrow-left" start class="flip-in-rtl" />
            Previous
          </VBtn>

          <VBtn v-if="items.length - 1 === currentStep" color="success" @click="onSubmit">
            Submit
          </VBtn>

          <VBtn v-else @click="validateStep">
            Next
            <VIcon icon="tabler-arrow-right" end class="flip-in-rtl" />
          </VBtn>
        </div>
      </VCard>
    </VCol>
  </VRow>
</template>

<style lang="scss">
@use "@core-scss/template/pages/page-auth.scss";

.illustration-image {
  block-size: 550px;
  inline-size: 248px;
}

.bg-image {
  inset-block-end: 0;
}
</style>
