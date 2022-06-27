import { ZUFMSCore as ApiZUFMSCore } from "client";
import { defineStore } from "pinia";
import * as zufmscore from "@/util/zufmscore";
import { userApi } from "@/api";

export type ZUFMSCore = Required<ApiZUFMSCore>;

type Termclass = {
  name: string;
  start: number;
  counter: number;
  size: number;
  title: string;
  description: string;
};

type State = {
  currentTermclassIndex: number;
  occurrences: Partial<ZUFMSCore>[];
  currentTermclass: Termclass;
  currentTermclassGlobalCounter: number;
  rows: number;
  error?: Error;
};

export const { counter, sizes } = zufmscore.termclassSizes;

export const terms = Object.keys(zufmscore.terms) as (keyof ZUFMSCore)[];
export const termclassesDescription = zufmscore.termclassDescriptions;

export const termsInputs = Object.entries(zufmscore.terms).reduce(
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

export const useSubmissionStore = defineStore("submissionStore", {
  state: () =>
    ({
      currentTermclassIndex: 0,
      occurrences: {},
      rows: 1,
    } as State),
  getters: {
    currentTermclass: (state) => ({
      ...(termclassesDescription[
        sizes[state.currentTermclassIndex]
          .name as keyof typeof termclassesDescription
      ] ?? {}),
      ...sizes[state.currentTermclassIndex],
    }),
    currentTermclassGlobalCounter: (state) =>
      counter[state.currentTermclass.name],
    currentTermclassCounterIndicator: (state) =>
      state.currentTermclassGlobalCounter === 1
        ? ""
        : `[${state.currentTermclass.counter}/${state.currentTermclassGlobalCounter}]`,
  },

  actions: {
    async loadFromCsv(file: File) {
      try {
        const response = await userApi.occurrences.occurrencesFileVerify(file);

        this.occurrences = response.data;
      } catch (err) {
        if (err instanceof Error) {
          this.error = err;
        }
      }
    },
    nextTermclass() {
      this.currentTermclassIndex +=
        Object.keys(termclassesDescription).length - 1 <
        this.currentTermclassIndex
          ? 1
          : 0;
    },
    previousTermclass() {
      this.currentTermclassIndex -= this.currentTermclassIndex < 0 ? 1 : 0;
    },
    goToTermclass() {
      return 0;
    },
    submit() {
      return 0;
    },
    occurrenceChange<T extends keyof ZUFMSCore>(
      index: number,
      term: T,
      value: ZUFMSCore[T]
    ) {
      if (!this.occurrences[index]) {
        this.occurrences[index] = {};
      }

      this.occurrences[index][term] = value;
    },
  },
});
