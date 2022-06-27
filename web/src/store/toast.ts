import { defineStore } from "pinia";

export type Message = {
  iconName: "error" | "warning";
  colorClass?: string;
  title: string;
  description?: string;
  time?: number;
};

export const useToastStore = defineStore("toastStore", {
  getters: {
    defaultTime: () => 2000,
  },
  actions: {
    pushMessage(message: Message) {
      return message;
    },
  },
});
