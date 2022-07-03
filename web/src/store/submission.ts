import { ZUFMSCore as ApiZUFMSCore } from "client";
import { defineStore } from "pinia";
import * as zufmscore from "@/util/zufmscore";
import { userApi } from "@/api";
import { useToastStore, descriptionFromResponseError } from "./toast";

export type ZUFMSCore = Required<ApiZUFMSCore>;

type Termclass = {
  name: keyof ZUFMSCore;
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
  autocompleteValues: { [key in keyof ZUFMSCore]?: string[] };
};

export const { counter, sizes } = zufmscore.termclassSizes;

export const terms = Object.keys(zufmscore.terms) as (keyof ZUFMSCore)[];
export const termclassesDescription = zufmscore.termclassDescriptions;

export const termsInputs = Object.entries(zufmscore.terms).reduce(
  (arr, [name, value]) => [
    ...arr,
    {
      name: name as keyof ZUFMSCore,
      placeholder: (value as any).examples?.[0],
      value: (value as any)["default"] ?? "",
      autocomplete: (value as any)["$zufmscore:autocomplete"] ?? false,
      autocompleteValues: (value as any).examples,
      pattern: (value as any).pattern,
      termclass: value["$zufmscore:termclass"],
    },
  ],
  [] as {
    name: keyof ZUFMSCore;
    placeholder: string;
    value: string;
    autocomplete: boolean;
    autocompleteValues: string[];
    pattern?: string;
    termclass: string;
  }[]
);

export const useSubmissionStore = defineStore("submissionStore", {
  state: () =>
  ({
    currentTermclassIndex: 0,
    occurrences: [] as ZUFMSCore[],
    rows: 1,
    autocompleteValues: {},
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
      const toastStore = useToastStore();

      try {
        const response = await userApi.occurrences.occurrencesFileVerify(file);

        this.occurrences = response.data;
        this.rows = this.occurrences.length;
      } catch (err) {
        toastStore.pushMessage({
          title: "Erro ao carregar CSV",
          iconName: "error",
          description: descriptionFromResponseError(err),
          time: 5000,
        });
      }
    },

    changeOccurrenceTermValue<T extends keyof ZUFMSCore>(
      occurrenceIndex: number,
      term: T,
      value: ZUFMSCore[T]
    ) {
      this.occurrences[occurrenceIndex] = {
        ...(this.occurrences[occurrenceIndex] ?? {}),
        [term]: value,
      };
    },
    async fetchAutocompleteValues(term: keyof ZUFMSCore, value?: string) {
      try {
        const autocompleteValues = (
          await userApi.occurrences.occurrencesAutocomplete(term, value ?? "")
        ).data;

        if ((autocompleteValues as any).errors) {
          throw (autocompleteValues as any).errors;
        }

        this.autocompleteValues[term] = [
          ...new Set([
            ...(this.autocompleteValues[term] ?? []),
            ...(autocompleteValues ?? []),
          ]).values(),
        ].sort();
      } catch (err) {
        console.error(err);
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
    async createOccurrences() {
      const toastStore = useToastStore();

      try {
        const response = await userApi.occurrences.occurrencesCreateMany(
          this.occurrences
        );

        return response;
      } catch (err) {
        toastStore.pushMessage({
          title: "Erro ao submeter ocorrências",
          iconName: "error",
          description: descriptionFromResponseError(err),
          time: 5000,
        });
      }
    },
  },
});
