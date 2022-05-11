<script setup lang="ts">
import MaterialIcon from "../icons/MaterialIcon.vue";

const props = defineProps<{
  title: string;
  subtitle: string;
}>();
</script>

<template>
  <header class="w-full min-w-fit flex pt-5 pb-10 border-b-2 border-[#2E688C]">
    <div class="flex-1">
      <h3 class="text-white text-3xl font-semibold">
        {{ props.title }}
      </h3>
      <p class="text-[#99BBD0] text-lg">
        {{ props.subtitle }}
      </p>
    </div>
    <div class="">
      <slot name="buttons"></slot>
    </div>
  </header>
</template>
