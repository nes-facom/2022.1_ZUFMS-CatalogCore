import { REDIS_ADAPTER_HOST } from "./config";

export const createOtpRequestStorage = async () => {
  return {
    set: (state: string, email: string) =>
      fetch(`http://${REDIS_ADAPTER_HOST}/${state}`, {
        method: "POST",
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
        body: JSON.stringify({ email }),
      }),
    get: (state: string): Promise<string> =>
      fetch(`http://${REDIS_ADAPTER_HOST}/${state}`)
        .then((response) => response.json())
        .then(({ email }) => email),
  };
};
