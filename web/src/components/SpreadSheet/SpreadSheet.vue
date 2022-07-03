<script setup lang="ts">
import type { ZUFMSCore } from "@/store/occurrences";
import SpreadSheetCore from "../SpreadSheet/SpreadSheetCore.vue";
import SpreadSheetHeader from "../SpreadSheet/SpreadSheetHeader.vue";
import SpreadSheetNavigation from "../SpreadSheet/SpreadSheetNavigation.vue";
import SpreadSheetSubmission from "./SpreadSheetSubmission.vue";

const props = defineProps<{
  mode?: "submission" | "occurrences";
  showTermclassNavigation?: boolean;
  omitTerms?: string[];
  filter?: { [key in keyof ZUFMSCore]?: string };
}>();

const omitTerms =
  props.mode === "occurrences"
    ? (["artificial:section"] as (keyof ZUFMSCore)[])
    : [];
</script>

<template>
  <main class="w-full">
    <SpreadSheetNavigation
      v-if="props.mode === 'submission' && props.showTermclassNavigation"
    />
    <SpreadSheetHeader :omit-terms="omitTerms" />
    <SpreadSheetSubmission v-if="props.mode === 'submission'" />
    <SpreadSheetCore v-else :omit-terms="omitTerms" />
  </main>
</template>
