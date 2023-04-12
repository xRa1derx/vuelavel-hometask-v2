import axios from "axios";

export default {
    namespaced: true,
    state: {
        authenticated: false,
        isAdmin: false,
        user: {},
    },
    getters: {
        authenticated(state) {
            return state.authenticated;
        },
        user(state) {
            return state.user;
        },
        isAdmin(state) {
            return state.isAdmin;
        },
    },
    mutations: {
        SET_AUTHENTICATED(state, value) {
            state.authenticated = value;
        },
        SET_USER(state, value) {
            state.user = value;
        },
        SET_ADMIN(state, value) {
            state.isAdmin = value;
        },
    },
    actions: {
        async role({ commit }) {
            await axios
                .get("/api/user")
                .then(({ data }) => {
                    commit("SET_USER", data);
                    if (localStorage.getItem("x_xsrf_token")) {
                        commit("SET_AUTHENTICATED", true);
                    }
                    if (data.is_admin === 1) {
                        commit("SET_ADMIN", true);
                    }
                })
                .catch((res) => {
                    commit("SET_USER", {});
                    commit("SET_AUTHENTICATED", false);
                });
        },
        logout({ commit }) {
            commit("SET_USER", {});
            commit("SET_AUTHENTICATED", false);
            commit("SET_ADMIN", false);
        },
    },
};
