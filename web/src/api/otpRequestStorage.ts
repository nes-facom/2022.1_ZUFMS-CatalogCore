export const createOtpRequestStorage = async () => {
  return {
    set: (state: string, email: string) =>
      fetch(`http://localhost:3030/${state}`, {
        method: "POST",
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
        body: JSON.stringify({ email }),
      }),
    get: (state: string): Promise<string> =>
      fetch(`http://localhost:3030/${state}`)
        .then((response) => response.json())
        .then(({ email }) => email),
  };
};
