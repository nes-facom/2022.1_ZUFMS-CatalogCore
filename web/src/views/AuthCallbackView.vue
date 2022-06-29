<script setup lang="ts">
import { otpRequestStorage, serviceApi } from "@/api";
import router from "@/router";
import { wait } from "@/util/async";

const query = router.currentRoute.value.query;

const state = query?.state;
const otp = query?.otp;

if (typeof state !== "string" || typeof otp !== "string") {
  throw new Error();
}

const fn = async () => {
  const email = await otpRequestStorage.get(state);

  try {
    const tokenResponse = await serviceApi.auth.authToken({
      type: "otp",
      otp_method: "email",
      email: email,
      otp: otp,
      scope: "admin",
    });

    localStorage.setItem("_at", tokenResponse.data.access_token ?? "");

    await wait(1000);

    router.replace("/");
  } catch (err) {
    console.error(err);
  }
};

fn();
</script>

<template>
  <div>Carregando</div>
</template>
