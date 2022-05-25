import { createApp } from "vue";
import App from "./App.vue";
// import { AcervoApi, AuthApi, Configuration, UserApi } from "./client";
import router from "./router";

const app = createApp(App);

app.use(router);

// const apiConfiguration = new Configuration({ accessToken: "" });
// const apiClient = {
//   user: new UserApi(apiConfiguration),
//   acervo: new AcervoApi(apiConfiguration),
//   auth: new AuthApi(apiConfiguration),
// };
// 
// setInterval(() => {
//   const formData = new FormData();
//   formData.append("blob", new Blob(["Hello World!\n"]), "test");
// 
//   apiClient.acervo
//     .processAcervoEntriesFile({
//       body: formData,
//     })
//     .then(console.log)
//     .catch(console.error);
// }, 5000);
// 
// app.provide("api_client", apiClient);
// 
app.mount("#app");
