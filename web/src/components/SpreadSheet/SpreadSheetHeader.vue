<script setup lang="ts">
import { termsList, useOccurrencesStore } from "@/store/occurrences";
import type { ZUFMSCore } from "@/store/occurrences";

const props = defineProps<{
  omitTerms?: (keyof ZUFMSCore)[];
}>();

const width = "20rem";

const occurrencesStore = useOccurrencesStore();

const toggleSelection = (term: keyof ZUFMSCore) => () =>
  occurrencesStore.toggleTermSelection(term);
</script>

<template>
  <header class="flex mt-8 mb-5 w-max h-full cursor-pointer select-none">
    <div
      :class="`pr-3 border-2 border-transparent ${
        occurrencesStore.selectedTerms[term.name as keyof ZUFMSCore] &&
        'border-[#60B9DF]/50 border-dashed bg-[#25648B]/20'
      }`"
      :style="{ width }"
      v-for="term in termsList.filter(term => !props.omitTerms?.includes(term.name as keyof ZUFMSCore) ?? true) as any[]"
      :key="term.title"
      @click="toggleSelection(term.name as keyof ZUFMSCore)()"
    >
      <h4 class="text-white text-2xl font-semibold mb-1">
        {{ term.title }}
      </h4>
      <p class="text-[#749FB9] text-sm">
        {{ term.description }}
      </p>
    </div>
  </header>
</template>
