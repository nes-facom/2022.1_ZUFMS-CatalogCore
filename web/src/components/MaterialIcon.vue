<script setup lang="ts">
defineProps<{
  name: string;
}>();
</script>

<template>
  <span class="select-none material-icons-outlined">{{ name }}</span>
</template>
