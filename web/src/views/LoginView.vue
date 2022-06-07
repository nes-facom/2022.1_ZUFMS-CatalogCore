<script setup lang="ts">
import { ref, watchEffect } from "vue";
import SequenceButton from "@/components/SequenceButton.vue";
import Button from "@/components/Button.vue";
import { otpRequestStorage, serviceApi } from "@/api";
import router from "@/router";
import { generateOtpState } from "@/util/auth";

const loginStep = ref<"email" | "code">("email");

const codeElements = ref<any[]>([]);
const currentCodeIndex = ref(1);

watchEffect(() => {
  codeElements.value[currentCodeIndex.value - 1]?.focus();
});

const email = ref<string>();

const loginStepEmailSubmit = async () => {
  const state = generateOtpState();
  const emailValue = email.value as string;

  try {
    await serviceApi.auth.authOtp({
      otp_method: "email",
      email: emailValue,
      state: state,
      scope: "occurrences:read",
    });

    await otpRequestStorage.set(state, emailValue);

    loginStep.value = "code";
  } catch (err) {
    console.error(err);
  }
};

const loginStepCodeSubmit = () => {
  serviceApi.auth
    .authToken({
      type: "otp",
      otp_method: "email",
      email: email.value,
      otp: codeElements.value.reduce((code, el) => (code += el.value), ""),
      scope: "occurrences:read",
    })
    .then((response) => {
      window.localStorage.setItem("_at", response.data.access_token ?? "");
      router.replace("/");
    })
    .catch((err) => {
      console.error(err);
    });
};
</script>

<template>
  <main class="page">
    <div class="login-card">
      <div class="card-logo">
        <img src="@/assets/zufms-logo.png" class="w-36" />
      </div>

      <div>
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
          class="mb-3 rounded-md px-7 py-3 bg-[#F2FAFF] border border-[#348F80]"
        />
        <SequenceButton type="submit">Solicitar acesso</SequenceButton>
      </form>

      <form
        v-if="loginStep === 'code'"
        class="flex flex-col pb-12"
        @submit.prevent="loginStepCodeSubmit"
      >
        <p class="mb-3 text-[#616161]">Insira o código enviado em seu e-mail</p>
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
        <Button>Entrar</Button>
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
  @apply bg-white w-2/6 h-3/6 px-16 pt-20 flex flex-col justify-between rounded-xl shadow-md relative;
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
  @apply text-center w-1/6 mb-3 rounded-md px-2 py-2 bg-[#F2FAFF] border border-[#348F80];
}

.secondary-action {
  @apply hover:decoration-solid hover:rounded-md select-none cursor-pointer flex items-center justify-center font-medium text-[#616161] underline decoration-dashed rounded-md p-3;
}
</style>
