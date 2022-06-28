<script setup lang="ts">
import { userApi } from "@/api";
import MaterialIcon from "@/components/MaterialIcon.vue";
import { ref } from "vue";
const props = defineProps<{
  terms: object;
  submissionMode?: boolean;
  entries?: any;
  currentTermclass?: string;
}>();

const emit = defineEmits(["scrollY", "changeTermclass"]);

const termsInputs = Object.entries(props.terms).reduce(
  (arr, [name, value]) => [
    ...arr,
    {
      name,
      placeholder: value?.examples?.[0],
      value: value["default"],
      autocomplete: value["$zufmscore:autocomplete"],
      autocompleteValues: value?.examples,
      termclass: value["$zufmscore:termclass"],
    },
  ],
  [] as {
    name: string;
    placeholder: string;
    value: string;
    autocomplete: boolean;
    autocompleteValues: string[];
    termclass: string;
  }[]
);

const rows = ref(1);

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
  const formData = ev.target
    ? new FormData(ev.target as HTMLFormElement)
    : new FormData();

  const submissionData = new Array(rows.value).fill({});

  for (const [key, value] of (formData as any).entries()) {
    const [term, index] = key.split("_");
    const indexNumber = parseInt(index, 10) - 1;

    submissionData[indexNumber][term] = value;
  }

  console.log(submissionData);

  userApi.occurrences.occurrencesCreateMany(submissionData);
};
const onInput = (data: any) => {
  console.log(data.target.value);
};
</script>

<template>
  <section class="overflow-y-scroll" @scroll="(ev) => emit('scrollY', ev)">
    <form @submit.prevent="submit">
      <div
        v-for="i in rows"
        :key="'row_' + i"
        class="w-full h-12 odd:bg-[#104F76] even:bg-[#0C496F]"
      >
        <template v-for="(term, j) in termsInputs" :key="term.name + '_' + i">
          <input
            :style="{ width: inputWidth }"
            :class="`${
              props.currentTermclass !== term.termclass && 'opacity-10'
            } transition-colors h-full bg-transparent focus:outline-none border-2 border-[#528CB0] focus:border-[#52BD8F] px-3 placeholder:text-[#336B8E] focus-visible:border-[#52BD8F] text-white`"
            :autofocus="i === rows && j === 0"
            :placeholder="term.placeholder"
            :tabindex="termclassNamesDiff(term.termclass)"
            :initialValue="term.value"
            :list="term.name"
            @input="onInput"
            @focus="
              () =>
                props.currentTermclass !== term.termclass &&
                emit('changeTermclass', term.termclass, j)
            "
            :name="`${term.name}_${i}`"
          />
          <datalist v-if="term.autocomplete" :id="term.name">
            <option v-for="value in term.autocompleteValues" :key="value">
              {{ value }}
            </option>
          </datalist>
        </template>
      </div>
      <div
        v-if="props.submissionMode"
        @click="
          rows++;
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
      <div v-if="props.submissionMode" class="fixed right-16 bottom-16">
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
