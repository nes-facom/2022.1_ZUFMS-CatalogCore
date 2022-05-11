<script setup lang="ts">
import ZUFMSSpreadsheet from "@/components/ZUFMSSpreadsheet/index.vue";
import { ref } from "vue";
import MaterialIcon from "../icons/MaterialIcon.vue";
import DashboardHeader from "./DashboardHeader.vue";

defineProps<{
  sidebarState: {
    section: string;
  };
}>();

const showHeader = ref(true);

const onScrollY = (event: Event) => {
  const { scrollTop = 0, scrollHeight = 0 } = (event.target as any) ?? {};
  showHeader.value = scrollTop + scrollHeight <= 0;
};

const dropfile = ref(false);

const onDragOver = (event: DragEvent) => {
  console.log("isFile", event.dataTransfer?.types.indexOf("Files") !== -1);
  if (!dropfile.value) {
    dropfile.value = true;
  }
};

const onDragLeave = () => {
  if (dropfile.value) {
    dropfile.value = false;
  }
};

const onDrop = onDragLeave;
</script>

<template>
  <div
    :class="`flex flex-col w-full h-full pt-10 ${
      dropfile && 'disable-pointer-events'
    }`"
    @dragover.prevent="onDragOver"
    @dragleave.prevent="onDragLeave"
    @drop.prevent="onDrop"
  >
    <Transition name="slide">
      <DashboardHeader
        v-if="showHeader"
        class="px-16"
        title="Nova submissão"
        subtitle="Submeta um novo registro à coleção"
      >
        <template v-slot:buttons>
          <div
            class="bg-[#52BD8F] transition-all hover:drop-shadow-xl hover:bg-[#369169] drop-shadow-md p-4 rounded-full flex items-center justify-center"
          >
            <input class="w-20 h-20 opacity-0 absolute" type="file" />
            <MaterialIcon name="file_upload" class="text-white" />
          </div>
        </template>
      </DashboardHeader>
    </Transition>
    <div class="flex-1 w-full pl-16 overflow-x-scroll h-full">
      <ZUFMSSpreadsheet submission-mode @scroll-y="onScrollY" />
    </div>

    <div
      v-if="dropfile"
      class="z-[2] fixed top-0 left-0 w-full h-full bg-black/40 border-4 border-dashed border-[#52BD8F] flex items-center justify-center"
    >
      <div class="p-4 bg-white rounded-lg animate-wiggle">
        <div
          class="flex flex-col justify-center bg-white border-2 border-dashed border-[#52BD8F] p-10 rounded-lg"
        >
          <MaterialIcon
            name="upload"
            class="text-gray-700 text-center text-4xl mb-2"
          />
          <h3 class="text-2xl text-gray-800 font-semibold">
            Importar arquivo .csv
          </h3>
          <p class="text-[#99BBD0]">Solte o arquivo .csv importar</p>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.disable-pointer-events * {
  pointer-events: none;
}

.slide-enter-active,
.slide-leave-active {
  transition: all 0.1s ease-in-out;
}
.slide-enter-from,
.slide-leave-to {
  transform: translateY(-100px);
  opacity: 0;
}
</style>
