<script setup lang="ts">
import MaterialIcon from "@/components/MaterialIcon.vue";
import { useSubmissionStore, termsInputs } from "@/store/submission";
import type { ZUFMSCore } from "@/store/submission";
import _ from "lodash";
import { computed } from "vue";
import router from "@/router";
const props = defineProps<{
  terms: object;
  entries?: any;
  currentTermclass?: string;
}>();

const emit = defineEmits(["scrollY", "changeTermclass"]);

const submissionStore = useSubmissionStore();

const diffValues = () => {
  const values: any[] = [];

  return (value: any) => {
    const indexOf = values.indexOf(value);

    return indexOf !== -1 ? indexOf : values.push(value) - 1;
  };
};

const termclassNamesDiff = diffValues();

const inputWidth = "20.75rem";

const submit = (ev: Event) => {
  /*
  const formData = ev.target
    ? new FormData(ev.target as HTMLFormElement)
    : new FormData();

  const submissionData = new Array(submissionStore.rows).fill({});

  for (const [key, value] of (formData as any).entries()) {
    const [term, index] = key.split("_");
    const indexNumber = parseInt(index, 10) - 1;

    submissionData[indexNumber][term] = value;
  }
  */

  submissionStore.createOccurrences().then((data) => {
    if (data !== undefined) {
      router.push("/");
    }
  });
};

termsInputs.forEach(
  (term) =>
    term.autocomplete && submissionStore.fetchAutocompleteValues(term.name, "")
);

const onInput =
  (term: keyof ZUFMSCore, occurrenceIndex: number) => (ev: Event) => {
    const value = (ev as any).target.value as string;

    submissionStore.changeOccurrenceTermValue(occurrenceIndex, term, value);

    submissionStore.fetchAutocompleteValues(term, value);
  };

const termValueIsInAutocomplete = computed(
  () => (occurrenceIndex: number, term: any) => {
    if (!term.autocomplete) {
      return true;
    }

    const occurrenceTermValue =
      submissionStore.occurrences?.[occurrenceIndex]?.[
        term.name as keyof ZUFMSCore
      ] ?? "";

    const autocompleteValues =
      submissionStore.autocompleteValues[term.name as keyof ZUFMSCore] ?? [];

    return autocompleteValues.includes(occurrenceTermValue.toString());
  }
);
</script>

<template>
  <section class="overflow-y-scroll" @scroll="(ev) => emit('scrollY', ev)">
    <form @submit.prevent="submit">
      <div
        v-for="(__, i) in submissionStore.rows"
        :key="'row_' + i"
        class="w-full h-12 odd:bg-[#104F76] even:bg-[#0C496F]"
      >
        <template v-for="(term, j) in termsInputs" :key="term.name + '_' + i">
          <input
            :style="{ width: inputWidth }"
            :class="`${
              props.currentTermclass !== undefined &&
              props.currentTermclass !== term.termclass &&
              'opacity-10'
            } ${
              !termValueIsInAutocomplete(i, term) && '!border-yellow-500'
            } invalid:border-red-500 transition-colors h-full bg-transparent focus:outline-none border-2 border-[#528CB0] focus:border-[#52BD8F] px-3 placeholder:text-[#336B8E] focus-visible:border-[#52BD8F] text-white`"
            :autofocus="i === submissionStore.rows && j === 0"
            :placeholder="term.placeholder"
            :pattern="term.pattern"
            :type="term.type"
            :step="term.type === 'number' ? 'any' : undefined"
            :tabindex="
              props.currentTermclass !== undefined
                ? termclassNamesDiff(term.termclass)
                : 1
            "
            :value="submissionStore.occurrences?.[i]?.[term.name as keyof ZUFMSCore] ?? term.value"
            :list="term.name"
            @input="ev => onInput(term.name as keyof ZUFMSCore, i)(ev)"
            @focus="
              () =>
                props.currentTermclass !== undefined &&
                props.currentTermclass !== term.termclass &&
                emit('changeTermclass', term.termclass, j)
            "
            :name="`${term.name}_${i}`"
          />
          <datalist v-if="term.autocomplete" :id="term.name">
            <option
              v-for="value in submissionStore.$state.autocompleteValues[term.name as keyof ZUFMSCore]"
              :key="_.uniqueId(value)"
            >
              {{ value }}
            </option>
          </datalist>
        </template>
      </div>
      <div
        @click="
          submissionStore.rows++;
          emit('changeTermclass', 'zufmscore:management', 0);
        "
        class="w-full h-12 odd:bg-[#104F76] even:bg-[#0C496F] opacity-20"
      >
        <input
          :style="{ width: inputWidth }"
          class="h-full bg-transparent border-2 border-[#2E688C] px-3 placeholder:text-[#336B8E] focus-visible:border-[#52BD8F] text-white"
          v-for="term in termsInputs"
          :key="term.name + '_insert'"
          :placeholder="term.placeholder"
          :value="term.value"
          tabindex="-1"
        />
      </div>
      <div class="fixed right-16 bottom-16">
        <button
          type="submit"
          class="bg-[#52BD8F] transition-all hover:drop-shadow-xl hover:bg-[#369169] drop-shadow-md p-4 rounded-full flex items-center justify-center"
        >
          <MaterialIcon name="done" class="text-white" />
        </button>
      </div>
    </form>
  </section>
</template>
