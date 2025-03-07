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
import { useRouter } from 'vue-router';
import {
  requiredValidator,
  regexValidator,
  integerValidator,
  lengthValidator,
} from '@core/utils/validators'
import { useCookies } from "vue3-cookies";

const registerMultiStepBg = useGenerateImageVariant(registerMultiStepBgLight, registerMultiStepBgDark)
const Swal = (await import("sweetalert2")).default;

const { cookies } = useCookies();
const router = useRouter();
definePage({
  meta: {
    layout: 'blank',
    public: true,
  },
})

const currentStep = ref(0)
const registerMultiStepIllustration = useGenerateImageVariant(registerMultiStepIllustrationLight, registerMultiStepIllustrationDark)
const majors = ref([])
const errors = ref({
  username: [],
  email: [],
  password: [],
  confirmPassword: [],
  firstName: [],
  lastName: [],
  nim: [],
  class: [],
  major_id: [],
  mobile: [],
  reason: [],
});

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
  class: "",
  major_id: null,
  nim: "",
  mobile: "",
  selectedInterest: null,
  reason: "",
  selectedSoftware: null, // 
});

const rulesFirstName = [
  requiredValidator,
  value => regexValidator(value, '^[a-zA-Z\\s]{2,}$') || 'Hanya huruf & spasi (min 2 karakter)'
]

const rulesLastName = [
  requiredValidator,
  value => regexValidator(value, '^[a-zA-Z\\s]{2,}$') || 'Hanya huruf & spasi (min 2 karakter)'
]

const rulesNIM = [
  requiredValidator,
  integerValidator,
  value => lengthValidator(value, 14) || 'Harus tepat 14 digit'
]

const rulesClass = [
  requiredValidator,
  value => regexValidator(value, '^[a-zA-Z0-9]+$') || 'Hanya huruf dan angka'
]

const rulesMajor = [
  requiredValidator
]

const rulesMobile = [
  requiredValidator,
  value => regexValidator(value, '^\\d{10,13}$') || 'Harus 10-13 digit angka'
]

const rulesReason = [
  requiredValidator,
  value => regexValidator(value, '^.{10,255}$') || 'Min 10 & max 255 karakter'
]

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

const refStepForm = ref(null) // Tambahkan ref untuk VForm

const validateStep = async () => {
  const { valid } = await refStepForm.value.validate()

  if (valid) currentStep.value++
}

// Fetch data jurusan dari API saat komponen dimuat
onMounted(async () => {
  try {
    const response = await axios.get('/api/majors');

    // Pastikan response data adalah array sebelum memasukkan ke `majors.value`
    if (Array.isArray(response.data)) {
      majors.value = response.data;
    } else {
      console.error('Invalid API response format:', response.data);
    }
  } catch (error) {
    console.error('❌ Error fetching majors:', error);
  }

  const urlParams = new URLSearchParams(window.location.search);
  const username = urlParams.get("username");
  const email = urlParams.get("email");

  if (username && email) {
    localStorage.setItem("socialUserData", JSON.stringify({ username, email }));
  }

  // Ambil dari localStorage jika tidak ada di URL
  const savedData = JSON.parse(localStorage.getItem("socialUserData") || "{}");
  if (savedData.username && savedData.email) {
    form.value.username = savedData.username;
    form.value.email = savedData.email;
  }

});

const onSubmit = async () => {
  try {
    console.log("📌 Data sebelum dikirim:", form.value);

    if (!form.value.username || !form.value.email) {
      throw new Error("❌ Username dan Email harus diisi!");
    }

    const requestData = {
      username: form.value.username,
      email: form.value.email,
      firstName: form.value.firstName,
      lastName: form.value.lastName,
      mobile: form.value.mobile,
      class: form.value.class,
      major_id: form.value.major_id,
      nim: form.value.nim,
      interest_id: form.value.selectedInterest,
      sub_interest_id: form.value.selectedSoftware,
      reason: form.value.reason,
    };

    console.log("📤 Data yang dikirim ke API:", requestData);

    const response = await axios.post("/api/register", requestData, {
      headers: { "Content-Type": "application/json" },
    });

    if (response.data.success) {
      cookies.set("accessToken", response.data.token, "1h");
      cookies.set("userData", JSON.stringify({ ...requestData, role: "client" }), "1h");

      console.log("✅ Registrasi berhasil, redirect ke:", response.data.redirect);
      await router.replace(response.data.redirect); // ✅ Gunakan router yang sudah didefinisikan
    }
  } catch (error) {
    let errorMessage = error.response?.data?.message || "Terjadi kesalahan";

    if (error.response?.status === 422) {
      const errorDetails = Object.values(error.response.data.errors).flat().join("\n");
      errorMessage = `Validasi gagal:\n${errorDetails}`;
    }

    Swal.fire({
      title: "Registrasi Gagal!",
      text: errorMessage,
      icon: "error",
      confirmButtonText: "OK",
    });

    console.error("❌ Error saat registrasi:", error);
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
          <VForm ref="refStepForm">
            <VWindowItem>
              <h4 class="text-h4">Personal Information</h4>
              <p>Enter Your Personal Information</p>

              <VRow>
                <VCol cols="12" md="6">
                  <AppTextField v-model="form.firstName" label="First Name" placeholder="John"
                    :error-messages="errors.firstName" :rules="rulesFirstName" prepend-inner-icon="tabler-user" />
                </VCol>

                <VCol cols="12" md="6">
                  <AppTextField v-model="form.lastName" label="Last Name" placeholder="Doe"
                    :error-messages="errors.lastName" :rules="rulesLastName" prepend-inner-icon="tabler-user" />
                </VCol>

                <VCol cols="12" md="6">
                  <AppTextField v-model="form.nim" label="NIM" type="number" placeholder="Enter your NIM"
                    :error-messages="errors.nim" :rules="rulesNIM" prepend-inner-icon="tabler-id" />
                </VCol>

                <VCol cols="12" md="6">
                  <AppTextField v-model="form.class" type="text" label="Class" placeholder="Example: IF22A"
                    :error-messages="errors.class" :rules="rulesClass" prepend-inner-icon="tabler-school" />
                </VCol>

                <VCol cols="12" md="6">
                  <AppSelect v-model="form.major_id" label="Major" :items="majors.length ? majors : []"
                    item-title="name" item-value="id" placeholder="Select Major" :error-messages="errors.major_id"
                    :rules="rulesMajor" prepend-inner-icon="tabler-book" />
                </VCol>

                <VCol cols="12" md="6">
                  <AppTextField v-model="form.mobile" type="text" label="Mobile" placeholder="08XX-XXXX-XXXX"
                    :error-messages="errors.mobile" :rules="rulesMobile" prepend-inner-icon="tabler-phone" />
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
                    :error-message="errors.reason" :rules="rulesReason" prepend-inner-icon="tabler-message" clearable />
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
