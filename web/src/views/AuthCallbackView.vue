<script setup lang="ts">
import { otpRequestStorage, serviceApi } from "@/api";
import router from "@/router";
import { useAuthStore } from "@/store/auth";
import { wait } from "@/util/async";
import { ref } from "vue";

const query = router.currentRoute.value.query;

const state = query?.state;
const otp = query?.otp;

const loginErrorMessage = ref<string>();

if (typeof state !== "string" || typeof otp !== "string") {
  loginErrorMessage.value = "URL inválida";
}

const authStore = useAuthStore();

const loginCallback = async () => {
  const email = await otpRequestStorage.get(state?.toString() ?? "");

  await authStore.loginWithOtp(email ?? "", otp?.toString() ?? "", "admin");

  if (!authStore.error) {
    router.replace("/");
  } else {
    loginErrorMessage.value = authStore.error?.message;
  }
};

loginCallback();
</script>

<template>
  <span v-if="loginErrorMessage">{{ loginErrorMessage }}</span>
  <span v-else>Carregando</span>
</template>
