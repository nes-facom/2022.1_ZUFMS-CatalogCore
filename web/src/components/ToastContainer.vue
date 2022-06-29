<script setup lang="ts">
import { useToastStore } from "@/store/toast";
import type { Message } from "@/store/toast";
import { ref } from "vue";
import MaterialIcon from "./MaterialIcon.vue";
import OpacityTransition from "./transitions/OpacityTransition.vue";
import { wait } from "@/util/async";

const toastStore = useToastStore();

const message = ref<Message>();

toastStore.$onAction(({ name, args }) => {
  if (name === "pushMessage") {
    message.value = args[0];

    wait(message.value?.time ?? toastStore.defaultTime).then(
      () => (message.value = undefined)
    );
  }
});
</script>

<template>
  <OpacityTransition>
    <div v-if="message" class="absolute bottom-0 right-0 z-[2] select-none">
      <div class="bg-[#e9e9e9]/90 backdrop-blur p-5 mb-10 mr-10 rounded">
        <div class="flex items-center">
          <MaterialIcon
            :name="message.iconName"
            :class="`${
              message.colorClass ?? 'text-red-500'
            } text-[1.2rem] mr-3`"
          />
          <div class="flex flex-col">
            <h3
              :class="`${
                message.colorClass ?? 'text-red-500'
              } font-semibold text-lg`"
            >
              {{ message.title }}
            </h3>
            <p class="text-black/50" v-if="message.description">
              {{ message.description }}
            </p>
          </div>
        </div>
      </div>
    </div>
  </OpacityTransition>
</template>
