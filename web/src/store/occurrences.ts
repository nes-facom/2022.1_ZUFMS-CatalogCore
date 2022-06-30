import { ZUFMSCore as ApiZUFMSCore } from "client";
import { defineStore } from "pinia";
import * as zufmscore from "@/util/zufmscore";
import { userApi } from "@/api";
import _ from "lodash";
import { wait } from "@/util/async";
import { descriptionFromResponseError, useToastStore } from "./toast";
import { jsonToCSV } from "@/util/csv";

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
  sections: ZUFMSCore["artificial:section"][];
  selectedTerms: { [key in keyof ZUFMSCore]?: true };
  occurrenceChanges: Record<ZUFMSCore["occurrenceID"], Partial<ZUFMSCore>>;
  autocompleteValues: { [key in keyof ZUFMSCore]?: string[] };
  selectedOccurrences: Record<ZUFMSCore["occurrenceID"], true>;
  currentSection: ZUFMSCore["artificial:section"];
  currentSectionIndex: number;
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
      currentSectionIndex: 0,
      sections: [] as ZUFMSCore["artificial:section"][],
      isFetchingPage: false,
    } as State),

  getters: {
    currentSection: (state) => state.sections[state.currentSectionIndex],
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
      state.pageOccurrences(
        state.currentPage,
        state.sections[state.currentSectionIndex]
      ),
    hasSomeOccurrenceSelected: (state) =>
      Object.keys(state.selectedOccurrences).length > 0,
    hasSomeOccurrenceChange: (state) =>
      Object.keys(state.occurrenceChanges).length > 0,
    hasSomeTermSelected: (state) => Object.keys(state.selectedTerms).length > 0,
  },
  actions: {
    async createFromCsv(file: File) {
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
      try {
        const sections = await userApi.occurrences.occurrencesAutocomplete(
          "artificial:section",
          ""
        );

        this.sections = sections.data;

        const occurrencesCount = await fetch(
          `https://localhost/v1/occurrences/count?artificial:section=${this.currentSection}`,
          {
            headers: {
              Authorization: `Bearer ${localStorage.getItem("_at") ?? ""}`,
            },
          }
        ).then((data) => data.json());

        if (occurrencesCount.errors) {
          throw { response: { data: occurrencesCount } };
        }

        this.pages = Math.ceil(occurrencesCount / this.occurrencesPerPage);

        this.pageOccurrences.cache.clear?.();
      } catch (err) {
        const toastStore = useToastStore();

        toastStore.pushMessage({
          title: "Erro ao carregar ocorrências",
          iconName: "error",
          description: descriptionFromResponseError(err),
          time: 5000,
        });
      }
    },

    async fetchAutocompleteValues(term: keyof ZUFMSCore, value?: string) {
      const toastStore = useToastStore();

      try {
        const autocompleteValues = (
          await userApi.occurrences.occurrencesAutocomplete(term, value ?? "")
        ).data;

        if ((autocompleteValues as any).errors) {
          throw { response: { data: autocompleteValues as any } };
        }

        this.autocompleteValues[term] = [
          ...new Set([
            ...(this.autocompleteValues[term] ?? []),
            ...(autocompleteValues ?? []),
          ]).values(),
        ].sort();
      } catch (err) {
        toastStore.pushMessage({
          title: "Erro ao buscar dados",
          iconName: "warning",
          colorClass: "text-yellow-500",
          description: descriptionFromResponseError(err),
        });
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
      const toastStore = useToastStore();

      try {
        await userApi.occurrences.occurrencesDeleteMany(
          Object.keys(this.selectedOccurrences)
        );

        this.selectedOccurrences = {};
      } catch (err) {
        toastStore.pushMessage({
          title: "Erro ao deletar ocorrências",
          iconName: "error",
          description: descriptionFromResponseError(err),
        });
      }
    },

    async updateChangedOccurrences() {
      const toastStore = useToastStore();

      try {
        await userApi.occurrences.occurrencesUpdateMany(
          Object.entries(this.occurrenceChanges).map(
            ([occurrenceID, values]) => ({ occurrenceID, ...values })
          )
        );

        this.occurrenceChanges = {};
      } catch (err) {
        toastStore.pushMessage({
          title: "Erro ao atualizar ocorrências",
          iconName: "error",
          description: descriptionFromResponseError(err),
        });
      }
    },

    async downloadSelectedOccurrences() {
      const toastStore = useToastStore();

      const occurrencesFetch = (await Promise.all(
        Object.keys(this.selectedOccurrences).map((occurrenceID) =>
          userApi.occurrences
            .occurrencesGetOne(occurrenceID)
            .then((response) => response.data)
            .catch((err) => {
              toastStore.pushMessage({
                title: `Erro ao baixar a ocorrência ${occurrenceID}`,
                iconName: "error",
                description: descriptionFromResponseError(err),
              });

              return undefined;
            })
        )
      )) as (ZUFMSCore | undefined)[];

      const selectedTerms = Object.keys(this.selectedTerms);

      const occurrences = occurrencesFetch
        .filter((data) => data !== undefined)
        .map((occurrence) =>
          selectedTerms.length > 0
            ? _.pick(occurrence, selectedTerms)
            : occurrence
        );

      const downloadLink = document.createElement("a");

      downloadLink.download = `zufms_${Date.now()}.csv`;

      downloadLink.href = `data:application/octet-stream,${encodeURI(
        jsonToCSV(occurrences)
      )}`;

      downloadLink.click();
    },
  },
});
