import { createApp } from "vue";
import App from "./App.vue";
import router from "./router";
import store from "./store";
import VueLazyload from "vue-lazyload";

import BaseSpinner from "./components/UI/BaseSpinner";
require("./bootstrap");

const app = createApp(App);
app.use(router);
app.use(store);
app.use(VueLazyload, {
    lazyComponent: true,
});

app.component("base-spinner", BaseSpinner);

app.mount("#app");
