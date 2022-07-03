<script setup lang="ts">
import { ref, watch, watchEffect } from "vue";
import SequenceButton from "@/components/SequenceButton.vue";
import Button from "@/components/Button.vue";
import router from "@/router";
import { useAuthStore } from "@/store/auth";

const loginStep = ref<"email" | "code">("email");

const codeElements = ref<any[]>([]);
const currentCodeIndex = ref(1);

watchEffect(() => {
  codeElements.value[currentCodeIndex.value - 1]?.focus();
});

const email = ref<string>();
const loginErrorMessage = ref<string>();

const authStore = useAuthStore();

const loginStepEmailSubmit = async () => {
  await authStore.requestOtp(email.value ?? "", "admin");

  if (!authStore.error) {
    loginStep.value = "code";
  } else {
    loginErrorMessage.value = authStore.error.message;
  }
};

const loginStepCodeSubmit = async () => {
  await authStore.loginWithOtp(
    email.value ?? "",
    codeElements.value.reduce((code, el) => (code += el.value), ""),
    "admin"
  );

  if (!authStore.error /*&& authStore.isAuthenticated*/) {
    router.replace("/");
  } else {
    loginErrorMessage.value = authStore.error.message;
  }
};

watch([email, codeElements], () => {
  loginErrorMessage.value = undefined;
});
</script>

<template>
  <main class="page">
    <div class="login-card">
      <div class="card-logo">
        <img
          src="@/assets/zufms-logo.png"
          :class="`${authStore.loading && 'animate-bounce'} w-36`"
        />
      </div>

      <div class="mb-10">
        <h4 class="card-subtitle">ZUFMS</h4>
        <h2 class="card-title">Acessar a Coleção Zoológica</h2>
      </div>

      <form
        v-if="loginStep === 'email'"
        class="flex flex-col pb-20"
        @submit.prevent="loginStepEmailSubmit"
      >
        <input
          type="email"
          placeholder="Digite seu e-mail"
          required
          v-model="email"
          class="rounded-md px-7 py-3 bg-[#F2FAFF] border border-[#348F80]"
        />
        <span class="text-red-400 text-sm" v-if="loginErrorMessage">{{
          loginErrorMessage
        }}</span>

        <SequenceButton type="submit" class="mt-4"
          >Solicitar acesso</SequenceButton
        >
      </form>

      <form
        v-if="loginStep === 'code'"
        class="flex flex-col pb-12"
        @submit.prevent="loginStepCodeSubmit"
      >
        <p class="text-[#616161]">Insira o código enviado em seu e-mail</p>
        <div class="flex justify-between">
          <input
            v-for="i in 5"
            :key="'code_' + i"
            ref="codeElements"
            type="text"
            :autofocus="i === 1"
            required
            @focus="(el) => (el.target as any)?.select()"
            @keypress="currentCodeIndex = i + 1"
            maxlength="1"
            placeholder="0"
            class="code-input"
          />
        </div>
        <span class="text-red-400 text-sm" v-if="loginErrorMessage">{{
          loginErrorMessage
        }}</span>

        <Button class="mt-3">Entrar</Button>
        <a @click="loginStep = 'email'" class="secondary-action">
          Inserir outro e-mail
        </a>
      </form>
    </div>
  </main>
</template>

<style scoped>
.page {
  @apply bg-[#024168] w-full h-full flex items-center justify-center;
}

.login-card {
  @apply bg-white max-w-xl w-full mx-0 my-10 sm:mx-16 lg:mx-0 xl:w-2/6 px-16 pt-20 flex flex-col justify-between rounded-xl shadow-md relative;
}

.card-logo {
  @apply absolute -right-10 -top-10;
}

.card-subtitle {
  @apply font-['Bebas_Neue'] text-gray-300 text-xl;
}

.card-title {
  @apply text-3xl font-semibold mr-4;
}

.code-input {
  @apply text-center w-1/6  rounded-md px-2 py-2 bg-[#F2FAFF] border border-[#348F80];
}

.secondary-action {
  @apply hover:decoration-solid hover:rounded-md select-none cursor-pointer flex items-center justify-center font-medium text-[#616161] underline decoration-dashed rounded-md p-3;
}
</style>
