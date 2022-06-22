<script setup lang="ts">
import { termsList } from "@/store/occurrences";
import { ref } from "vue";
const width = "20rem";

const selectedTerms = ref<Record<string, true>>({});

const toggleSelection = (term: string) => () => {
  if (selectedTerms.value[term]) {
    delete selectedTerms.value[term];
  } else {
    selectedTerms.value[term] = true;
  }
};
</script>

<template>
  <header class="flex mt-8 mb-5 w-max h-full cursor-default">
    <div
      :class="`pr-3 border-2 border-transparent ${
        selectedTerms[term.title] &&
        'border-[#60B9DF]/50 border-dashed bg-[#25648B]/20'
      }`"
      :style="{ width }"
      v-for="term in termsList"
      :key="term.title"
      @click="toggleSelection(term.title)()"
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
