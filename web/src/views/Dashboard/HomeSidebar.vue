<script setup lang="ts">
import { useOccurrencesStore } from "@/store/occurrences";

const occurrencesStore = useOccurrencesStore();

const changeSelectedSection = (sectionIndex: number) => {
  occurrencesStore.currentSectionIndex = sectionIndex;
  occurrencesStore.fetchOccurrences();
};
</script>

<template>
  <div>
    <div class="px-5 mb-5">
      <div class="w-full min-w-max border-b border-gray-400 text-gray-400 mb-2">
        <h3>Seções</h3>
      </div>
      <div>
        <div
          v-for="(section, i) in occurrencesStore.sections"
          :key="section"
          @click="changeSelectedSection(i)"
          :class="`transition-colors select-none py-2 px-4 cursor-pointer rounded-md border border-transparent hover:border hover:border-[#9D9D9D] text-[#5B5B5B]' ${
            occurrencesStore.currentSectionIndex === i &&
            'font-semibold bg-[#C4C4C4] border !border-[#9D9D9D] min-w-fit'
          }`"
        >
          {{ section }}
        </div>
      </div>
    </div>
    <div class="px-5">
      <div class="w-full min-w-max border-b border-gray-400 text-gray-400 mb-2">
        <h3>Outras informações</h3>
      </div>
      <div>
        <div
          @click="false"
          :class="`transition-colors select-none py-2 px-4 cursor-pointer rounded-md border border-transparent hover:border hover:border-[#9D9D9D] text-[#5B5B5B]' ${
            false &&
            'font-semibold bg-[#C4C4C4] border !border-[#9D9D9D] min-w-fit'
          }`"
        >
          Rascunho
        </div>
      </div>
    </div>
  </div>
</template>
