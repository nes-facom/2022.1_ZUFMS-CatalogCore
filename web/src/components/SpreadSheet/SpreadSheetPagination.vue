<script setup lang="ts">
import { useOccurrencesStore } from "@/store/occurrences";
import _ from "lodash";
import { ref, watchEffect, computed } from "vue";
import MaterialIcon from "../MaterialIcon.vue";
const props = defineProps<{
  maxPagesShown?: number;
}>();

const occurrencesStore = useOccurrencesStore();

const pagesShown = computed(() =>
  Math.min(
    props.maxPagesShown ?? occurrencesStore.pages,
    occurrencesStore.pages
  )
);

const currentPage = ref(1);
const previousPage = () =>
  (currentPage.value = Math.max(1, --currentPage.value));
const nextPage = () =>
  (currentPage.value = Math.min(occurrencesStore.pages, ++currentPage.value));

const isMiddlePage = (n: number) =>
  Math.ceil(pagesShown.value / 2) < n &&
  n < occurrencesStore.pages - Math.ceil(pagesShown.value / 2) + 1;

watchEffect(() => occurrencesStore.$patch({ currentPage: currentPage.value }));
</script>

<template>
  <section class="flex mt-3 text-[#99BBD0]">
    <button
      @click="previousPage()"
      :disabled="currentPage === 1"
      class="disabled:opacity-10 py-1 px-4 rounded flex items-center justify-center"
    >
      <MaterialIcon name="chevron_left" class="text-md" />
    </button>

    <template
      v-if="
        pagesShown === occurrencesStore.pages || occurrencesStore.pages <= 6
      "
    >
      <button
        :class="`py-1 px-4 rounded flex items-center justify-center ${
          currentPage === n &&
          `bg-[#52BD8F] text-white ${
            occurrencesStore.isFetchingPage && 'animate-pulse'
          }`
        }`"
        @click="currentPage = n"
        v-for="n in pagesShown"
        :key="n"
      >
        {{ n }}
      </button>
    </template>

    <template v-else>
      <button
        :class="`py-1 px-4 rounded flex items-center justify-center ${
          currentPage === n &&
          `bg-[#52BD8F] text-white ${
            occurrencesStore.isFetchingPage && 'animate-pulse'
          }`
        }`"
        @click="currentPage = n"
        v-for="n in Math.ceil(pagesShown / 2)"
        :key="n"
      >
        {{ n }}
      </button>

      <button
        v-if="isMiddlePage(currentPage)"
        class="py-1 px-4 rounded flex items-center justify-center bg-[#52BD8F] text-white"
      >
        {{ currentPage }}
      </button>

      <span v-else class="py-1 px-4 rounded flex items-center justify-center">
        ...
      </span>

      <button
        :class="`py-1 px-4 rounded flex items-center justify-center ${
          currentPage === n &&
          `bg-[#52BD8F] text-white ${
            occurrencesStore.isFetchingPage && 'animate-pulse'
          }`
        }`"
        @click="currentPage = n"
        v-for="n in _.range(
          occurrencesStore.pages - Math.ceil(pagesShown / 2) + 1,
          occurrencesStore.pages + 1
        )"
        :key="n"
      >
        {{ n }}
      </button>
    </template>

    <button
      @click="nextPage()"
      :disabled="
        currentPage === occurrencesStore.pages || occurrencesStore.pages === 0
      "
      class="disabled:opacity-10 py-2 px-4 flex items-center justify-center"
    >
      <MaterialIcon name="chevron_right" />
    </button>
  </section>
</template>
