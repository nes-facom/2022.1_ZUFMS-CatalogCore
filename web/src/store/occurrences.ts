import { ZUFMSCore as ApiZUFMSCore } from "client";
import { defineStore } from "pinia";
import * as zufmscore from "@/util/zufmscore";
import { userApi } from "@/api";
import _ from "lodash";

export type ZUFMSCore = Required<ApiZUFMSCore>;

type InputAbstraction = {
  name: string;
  placeholder: string;
  value: string;
  autocomplete: boolean;
  autocompleteValues: string[];
  termclass: string;
};

export const { counter, sizes } = zufmscore.termclassSizes;

export const terms = Object.keys(zufmscore.terms) as (keyof ZUFMSCore)[];
export const termsList = Object.entries(zufmscore.terms).map(
  ([key, value]) => ({
    name: key,
    termclass: value["$zufmscore:termclass"],
    ...value,
  })
);
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

type State = {
  occurrences: ZUFMSCore[];
  occurrencesPerPage: number;
  currentPage: number;
  selectedTerms: { [key in keyof ZUFMSCore]?: true };
  occurrenceChanges: Record<ZUFMSCore["occurrenceID"], Partial<ZUFMSCore>>;
  autocompleteValues: { [key in keyof ZUFMSCore]?: string[] };
  selectedOccurrences: Record<ZUFMSCore["occurrenceID"], true>;
  currentSection: ZUFMSCore["artificial:section"];
};

export const useOccurrencesStore = defineStore("occurrencesStore", {
  state: () =>
    ({
      occurrences: [],
      occurrencesPerPage: 10,
      currentPage: 1,
      occurrenceChanges: {},
      autocompleteValues: {},
      selectedOccurrences: {},
      selectedTerms: {},
      currentSection: "AMP",
    } as State),

  getters: {
    pages: (state) =>
      Math.ceil(state.occurrences.length / state.occurrencesPerPage),
    pageOccurrences: (state) =>
      state.occurrences.slice(
        state.currentPage * state.occurrencesPerPage,
        state.currentPage * state.occurrencesPerPage + state.occurrencesPerPage
      ),
    hasSomeOccurrenceSelected: (state) =>
      Object.keys(state.selectedOccurrences).length > 0,
    hasSomeOccurrenceChange: (state) =>
      Object.keys(state.occurrenceChanges).length > 0,
    hasSomeTermSelected: (state) => Object.keys(state.selectedTerms).length > 0,
  },
  actions: {
    fetchOccurrences() {
      this.occurrences = _.range(100).map(
        (i) =>
          ({
            occurrenceID: `ZUFMSAMP-${i}`,
            "artificial:section": "AMP",
            "dcterms:modified": new Date().toLocaleDateString("pt-BR"),
          } as any)
      );
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

    getAutocompleteValues(term: keyof ZUFMSCore, value?: string) {
      return this.autocompleteValues[term]?.filter((val) =>
        val.startsWith(value ?? "")
      );
    },

    changeOccurrenceTermValue<T extends keyof ZUFMSCore>(
      occurrenceID: ZUFMSCore["occurrenceID"],
      term: T,
      value: ZUFMSCore[T]
    ) {
      this.occurrenceChanges[occurrenceID] = {
        ...(this.occurrenceChanges[occurrenceID] ?? {}),
        [term]: value,
      };
    },

    toggleOccurrenceSelection(occurrenceID: ZUFMSCore["occurrenceID"]) {
      if (this.selectedOccurrences[occurrenceID]) {
        delete this.selectedOccurrences[occurrenceID];
      } else {
        this.selectedOccurrences[occurrenceID] = true;
      }
    },

    toggleTermSelection(term: keyof ZUFMSCore) {
      if (this.selectedTerms[term]) {
        delete this.selectedTerms[term];
      } else {
        this.selectedTerms[term] = true;
      }
    },

    async deleteSelectedOccurrences() {
      await userApi.occurrences.occurrencesDeleteMany(
        Object.keys(this.selectedOccurrences)
      );

      this.selectedOccurrences = {};
    },

    async updateChangedOccurrences() {
      await userApi.occurrences.occurrencesUpdateMany(
        Object.entries(this.occurrenceChanges).map(
          ([occurrenceID, values]) => ({ occurrenceID, ...values })
        )
      );

      this.occurrenceChanges = {};
    },

    async downloadSelectedOccurrences() {
      return this.occurrences.reduce(
        (occurrences, occurrencesPage) => [
          ...occurrences,
          ...occurrencesPage
            .filter(
              (occurrence) => this.selectedOccurrences[occurrence.occurrenceID]
            )
            .map((occurrence) =>
              this.hasSomeTermSelected
                ? _.pick(occurrence, Object.keys(this.selectedTerms))
                : occurrence
            ),
        ],
        [] as Partial<ZUFMSCore>[]
      );
    },

    async uploadOccurrences() {
      return 0;
    },
  },
});
