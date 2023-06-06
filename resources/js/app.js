import { createApp } from "vue";
import App from "./App.vue";
import router from "./router";
import store from "./store";
import VueLazyload from "vue-lazyload";

import BaseSpinner from "./components/UI/BaseSpinner";
import RepliesComponent from "./components/Comments/RepliesComponent";

import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import { library } from "@fortawesome/fontawesome-svg-core";
import { fas } from "@fortawesome/free-solid-svg-icons";

library.add(fas);

require("./bootstrap");

const preload = async () => {
    const token = localStorage.getItem("x_xsrf_token");
    if (token) {
        await store.dispatch("auth/role");
    }
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

    app.component("font-awesome-icon", FontAwesomeIcon);
    app.component("base-spinner", BaseSpinner);
    app.component("replies-component", RepliesComponent);

    app.mount("#app");
};

preload();
