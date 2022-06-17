<script setup lang="ts">
import MaterialIcon from "@/components/icons/MaterialIcon.vue";

defineProps<{
  name: string;
}>();

defineEmits<{
  (e: "logout"): void;
  (e: "avatarClick"): void;
}>();
</script>

<template>
  <div class="user-indicator">
    <button class="avatar-wrapper" @click.prevent="$emit('avatarClick')">
      <MaterialIcon name="person" class="text-sm" />
    </button>
    <span class="mx-3">
      {{ $props.name }}
    </span>

    <button class="logout-wrapper" @click.prevent="$emit('logout')">
      <MaterialIcon name="logout" class="text-sm text-red-800" />
    </button>
  </div>
</template>

<style scoped>
.user-indicator {
  @apply select-none bg-[#C4C4C4] rounded-md p-2 h-full flex justify-between w-fit items-center;
}

.user-indicator > .avatar-wrapper {
  @apply bg-[#E9E9E9] h-full w-8 rounded-md flex items-center justify-center;
}

.logout-wrapper {
  @apply flex items-center;
}
</style>
