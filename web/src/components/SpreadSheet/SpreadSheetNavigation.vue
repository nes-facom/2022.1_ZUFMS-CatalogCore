<script setup lang="ts">
import MaterialIcon from "@/components/MaterialIcon.vue";
import {
  useSubmissionStore,
  sizes,
  counter,
  termclassesDescription,
} from "@/store/submission";
import { computed, ref, watchEffect } from "vue";

const termclassCursor = ref<any>(null);
const termclassNavigation = ref<any>(null);

const termclassCursorPointer = ref(0);

function getScrollParent(node: any): any {
  if (node == null) {
    return null;
  }

  return node.scrollWidth > node.clientWidth
    ? node
    : getScrollParent(node.parentNode);
}

const mainRef = ref<any>(null);
const mainScrollParent = computed<any>(() => getScrollParent(mainRef?.value));

const submissionStore = useSubmissionStore();

watchEffect(() =>
  mainScrollParent.value?.addEventListener("scroll", (ev: Event) => {
    let currentSize = 0;

    const actualTermclassIndex = sizes.findIndex((termclass) => {
      currentSize += termclass.size * 332;
      return currentSize > (ev.target as any)?.scrollLeft;
    });

    if (submissionStore.$state.currentTermclassIndex !== actualTermclassIndex) {
      submissionStore.$state.currentTermclassIndex = actualTermclassIndex;
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

watchEffect(() => {
  scrollToTermclassIndex(submissionStore.$state.currentTermclassIndex);
});

watchEffect(
  () =>
    (termclassCursorPointer.value =
      termclassNavigation.value?.getBoundingClientRect()?.bottom - 12)
);
</script>

<template>
  <main ref="mainRef">
    <section
      ref="termclassNavigation"
      class="min-w-fit py-7 px-20 border-b-2 border-[#2E688C] relative flex"
    >
      <div class="flex items-start z-[1] fix">
        <div class="flex flex-col items-center fixed" ref="termclassCursor">
          <div
            id="current-icon"
            class="flex rounded-full p-4 mb-3 w-fit bg-[#52bd8f]"
          >
            <MaterialIcon name="assignment" class="text-white" />
          </div>
          <div class="max-w-[11rem] whitespace-normal">
            <div class="flex text-white mb-1">
              <h5 class="font-semibold mr-1">
                {{ submissionStore.currentTermclass.title }}
              </h5>
              <span class="text-white/50">{{
                submissionStore.currentTermclassCounterIndicator
              }}</span>
            </div>

            <p id="current-subtitle" class="text-[#99BBD0] text-sm">
              {{ submissionStore.currentTermclass.description }}
            </p>
          </div>
          <div
            class="bg-[#2E688C] rounded-full p-[.25rem] fixed"
            :style="{
              top: termclassCursorPointer + 'px',
            }"
          >
            <div class="bg-[#52bd8f] rounded-full p-[.35rem]"></div>
          </div>
          <button
            v-if="submissionStore.currentTermclassIndex > 0"
            @click="submissionStore.previousTermclass"
            class="absolute left-0 top-0 text-[#99BBD0] transition-all hover:text-white text-md p-4"
          >
            <MaterialIcon name="chevron_left" />
          </button>
          <button
            v-if="submissionStore.currentTermclassIndex < sizes.length - 1"
            @click="submissionStore.nextTermclass"
            class="absolute right-0 top-0 text-[#99BBD0] transition-all hover:text-white text-md p-4"
          >
            <MaterialIcon name="chevron_right" />
          </button>
        </div>
      </div>

      <div
        :style="{ width: termclass.size * 20.75 + 'rem' }"
        class="flex items-start"
        v-for="termclass in sizes"
        :key="termclass.name"
        :id="termclass.name + termclass.counter"
      >
        <div class="flex flex-col items-center">
          <div class="flex opacity-40 rounded-full p-4 mb-3 w-fit bg-[#407494]">
            <MaterialIcon name="assignment" class="text-white" />
          </div>
          <div class="opacity-40 max-w-[11rem] whitespace-normal">
            <div class="flex text-[#99BBD0] mb-1">
              <h5 class="font-semibold mr-1">
                {{ termclassesDescription[termclass.name as keyof typeof termclassesDescription].title }}
              </h5>
              <span class="text-[#99BBD0]/50">{{
                counter[termclass.name] === 1
                  ? ""
                  : ` [${termclass.counter}/${counter[termclass.name]}]`
              }}</span>
            </div>
            <p class="text-[#99BBD0] text-sm">
              {{ termclassesDescription[termclass.name as keyof typeof termclassesDescription].description }}
            </p>
          </div>
          <div
            class="bg-[#2E688C] rounded-full p-[.6rem] absolute -bottom-[.6rem]"
          ></div>
        </div>
      </div>
    </section>
  </main>
</template>
