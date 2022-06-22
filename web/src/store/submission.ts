import { ZUFMSCore as ApiZUFMSCore } from "client";
import { defineStore } from "pinia";
import * as zufmscore from "@/util/zufmscore";
import { userApi } from "@/api";

type ZUFMSCore = Required<ApiZUFMSCore>;

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
  error?: Error;
};

export const { counter, sizes } = zufmscore.termclassSizes;

export const terms = Object.keys(zufmscore.terms) as (keyof ZUFMSCore)[];
export const termclassesDescription = zufmscore.termclassDescriptions;

export const useSubmissionStore = defineStore("submissionStore", {
  state: () =>
    ({
      currentTermclassIndex: 0,
      occurrences: {},
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
  },
});
