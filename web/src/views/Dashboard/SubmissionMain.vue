<script setup lang="ts">
import ZUFMSSpreadsheet from "@/components/ZUFMSSpreadsheet/index.vue";
import { ref } from "vue";
import MaterialIcon from "@/components/MaterialIcon.vue";
import DashboardHeader from "./DashboardHeader.vue";
import SpreadSheet from "@/components/SpreadSheet/SpreadSheet.vue";
import SlideTransition from "@/components/transitions/SlideTransition.vue";

defineProps<{
  sidebarState: {
    section: string;
  };
}>();

const showTermclassNavigation = ref(true);
const showHeader = ref(true);
</script>

<template>
  <div :class="`${showHeader && 'pt-10'} flex flex-col w-full h-full`">
    <SlideTransition>
      <DashboardHeader
        v-if="showHeader"
        class="px-16"
        title="Nova submissão"
        subtitle="Submeta um novo registro à coleção"
      >
        <template v-slot:buttons>
          <div class="flex">
            <button
              @click="showTermclassNavigation = !showTermclassNavigation"
              :class="`${
                showTermclassNavigation
                  ? 'bg-[#52BD8F] hover:bg-[#369169]'
                  : 'bg-[#737373] hover:bg-[#787878]'
              } mr-2 w-fit transition-all hover:drop-shadow-xl drop-shadow-md p-4 rounded-full flex items-center justify-center`"
            >
              <MaterialIcon
                :name="
                  showTermclassNavigation ? 'visibility' : 'visibility_off'
                "
                class="text-white"
              />
            </button>

            <div
              class="bg-[#52BD8F] transition-all hover:drop-shadow-xl hover:bg-[#369169] drop-shadow-md p-4 rounded-full flex items-center justify-center"
            >
              <input class="w-20 h-20 opacity-0 absolute" type="file" />
              <MaterialIcon name="file_upload" class="text-white" />
            </div>
          </div>
        </template>
      </DashboardHeader>
      <div v-else>
        <div class="flex absolute top-14 right-10 z-[3]">
          <button
            @click="showTermclassNavigation = !showTermclassNavigation"
            :class="`${
              showTermclassNavigation
                ? 'bg-[#52BD8F] hover:bg-[#369169]'
                : 'bg-[#737373] hover:bg-[#787878]'
            } mr-2 w-fit transition-all hover:drop-shadow-xl drop-shadow-md p-4 rounded-full flex items-center justify-center`"
          >
            <MaterialIcon
              :name="showTermclassNavigation ? 'visibility' : 'visibility_off'"
              class="text-white"
            />
          </button>

          <div
            class="bg-[#52BD8F] transition-all hover:drop-shadow-xl hover:bg-[#369169] drop-shadow-md p-4 rounded-full flex items-center justify-center"
          >
            <input class="w-20 h-20 opacity-0 absolute" type="file" />
            <MaterialIcon name="file_upload" class="text-white" />
          </div>
        </div>
      </div>
    </SlideTransition>
    <div class="flex-1 w-full pl-16 overflow-x-scroll h-full">
      <ZUFMSSpreadsheet
        submission-mode
        :showTermclassNavigation="showTermclassNavigation"
        @scroll-y="
          () => {
            showHeader = false;
            showTermclassNavigation = false;
          }
        "
      />
    </div>
  </div>
</template>

<style scoped></style>
