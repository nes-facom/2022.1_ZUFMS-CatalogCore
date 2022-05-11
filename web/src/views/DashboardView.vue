<script setup lang="ts">
import { ref } from "vue";
import { RouterLink, RouterView } from "vue-router";
import ZUFMSLogo from "@/components/ZUFMSLogo.vue";
import MaterialIcon from "@/components/icons/MaterialIcon.vue";
import UserIndicator from "@/components/UserIndicator.vue";
import OpacityTransition from "@/components/transitions/OpacityTransition.vue";
import SlideTransition from "@/components/transitions/SlideTransition.vue";

const sidebarState = ref({});
const onSidebarStateChange = (newState: any) => {
  sidebarState.value = newState;
};

const navLinks = [
  { title: "Home", to: "/" },
  { title: "Etiquetas", to: "/etiquetas" },
  { title: "Empréstimos", to: "/emprestimos" },
  { title: "Submissão", to: "/submissao" },
];

const sidebarOnFocus = ref(true);
</script>

<template>
  <div class="page">
    <header class="navbar">
      <ZUFMSLogo />
      <nav class="navbar-links">
        <RouterLink
          v-for="navLink in navLinks"
          exactActiveClass="navbar-link-active"
          :key="navLink.title"
          :to="navLink.to"
        >
          {{ navLink.title }}
        </RouterLink>
      </nav>
      <UserIndicator
        name="Técnico"
        @avatarClick="$router.replace('/login')"
        @logout="$router.replace('/login')"
      />
    </header>
    <div class="page-body">
      <RouterView name="sidebar" v-slot="{ Component }">
        <aside
          tabindex="0"
          v-if="Component"
          :class="`sidebar-wrapper ${
            sidebarOnFocus ? 'w-1/6 overflow-y-scroll' : 'w-20 overflow-hidden'
          }`"
          @focus="sidebarOnFocus = true"
          @blur="sidebarOnFocus = false"
          @mouseleave="sidebarOnFocus = false"
          @mouseenter="sidebarOnFocus = true"
        >
          <OpacityTransition>
            <div v-if="!sidebarOnFocus" class="sidebar-overlay">
              <MaterialIcon name="chevron_right" class="text-5xl" />
            </div>
          </OpacityTransition>
          <SlideTransition>
            <component
              :is="Component"
              class="w-full"
              @stateChange="onSidebarStateChange"
            />
          </SlideTransition>
        </aside>
      </RouterView>

      <main class="dashboard-main">
        <RouterView v-slot="{ Component }">
          <component :is="Component" :sidebarState="sidebarState" />
        </RouterView>
      </main>
    </div>
  </div>
</template>

<style scoped>
.page {
  @apply bg-[#E9E9E9] flex flex-col w-full h-full;
}

.navbar {
  @apply flex w-full p-5 items-center justify-between overflow-y-scroll max-h-full;
}

.navbar-links {
  @apply flex w-[32rem] justify-between text-[1.2rem] text-[#85ABC2];
}

.navbar-link-active {
  @apply text-[#024168] underline font-semibold;
}

.sidebar-wrapper {
  @apply relative overflow-x-hidden h-full;
}

.sidebar-overlay {
  @apply absolute w-full h-full flex items-center justify-center bg-gray-200/80;
}

.page-body {
  @apply flex flex-1 w-screen min-h-0;
}

.dashboard-main {
  @apply z-[1] bg-[#024168] flex-1 w-5/6 only:rounded-t-3xl rounded-tl-3xl;
}
</style>
