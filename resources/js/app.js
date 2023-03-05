import { createApp } from "vue";
import App from "./App.vue";
import router from "./router";
import store from "./store";
import VueLazyload from "vue-lazyload";

import BaseSpinner from "./components/UI/BaseSpinner";
import RepliesComponent from "./components/Comments/RepliesComponent";
require("./bootstrap");

const app = createApp(App);
app.use(router);
app.use(store);
app.use(VueLazyload, {
    observer: true,
    observerOptions: {
        rootMargin: "0px",
        threshold: 0.1,
    },
    lazyComponent: true,
});

app.component("base-spinner", BaseSpinner);
app.component("replies-component", RepliesComponent);

app.mount("#app");
