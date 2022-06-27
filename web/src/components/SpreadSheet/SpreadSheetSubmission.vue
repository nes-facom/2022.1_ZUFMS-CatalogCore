<script setup lang="ts">
import _ from "lodash";
import type { ZUFMSCore } from "@/store/occurrences";
import { useSubmissionStore, termsInputs } from "@/store/submission";

const props = defineProps<{
  omitTerms?: (keyof ZUFMSCore)[];
}>();
const inputWidth = "20rem";

const submissionStore = useSubmissionStore();
</script>

<template>
  <section class="w-max h-full flex">
    <div>
      <div
        v-for="i in submissionStore.rows"
        :key="'row_' + i"
        class="w-full h-12 odd:bg-[#104F76] even:bg-[#0C496F]"
      >
        <input
          type="checkbox"
          class="-ml-6 mr-2"
          @change="() => {}"
          :checked="false"
        />
        <template
          v-for="term in termsInputs.filter(term => !props.omitTerms?.includes(term.name as keyof ZUFMSCore) ?? true) as any[]"
          :key="term.name + '_' + i"
        >
          <input
            :style="{ width: inputWidth }"
            :class="`transition-colors h-full bg-transparent focus:outline-none border-2 border-[#528CB0] focus:border-[#52BD8F] px-3 placeholder:text-[#336B8E] focus-visible:border-[#52BD8F] text-white`"
            :placeholder="term.placeholder"
            :value="submissionStore.$state.occurrences?.[i]?.[term.name as keyof ZUFMSCore]"
            :list="term.name"
            @input="() => {}"
            :name="`occurrence[${i}][${term.name}]`"
          />
          <datalist v-if="true || term.autocomplete" :id="term.name">
            <option v-for="value in []" :key="_.uniqueId(value)">
              {{ value }}
            </option>
          </datalist>
        </template>
      </div>
    </div>
  </section>
</template>
