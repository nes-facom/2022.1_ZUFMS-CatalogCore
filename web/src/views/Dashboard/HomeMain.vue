<script setup lang="ts">
import SpreadSheet from "@/components/SpreadSheet/SpreadSheet.vue";
import MaterialIcon from "@/components/MaterialIcon.vue";
import { ref } from "vue";
import DashboardHeader from "./DashboardHeader.vue";

const props = defineProps<{
  sidebarState: {
    section: string;
  };
}>();

const searchBoxValue = ref("");
</script>

<template>
  <div class="flex flex-col w-full h-full pt-10">
    <DashboardHeader
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
        <button
          @click="() => {}"
          :class="`${
            !false
              ? 'hover:bg-green-600 hover:border-green-700'
              : 'bg-green-600  cursor-default'
          } w-fit p-2 mr-2 flex items-center justify-center rounded-md text-gray-300 border-2 border-[#2E688C]`"
        >
          <MaterialIcon name="upload" />
        </button>
        <button
          @click="() => {}"
          :disabled="!false"
          :class="`${
            false
              ? 'hover:bg-green-600 hover:border-green-700'
              : 'opacity-30 cursor-default'
          } w-fit p-2 mr-2 flex items-center justify-center rounded-md text-gray-300 border-2 border-[#2E688C]`"
        >
          <MaterialIcon name="check" />
        </button>
        <button
          @click="() => {}"
          :disabled="!false"
          :class="`${
            false
              ? 'hover:bg-red-600 hover:border-red-700'
              : 'opacity-30 cursor-default'
          } w-fit p-2 flex items-center justify-center rounded-md text-gray-300 border-2 border-[#2E688C]`"
        >
          <MaterialIcon name="delete" />
        </button>
      </div>
    </section>

    <div class="flex-1 w-full pl-16 pb-16 overflow-x-scroll">
      <SpreadSheet
        :omit-terms="['artificial:section']"
        :filter="{ 'artificial:section': props.sidebarState.section }"
      />
    </div>
  </div>
</template>
