<script setup lang="ts">
import * as zufmscore from "@/util/zufmscore";
import { ref, watchEffect } from "vue";

const emit = defineEmits<{
  (e: "stateChange", newState: { section: string }): void;
}>();

const zufmsCoreSections = zufmscore.sections;

const currentSectionIndex = ref(0);

watchEffect(() => {
  emit("stateChange", {
    section: zufmsCoreSections[currentSectionIndex.value],
  });
});

const changeSelectedSection = (sectionIndex: number) => {
  currentSectionIndex.value = sectionIndex;
};
</script>

<template>
  <div class="px-5">
    <div class="w-full border-b border-gray-400 text-gray-400 mb-2">
      <h3>Seções</h3>
    </div>
    <div>
      <div
        v-for="(section, i) in zufmsCoreSections"
        :key="section"
        @click="changeSelectedSection(i)"
        :class="`transition-colors select-none py-2 px-4 cursor-pointer rounded-md border border-transparent hover:border hover:border-[#9D9D9D] text-[#5B5B5B]' ${
          currentSectionIndex === i &&
          'font-semibold bg-[#C4C4C4] border !border-[#9D9D9D] min-w-fit'
        }`"
      >
        {{ section }}
      </div>
    </div>
  </div>
</template>
