<script setup lang="ts">
import { termsInputs } from "@/store/occurrences";
import type { ZUFMSCore } from "@/store/occurrences";

const props = defineProps<{
  occurrences?: ZUFMSCore[];
  currentTermclass?: string;
}>();
const emit = defineEmits(["submit", "changeTermclass"]);
const inputWidth = "20rem";

const onInput = () => {};

const termclassNamesDiff = (asd: any) => asd;
</script>

<template>
  <section class="w-max h-full flex">
    <form @submit.prevent="emit('submit')">
      <div
        v-for="(occurrence, i) in props.occurrences"
        :key="'row_' + occurrence.occurrenceID"
        class="w-full h-12 odd:bg-[#104F76] even:bg-[#0C496F]"
      >
        <input type="checkbox" class="-ml-6 mr-2" @change="() => {}" />
        <template v-for="(term, j) in termsInputs" :key="term.name + '_' + i">
          <input
            :style="{ width: inputWidth }"
            :class="`transition-colors h-full bg-transparent focus:outline-none border-2 border-[#528CB0] focus:border-[#52BD8F] px-3 placeholder:text-[#336B8E] focus-visible:border-[#52BD8F] text-white`"
            :autofocus="i === occurrences?.length - 1 && j === 0"
            :placeholder="term.placeholder"
            :tabindex="termclassNamesDiff(term.termclass)"
            :value="occurrence[term.name as keyof ZUFMSCore]"
            :list="term.name"
            @input="onInput"
            @focus="
              () =>
                props.currentTermclass !== term.termclass &&
                emit('changeTermclass', term.termclass, j)
            "
            :name="`entry[${i}][${term.name}]`"
          />
          <datalist v-if="term.autocomplete" :id="term.name">
            <option v-for="value in term.autocompleteValues" :key="value">
              {{ value }}
            </option>
          </datalist>
        </template>
      </div>
    </form>
  </section>
</template>
