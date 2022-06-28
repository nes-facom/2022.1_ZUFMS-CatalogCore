import { otpRequestStorage, serviceApi, User as UserApi, userApi } from "@/api";
import { wait } from "@/util/async";
import VueJwtDecode from "vue-jwt-decode";
import { defineStore } from "pinia";
import { generateOtpState } from "@/util/auth";

export type User = Required<UserApi>;

type State = {
  userInfo: any;
  accessToken: string;
  parsedAccessToken: any;
  scopes: string[];

  loading: boolean;
  error?: Error;
};

export const useAuthStore = defineStore("authStore", {
  state: () =>
    ({
      userInfo: {},
      loading: false,
    } as State),
  getters: {
    accessToken: () => localStorage.getItem("_at") ?? "",
    parsedAccessToken: (state) => VueJwtDecode.decode(state.accessToken),
    scopes: (state) => state.parsedAccessToken.scope.split(" "),
    isAuthenticated: (state) =>
      state.accessToken !== "" &&
      state.parsedAccessToken?.exp * 1000 > Date.now(),
    username: (state) => state.userInfo?.email?.split("@")?.[0],
  },
  actions: {
    async loginWithOtp(email: string, otp: string, scope: string) {
      try {
        this.loading = true;

        const response = await serviceApi.auth.authToken({
          type: "otp",
          otp_method: "email",
          email,
          otp,
          scope,
        });

        localStorage.setItem("_at", response.data.access_token ?? "");
      } catch (err) {
        this.error =
          err instanceof Error ? err : new Error((err as any).toString());
      } finally {
        this.loading = false;
      }
    },

    async requestOtp(email: string, scope: string) {
      const state = generateOtpState();

      try {
        this.loading = true;

        await serviceApi.auth.authOtp({
          otp_method: "email",
          email,
          state,
          scope,
        });

        await otpRequestStorage.set(state, email);
      } catch (err) {
        this.error =
          err instanceof Error ? err : new Error((err as any).toString());
      } finally {
        this.loading = false;
      }
    },

    logout() {
      localStorage.removeItem("_at");
    },
    async fetchUserInfo() {
      try {
        const response = await fetch("https://localhost/v1/auth/userinfo", {
          headers: {
            Authorization: "Bearer " + this.accessToken,
          },
        });

        const userInfo = await response.json();

        this.userInfo = userInfo;
      } catch (err) {
        this.logout();
      }
    },
  },
});
