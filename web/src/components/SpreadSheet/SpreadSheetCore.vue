<script setup lang="ts">
import _ from "lodash";
import { ref, watch } from "vue";
import { termsInputs, useOccurrencesStore } from "@/store/occurrences";
import type { ZUFMSCore } from "@/store/occurrences";
import { storeToRefs } from "pinia";
import OpacityTransition from "../transitions/OpacityTransition.vue";

const props = defineProps<{
  omitTerms?: (keyof ZUFMSCore)[];
}>();
const inputWidth = "20rem";

const occurrencesStore = useOccurrencesStore();

const occurrences = ref<ZUFMSCore[]>([]);

occurrencesStore.fetchOccurrences();

const { currentPageOccurrences, currentSectionIndex, currentPage, pages } =
  storeToRefs(occurrencesStore);

const fetchNewPage = () => {
  if (occurrencesStore.sections.length !== 0) {
    occurrencesStore.isFetchingPage = true;

    occurrencesStore
      .pageOccurrences(
        occurrencesStore.currentPage,
        occurrencesStore.currentSection
      )
      .then((data) => {
        occurrences.value = data;
        occurrencesStore.isFetchingPage = false;
      });
  }
};

fetchNewPage();

watch(
  [currentPageOccurrences, currentSectionIndex, currentPage, pages],
  fetchNewPage
);

const onInput =
  (term: any, occurrenceID: ZUFMSCore["occurrenceID"]) => (ev: Event) => {
    const value = (ev as any).target.value as string;

    occurrencesStore.changeOccurrenceTermValue(occurrenceID, term.name, value);

    if (term.autocomplete) {
      occurrencesStore.fetchAutocompleteValues(term.name, value);
    }
  };

const onCheckboxChange = (occurrenceID: ZUFMSCore["occurrenceID"]) => () =>
  occurrencesStore.toggleOccurrenceSelection(occurrenceID);

const termValueIsInAutocomplete = (occurrence: ZUFMSCore, term: any) =>
  !(
    occurrencesStore.autocompleteValues[term.name as keyof ZUFMSCore]?.includes(
      (
        occurrencesStore.$state.occurrenceChanges?.[
          occurrence["occurrenceID"]
        ]?.[term.name as keyof ZUFMSCore] ??
        occurrence[term.name as keyof ZUFMSCore]
      ).toString()
    ) ?? true
  );
</script>

<template>
  <section class="w-max h-full flex">
    <OpacityTransition appear>
      <div v-if="occurrences.length > 0" :key="occurrences.length">
        <div
          v-for="(occurrence, i) in occurrences"
          :key="'row_' + occurrence.occurrenceID"
          class="w-full h-12 odd:bg-[#104F76] even:bg-[#0C496F]"
        >
          <input
            type="checkbox"
            class="-ml-6 mr-2"
            @change="onCheckboxChange(occurrence.occurrenceID)()"
            :checked="
              occurrencesStore.selectedOccurrences[occurrence.occurrenceID]
            "
          />
          <template
            v-for="term in termsInputs.filter(term => !props.omitTerms?.includes(term.name as keyof ZUFMSCore) ?? true) as any[]"
            :key="term.name + '_' + i"
          >
            <input
              :pattern="term.pattern"
              :style="{ width: inputWidth }"
              :class="`transition-colors h-full bg-transparent focus:outline-none border-2 border-[#528CB0] focus:border-[#52BD8F] px-3 placeholder:text-[#336B8E] focus-visible:border-[#52BD8F] invalid:border-red-500 ${
                termValueIsInAutocomplete(occurrence, term) &&
                '!border-yellow-500'
              } text-white`"
              :placeholder="term.placeholder"
              :type="term.type"
              :value="occurrencesStore.$state.occurrenceChanges?.[occurrence['occurrenceID']]?.[term.name as keyof ZUFMSCore] ?? occurrence[term.name as keyof ZUFMSCore]"
              :list="term.name"
              :step="term.type === 'number' ? 'any' : undefined"
              @input="(ev) => onInput(term, occurrence['occurrenceID'])(ev)"
              :name="`occurrence[${i}][${term.name}]`"
            />
            <datalist v-if="term.autocomplete" :id="term.name">
              <option
                v-for="value in occurrencesStore.$state.autocompleteValues[term.name as keyof ZUFMSCore]"
                :key="_.uniqueId(value)"
              >
                {{ value }}
              </option>
            </datalist>
          </template>
        </div>
      </div>
    </OpacityTransition>
  </section>
</template>
