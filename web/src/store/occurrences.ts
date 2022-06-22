import { ZUFMSCore as ApiZUFMSCore } from "client";
import { defineStore } from "pinia";
import * as zufmscore from "@/util/zufmscore";
import { userApi } from "@/api";
import _ from "lodash";

export type ZUFMSCore = Required<ApiZUFMSCore>;

type State = {
  occurrences: ZUFMSCore[];
};

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

export const useOccurrencesStore = defineStore("occurrencesStore", {
  state: () =>
    ({
      occurrences: _.range(10).map((i) => ({
        occurrenceID: `ZUFMSAMP-${i}`,
        "artificial:section": "AMP",
        "dcterms:modified": "asdasd",
      })),
    } as State),
});
