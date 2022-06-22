<script setup lang="ts">
import { useOccurrencesStore, ZUFMSCore } from "@/store/occurrences";
import SpreadSheetCore from "../SpreadSheet/SpreadSheetCore.vue";
import SpreadSheetHeader from "../SpreadSheet/SpreadSheetHeader.vue";
import SpreadSheetNavigation from "../SpreadSheet/SpreadSheetNavigation.vue";
import SpreadSheetPagination from '../SpreadSheet/SpreadSheetPagination.vue'

const props = defineProps<{
    mode?: 'submission' | 'occurrences'
    showTermclassNavigation?: boolean;
    omitTerms?: string[]
    filter?: { [key in keyof ZUFMSCore]?: string }
}
>();

const occurrencesStore = useOccurrencesStore()
</script>

<template>
    <main>
        <SpreadSheetNavigation v-if="props.submissionMode && props.showTermclassNavigation" />
        <SpreadSheetHeader />
        <SpreadSheetCore :occurrences="occurrencesStore.occurrences" />
        <SpreadSheetPagination />
    </main>
</template>
