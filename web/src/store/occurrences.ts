import { ZUFMSCore as ApiZUFMSCore } from "client";
import { defineStore } from "pinia";
import * as zufmscore from "@/util/zufmscore";
import { userApi } from "@/api";
import _ from "lodash";
import { wait } from "@/util/async";
import { descriptionFromResponseError, useToastStore } from "./toast";

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
      placeholder: (value as any).examples?.[0],
      value: (value as any)["default"] ?? "",
      autocomplete: (value as any)["$zufmscore:autocomplete"] ?? false,
      autocompleteValues: (value as any).examples,
      pattern: (value as any).pattern,
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
  occurrences: Record<number, ZUFMSCore[]>;
  occurrencesPerPage: number;
  currentPage: number;
  pages: number;
  selectedTerms: { [key in keyof ZUFMSCore]?: true };
  occurrenceChanges: Record<ZUFMSCore["occurrenceID"], Partial<ZUFMSCore>>;
  autocompleteValues: { [key in keyof ZUFMSCore]?: string[] };
  selectedOccurrences: Record<ZUFMSCore["occurrenceID"], true>;
  currentSection: ZUFMSCore["artificial:section"];
  isFetchingPage: boolean;
  pageOccurrences: (
    page: number,
    section: ZUFMSCore["artificial:section"]
  ) => Promise<ZUFMSCore[]>;
};

export const useOccurrencesStore = defineStore("occurrencesStore", {
  state: () =>
    ({
      occurrences: {},
      occurrencesPerPage: 10,
      currentPage: 1,
      pages: 0,
      occurrenceChanges: {},
      autocompleteValues: {},
      selectedOccurrences: {},
      selectedTerms: {},
      currentSection: "Amphibia",
      isFetchingPage: false,
    } as State),

  getters: {
    pageOccurrences: (state) =>
      _.memoize(
        async (page: number, section: ZUFMSCore["artificial:section"]) =>
          (
            await userApi.occurrences.occurrencesGetAll(
              undefined,
              state.occurrencesPerPage * (page - 1),
              state.occurrencesPerPage,
              section
            )
          ).data as unknown as ZUFMSCore[]
      ),
    currentPageOccurrences: (state) =>
      state.pageOccurrences(state.currentPage, state.currentSection),
    hasSomeOccurrenceSelected: (state) =>
      Object.keys(state.selectedOccurrences).length > 0,
    hasSomeOccurrenceChange: (state) =>
      Object.keys(state.occurrenceChanges).length > 0,
    hasSomeTermSelected: (state) => Object.keys(state.selectedTerms).length > 0,
  },
  actions: {
    async getSections() {
      return await userApi.occurrences.occurrencesAutocomplete(
        "artificial:section",
        ""
      );
    },

    async loadFromCsv(file: File) {
      const toastStore = useToastStore();

      try {
        await userApi.occurrences.occurrencesFile({
          data: file,
        });

        toastStore.pushMessage({
          title: "CSV carregado com sucesso",
          iconName: "done",
        });

        this.fetchOccurrences();
      } catch (err) {
        toastStore.pushMessage({
          title: "Erro ao carregar CSV",
          iconName: "error",
          description: descriptionFromResponseError(err),
          time: 5000,
        });
      }
    },

    async fetchOccurrences() {
      const occurrencesCount = await fetch(
        `https://localhost/v1/occurrences/count?artificial:section=${this.currentSection}`,
        {
          headers: {
            Authorization: `Bearer ${localStorage.getItem("_at") ?? ""}`,
          },
        }
      ).then((data) => data.json());

      this.pages = Math.ceil(occurrencesCount / this.occurrencesPerPage);

      this.pageOccurrences.cache.clear?.();
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
      /*
      return this.occurrences.reduce(
        (occurrences, occurrencesPage) => [
          ...occurrences,
          ...occurrencesPage
            .filter(
              (occurrence: ZUFMSCore) => this.selectedOccurrences[occurrence.occurrenceID]
            )
            .map((occurrence: ZUFMSCore) =>
              this.hasSomeTermSelected
                ? _.pick(occurrence, Object.keys(this.selectedTerms))
                : occurrence
            ),
        ],
        [] as Partial<ZUFMSCore>[]
      );
      */

      return [];
    },

    async uploadOccurrences() {
      return 0;
    },
  },
});
