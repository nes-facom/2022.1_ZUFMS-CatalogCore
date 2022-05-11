<script setup lang="ts">
defineProps<{
  title: string;
  description: string;
  width: string;
}>();
</script>

<template>
  <div class="mr-3" :style="{ width }">
    <h4 class="text-white text-2xl font-semibold mb-1">
      {{ title }}
    </h4>
    <p class="text-[#749FB9] text-sm">
      {{ description }}
    </p>
  </div>
</template>
