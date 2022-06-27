<script setup lang="ts">
import { useOccurrencesStore } from "@/store/occurrences";
import _ from "lodash";
import { ref, watchEffect } from "vue";
import MaterialIcon from "../MaterialIcon.vue";
const props = defineProps<{
  pages: number;
  pagesShown?: number;
}>();

const pagesShown = props.pagesShown ?? props.pages;

const currentPage = ref(1);
const previousPage = () =>
  (currentPage.value = Math.max(1, --currentPage.value));
const nextPage = () =>
  (currentPage.value = Math.min(props.pages, ++currentPage.value));

const isMiddlePage = (n: number) =>
  Math.ceil(pagesShown / 2) < n &&
  n < props.pages - Math.ceil(pagesShown / 2) + 1;

const occurrencesStore = useOccurrencesStore();

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

    <template v-if="pagesShown === props.pages">
      <button
        :class="`py-1 px-4 rounded flex items-center justify-center ${
          currentPage === n && 'bg-[#52BD8F] text-white'
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
          currentPage === n && 'bg-[#52BD8F] text-white'
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
          currentPage === n && 'bg-[#52BD8F] text-white'
        }`"
        @click="currentPage = n"
        v-for="n in _.range(
          props.pages - Math.ceil(pagesShown / 2) + 1,
          props.pages + 1
        )"
        :key="n"
      >
        {{ n }}
      </button>
    </template>

    <button
      @click="nextPage()"
      :disabled="currentPage === props.pages"
      class="disabled:opacity-10 py-2 px-4 flex items-center justify-center"
    >
      <MaterialIcon name="chevron_right" />
    </button>
  </section>
</template>
