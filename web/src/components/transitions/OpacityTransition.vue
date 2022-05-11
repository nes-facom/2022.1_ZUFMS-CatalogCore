<template>
  <Transition name="opacity">
    <slot></slot>
  </Transition>
</template>

<style scoped>
.opacity-enter-active {
  transition: opacity 0.3s ease-in-out;
}
.opacity-leave-active {
  transition: transform 0.3s ease-in-out 0.3s, opacity 0.3s ease-in-out;
}
.opacity-enter-from,
.opacity-leave-to {
  transform: translateX(100px);
  opacity: 0;
}
</style>
