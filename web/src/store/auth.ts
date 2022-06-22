import { User as UserApi, userApi } from "@/api";
import { wait } from "@/util/async";
import { defineStore } from "pinia";

export type User = Required<UserApi>;

type State = {};

export const useAuthStore = defineStore("authStore", {
  state: () => ({} as State),
  actions: {
    login() {
      return 0;
    },
    logout() {
      return 0;
    },
  },
});
