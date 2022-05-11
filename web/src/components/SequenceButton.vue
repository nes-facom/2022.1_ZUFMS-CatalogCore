<script lang="ts" setup>
import MaterialIcon from "@/components/icons/MaterialIcon.vue";
</script>

<template>
  <button
    class="hover:bg-[#024168]/95 hover:pr-4 hover:shadow-xl hover:scale-105 transition-all flex items-center justify-between bg-[#024168] font-semibold text-white rounded-md px-7 py-3"
  >
    <slot></slot>
    <slot name="icon">
      <MaterialIcon name="east" class="text-[1.2rem]" />
    </slot>
  </button>
</template>
