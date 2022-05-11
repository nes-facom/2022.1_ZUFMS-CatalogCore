<template>
  <Transition name="slide">
    <slot></slot>
  </Transition>
</template>

<style scoped>
.slide-enter-active,
.slide-leave-active {
  transition: all 0.1s ease-in-out;
}
.slide-enter-from,
.slide-leave-to {
  transform: translateX(-100px);
  opacity: 0;
}
</style>
