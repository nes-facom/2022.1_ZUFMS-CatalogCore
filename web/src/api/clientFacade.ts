import { UsersApi, OccurrencesApi, AuthApi } from "@/../client";

const setup = () => {
  const isJsonMime = () => true;

  const withAccessToken = (accessToken?: string) => ({
    users: new UsersApi({ accessToken, isJsonMime }),
    occurrences: new OccurrencesApi({ accessToken, isJsonMime }),
    auth: new AuthApi({ accessToken, isJsonMime }),
  });

  return { ...withAccessToken(), withAccessToken };
};

export default setup();
