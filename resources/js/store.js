import axios from "axios";
import { createStore } from "vuex";
import createPersistedState from "vuex-persistedstate";
import auth from "./auth";
const store = createStore({
    modules: {
        auth,
    },
    plugins: [createPersistedState()],
    state() {
        return {
            users: [],
        };
    },
    mutations: {
        setUsers(state, users) {
            state.users = users;
        },
    },
    actions: {
        getUsers({ commit }) {
            axios.get("/api/admin/users").then((users) => {
                commit("setUsers", users.data);
            });
        },
    },
    getters: {
        users(state) {
            return state.users;
        },
    },
});

export default store;
