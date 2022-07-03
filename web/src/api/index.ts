import { default as api } from "./clientFacade";
import { CLIENT_ID, CLIENT_SECRET } from "./config";
import { createOtpRequestStorage } from "./otpRequestStorage";
export { api };
export * from '@/../client';

export const serviceApi = await (async () => {
  const clientAccessTokenResponse = await api.auth.authToken({
    type: "client_credentials",
    client_id: CLIENT_ID,
    client_secret: CLIENT_SECRET,
    scope: "client.auth:otp",
  });

  const accessToken = clientAccessTokenResponse.data?.access_token;

  const _api = api.withAccessToken(accessToken);

  return _api;
})();

export const userApi = await (async () => {
  const retrieveAccessToken = () => localStorage.getItem("_at") ?? ""

  const proxy = new Proxy(api, {
    get(target, p, receiver) {
      return api.withAccessToken(retrieveAccessToken())[
        p as keyof Omit<typeof api, "withAccessToken">
      ];
    },
  })

  return proxy;
})();

export const otpRequestStorage = await createOtpRequestStorage();
