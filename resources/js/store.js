import axios from "axios";
import { createStore } from "vuex";
import auth from "./auth";
const store = createStore({
    modules: {
        auth,
    },
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
        authenticated(state) {
            return state.authenticated;
        },
        users(state) {
            return state.users;
        },
    },
});

export default store;
