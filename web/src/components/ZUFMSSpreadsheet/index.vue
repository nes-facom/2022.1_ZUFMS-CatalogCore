<script setup lang="ts">
import TermInfo from "./TermInfo.vue";
import Spreadsheet from "./Spreadsheet.vue";
import MaterialIcon from "@/components/MaterialIcon.vue";
import _ from "lodash/fp";
import { nextTick, ref, watchEffect } from "vue";
import { computed } from "@vue/reactivity";
import * as zufmscore from "@/util/zufmscore";

const props = defineProps<{
  omitTerms?: string[];
  entries?: any[];
  submissionMode?: boolean;
  showTermclassNavigation?: boolean;
}>();

const emit = defineEmits(["scrollY"]);

const termclassesDescription = zufmscore.termclassDescriptions as Record<
  string,
  { title: string; description: string }
>;

const terms = _.omit(props.omitTerms ?? [], zufmscore.terms);

const termsList = Object.entries(terms).map(([key, value]) => ({
  name: key,
  termclass: value["$zufmscore:termclass"],
  ...value,
}));

const { counter, sizes } = zufmscore.termclassSizes;

const currentTermclassIndex = ref(0);

const currentTermclass = computed(() => ({
  ...(termclassesDescription[sizes[currentTermclassIndex.value].name] ?? {}),
  ...sizes[currentTermclassIndex.value],
}));

function getScrollParent(node: any): any {
  if (node == null) {
    return null;
  }

  return node.scrollWidth > node.clientWidth
    ? node
    : getScrollParent(node.parentNode);
}

const termclassCursor = ref<any>(null);

const termclassNavigation = ref<any>(null);

const mainRef = ref<any>(null);
const mainScrollParent = computed<any>(() => getScrollParent(mainRef?.value));

watchEffect(() =>
  mainScrollParent.value?.addEventListener("scroll", (ev: Event) => {
    let currentSize = 0;

    const actualTermclassIndex = sizes.findIndex((termclass) => {
      currentSize += termclass.size * 332;
      return currentSize > (ev.target as any)?.scrollLeft;
    });

    if (currentTermclassIndex.value !== actualTermclassIndex) {
      currentTermclassIndex.value = actualTermclassIndex;
    }
  })
);

const scrollTo = (x = 0, y = 0) =>
  mainScrollParent.value?.scrollTo({
    top: y,
    left: x,
    behavior: "smooth",
  });

const scrollToTermclassIndex = (index: number) => {
  const destinationTermclassEl = document.getElementById(
    sizes[index].name + sizes[index].counter
  );

  const { x = 0 } = destinationTermclassEl?.getBoundingClientRect() ?? {};

  scrollTo(
    x +
    mainScrollParent.value.scrollLeft -
    (termclassCursor.value?.getBoundingClientRect()?.x ?? 0)
  );
};

const goToNextTermclass = () =>
  scrollToTermclassIndex(
    currentTermclassIndex.value +
    (sizes.length - 1 > currentTermclassIndex.value ? 1 : 0)
  );

const goToPreviousTermclass = () =>
  scrollToTermclassIndex(
    currentTermclassIndex.value - (0 < currentTermclassIndex.value ? 1 : 0)
  );

const termclassCursorPointer = ref(0);

watchEffect(
  () =>
  (termclassCursorPointer.value =
    termclassNavigation.value?.getBoundingClientRect()?.bottom - 12)
);

const onSpreadsheetScrollY = (ev: Event) => {
  emit("scrollY", ev);

  nextTick(() => {
    termclassCursorPointer.value =
      termclassNavigation.value?.getBoundingClientRect()?.bottom - 12;
  });
};

const changeTermclass = (termclass: string, i: number) => {
  const termclassSizeIndex = sizes.findIndex(
    (tc) => tc.name === termclass && (tc.start >= i || i <= tc.start + tc.size)
  );

  if (
    termclassSizeIndex !== undefined &&
    termclassSizeIndex !== currentTermclassIndex.value
  ) {
    scrollToTermclassIndex(termclassSizeIndex);
  }
};

</script>

<template>
  <main :class="`w-max h-full flex flex-col ${props.showTermclassNavigation && 'overflow-y-hidden'}`" ref="mainRef">
    <section v-if="props.submissionMode && props.showTermclassNavigation" ref="termclassNavigation"
      class="min-w-fit py-7 px-20 border-b-2 border-[#2E688C] relative flex">
      <div class="flex items-start z-[1] fix">
        <div class="flex flex-col items-center fixed" ref="termclassCursor">
          <div id="current-icon" class="flex rounded-full p-4 mb-3 w-fit bg-[#52bd8f]">
            <MaterialIcon name="assignment" class="text-white" />
          </div>
          <div class="max-w-[11rem] whitespace-normal">
            <div class="flex text-white mb-1">
              <h5 class="font-semibold mr-1">
                {{ currentTermclass.title }}
              </h5>
              <span class="text-white/50">{{
                  counter[currentTermclass.name] === 1
                    ? ""
                    : ` [${currentTermclass.counter}/${counter[currentTermclass.name]
                    }]`
              }}</span>
            </div>

            <p id="current-subtitle" class="text-[#99BBD0] text-sm">
              {{ currentTermclass.description }}
            </p>
          </div>
          <div class="bg-[#2E688C] rounded-full p-[.25rem] fixed" :style="{
            top: termclassCursorPointer + 'px',
          }">
            <div class="bg-[#52bd8f] rounded-full p-[.35rem]"></div>
          </div>
          <button v-if="currentTermclassIndex > 0" @click="goToPreviousTermclass"
            class="absolute left-0 top-0 text-[#99BBD0] transition-all hover:text-white text-md p-4">
            <span class="material-icons-outlined">chevron_left</span>
          </button>
          <button v-if="currentTermclassIndex < sizes.length - 1" @click="goToNextTermclass"
            class="absolute right-0 top-0 text-[#99BBD0] transition-all hover:text-white text-md p-4">
            <span class="material-icons-outlined">chevron_right</span>
          </button>
        </div>
      </div>

      <div :style="{ width: termclass.size * 20.75 + 'rem' }" class="flex items-start" v-for="termclass in sizes"
        :key="termclass.name" :id="termclass.name + termclass.counter">
        <div class="flex flex-col items-center">
          <div class="flex opacity-40 rounded-full p-4 mb-3 w-fit bg-[#407494]">
            <span class="material-icons-outlined text-white"> assignment </span>
          </div>
          <div class="opacity-40 max-w-[11rem] whitespace-normal">
            <div class="flex text-[#99BBD0] mb-1">
              <h5 class="font-semibold mr-1">
                {{ termclassesDescription[termclass.name].title }}
              </h5>
              <span class="text-[#99BBD0]/50">{{
                  counter[termclass.name] === 1
                    ? ""
                    : ` [${termclass.counter}/${counter[termclass.name]}]`
              }}</span>
            </div>
            <p class="text-[#99BBD0] text-sm">
              {{ termclassesDescription[termclass.name].description }}
            </p>
          </div>
          <div class="bg-[#2E688C] rounded-full p-[.6rem] absolute -bottom-[.6rem]"></div>
        </div>
      </div>
    </section>

    <header class="flex mt-8 mb-5">
      <TermInfo v-for="term in termsList" :key="term.name" :title="term.title" :description="term.description"
        :class="currentTermclass.name !== term.termclass && 'opacity-10'" width="20rem" />
    </header>

    <Spreadsheet class="h-full" :terms="terms" :submissionMode="props.submissionMode"
      :currentTermclass="currentTermclass.name" @scrollY="onSpreadsheetScrollY" @changeTermclass="changeTermclass" />
  </main>
</template>
