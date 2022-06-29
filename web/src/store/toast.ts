import { defineStore } from "pinia";

export type Message = {
  iconName: "error" | "warning" | "done";
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

export const descriptionFromResponseError = (
  err: unknown
): string | undefined =>
  (err as any)?.response?.data.errors?.reduce(
    (message: string, e: { title: string; description: string }) =>
      `${message}${e.title}: ${e.description}\n`,
    ""
  );
