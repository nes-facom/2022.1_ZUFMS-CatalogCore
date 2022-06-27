<script setup lang="ts">
import { RouterView } from "vue-router";
import ToastContainer from "@/components/ToastContainer.vue";
</script>

<template>
  <ToastContainer />
  <RouterView />
</template>

<style>
@import "@/assets/base.css";
</style>
