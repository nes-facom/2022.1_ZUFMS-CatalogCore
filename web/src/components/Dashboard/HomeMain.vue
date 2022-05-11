<script setup lang="ts">
import ZUFMSSpreadsheet from "@/components/ZUFMSSpreadsheet/index.vue";
import { ref, watchEffect } from "vue";
import DashboardHeader from "./DashboardHeader.vue";

const props = defineProps<{
  sidebarState: {
    section: string;
  };
}>();

const showHeader = ref(true);

watchEffect(() => console.log(props.sidebarState.section));
</script>

<template>
  <div class="flex flex-col w-full h-full pt-10">
    <DashboardHeader
      v-if="showHeader"
      class="mb-10 px-16"
      title="Todos os registros"
      subtitle="Listagem de todos os registros cadastrados"
    />
    <div class="flex-1 w-full pl-16 overflow-x-scroll">
      <ZUFMSSpreadsheet :omit-terms="['artificial:section']" />
    </div>
  </div>
</template>
