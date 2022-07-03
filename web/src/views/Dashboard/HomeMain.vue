<script setup lang="ts">
import SpreadSheet from "@/components/SpreadSheet/SpreadSheet.vue";
import MaterialIcon from "@/components/MaterialIcon.vue";
import { ref } from "vue";
import DashboardHeader from "./DashboardHeader.vue";
import { useOccurrencesStore } from "@/store/occurrences";
import SpreadSheetPagination from "@/components/SpreadSheet/SpreadSheetPagination.vue";

const props = defineProps<{
  sidebarState: {
    section: string;
  };
}>();

const searchBoxValue = ref("");

const occurrencesStore = useOccurrencesStore();

const showHeader = ref(true);

const onSpreadSheetScroll = (ev: Event) => {
  if ((ev.target as any)?.scrollTop === 0) {
    //  showHeader.value = true;
  } else {
    showHeader.value = false;
  }
};

const onChangeFileUpload = (e: Event) => {
  const file = (e as any).target?.files?.[0] as File | undefined;

  if (file) {
    occurrencesStore.createFromCsv(file);
  }
};
</script>

<template>
  <div :class="`${showHeader && 'pt-10'} flex flex-col w-full h-full`">
    <DashboardHeader
      v-if="showHeader"
      class="px-16"
      title="Todos os registros"
      subtitle="Listagem de todos os registros cadastrados"
    />

    <section
      class="flex justify-between w-full min-w-fit flex px-16 py-5 border-b-2 border-[#2E688C]"
    >
      <div
        :class="`flex-1 mr-20 py-[.6rem] flex items-center rounded-md border-2 border-[#2E688C] bg-[#10527A]`"
      >
        <MaterialIcon name="search" class="mx-3 text-[#4E93BF] text-[1.2rem]" />
        <input
          v-model="searchBoxValue"
          class="w-full bg-transparent placeholder:text-[#749FB9] text-gray-200 focus-visible:outline-none"
          placeholder="Pesquisar"
        />
      </div>
      <div class="flex">
        <div
          class="cursor-pointer hover:bg-green-600 hover:border-green-700 w-fit p-2 mr-2 flex items-center justify-center rounded-md text-gray-300 border-2 border-[#2E688C]"
        >
          <input
            class="w-20 h-20 opacity-0 absolute cursor-pointer"
            type="file"
            @change="onChangeFileUpload"
          />
          <MaterialIcon name="file_upload" class="text-white" />
        </div>
        <button
          @click="occurrencesStore.downloadSelectedOccurrences()"
          :disabled="!occurrencesStore.hasSomeOccurrenceSelected"
          :class="`${
            occurrencesStore.hasSomeOccurrenceSelected
              ? 'hover:bg-green-600 hover:border-green-700'
              : 'opacity-30 cursor-default'
          } w-fit p-2 mr-2 flex items-center justify-center rounded-md text-gray-300 border-2 border-[#2E688C]`"
        >
          <MaterialIcon name="download" />
        </button>
        <button
          @click="occurrencesStore.updateChangedOccurrences()"
          :disabled="!occurrencesStore.hasSomeOccurrenceChange"
          :class="`${
            occurrencesStore.hasSomeOccurrenceChange
              ? 'hover:bg-green-600 hover:border-green-700'
              : 'opacity-30 cursor-default'
          } w-fit p-2 mr-2 flex items-center justify-center rounded-md text-gray-300 border-2 border-[#2E688C]`"
        >
          <MaterialIcon name="check" />
        </button>
        <button
          @click="occurrencesStore.deleteSelectedOccurrences()"
          :disabled="!occurrencesStore.hasSomeOccurrenceSelected"
          :class="`${
            occurrencesStore.hasSomeOccurrenceSelected
              ? 'hover:bg-red-600 hover:border-red-700'
              : 'opacity-30 cursor-default'
          } w-fit p-2 flex items-center justify-center rounded-md text-gray-300 border-2 border-[#2E688C]`"
        >
          <MaterialIcon name="delete" />
        </button>
      </div>
    </section>

    <div
      class="flex-1 w-full pl-16 pb-10 overflow-x-scroll"
      @scroll="onSpreadSheetScroll"
    >
      <SpreadSheet
        mode="occurrences"
        :omit-terms="['artificial:section']"
        :filter="{ 'artificial:section': props.sidebarState.section }"
      />
      <div
        class="w-full flex items-center justify-center sticky bottom-0 left-0"
      >
        <SpreadSheetPagination
          class="bg-[#024168]/90 rounded-md p-1 backdrop-blur"
          :max-pages-shown="6"
        />
      </div>
    </div>
  </div>
</template>
