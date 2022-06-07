import { default as api } from "./clientFacade";
import { createOtpRequestStorage } from "./otpRequestStorage";
export { api };

export const serviceApi = await (async () => {
  const clientAccessTokenResponse = await api.auth.authToken({
    type: "client_credentials",
    client_id: "4bc7dba9-46cc-41c2-802a-dcb5a76120c7",
    client_secret: "1234",
    scope: "client.auth:otp",
  });

  const accessToken = clientAccessTokenResponse.data?.access_token;

  const serviceApi = api.withAccessToken(accessToken);

  return serviceApi;
})();

export const otpRequestStorage = await createOtpRequestStorage();
