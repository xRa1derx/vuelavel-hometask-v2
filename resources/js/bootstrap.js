const { default: store } = require("./store");

window._ = require("lodash");

try {
    require("bootstrap");
} catch (e) {}

window.axios = require("axios");

window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";
window.axios.defaults.withCredentials = true;

window.axios.interceptors.response.use({}, (err) => {
    if (err.response.status === 401 || err.response.status === 419) {
        const token = localStorage.getItem("x_xsrf_token");
        if (token) {
            localStorage.removeItem("x_xsrf_token");
        }
        store.state.auth.authenticated = false;
        store.state.auth.isAdmin = false;
        store.state.auth.user = {};
    }
    return Promise.reject(err);
});

import Echo from "laravel-echo";

window.Pusher = require("pusher-js");

// window.Echo = new Echo({
//     broadcaster: "pusher",
//     key: process.env.MIX_PUSHER_APP_KEY,
//     wsHost: window.location.hostname,
//     wsPort: 6001,
//     wssPort: 6001,
//     cluster: process.env.MIX_PUSHER_APP_CLUSTER,
//     forceTLS: false,
//     disableStats: true,
//     enabledTransports: ['ws', 'wss'],
// });

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: process.env.MIX_PUSHER_APP_KEY,
    cluster: process.env.MIX_PUSHER_APP_CLUSTER,
    forceTLS: true
});
