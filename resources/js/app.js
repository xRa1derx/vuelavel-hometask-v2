import { createApp } from "vue";
import App from "./App.vue";
import router from "./router";

import BaseSpinner from "./components/UI/BaseSpinner";
require("./bootstrap");



const app = createApp(App);
app.use(router);

app.component("base-spinner", BaseSpinner);

app.mount("#app");
