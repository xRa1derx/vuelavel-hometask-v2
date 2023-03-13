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
            quickTextarea: null,
        };
    },
    mutations: {
        setUsers(state, users) {
            state.users = users;
        },
        setQuickTextarea(state, quickTextarea) {
            state.quickTextarea = quickTextarea;
        },
    },
    actions: {
        getUsers({ commit }) {
            axios.get("/api/admin/users").then((users) => {
                commit("setUsers", users.data);
            });
        },
        getQuickTextarea(context, id) {
            context.commit("setQuickTextarea", id);
        },
    },
    getters: {
        users(state) {
            return state.users;
        },
        currentTextarea(state) {
            return state.quickTextarea;
        },
    },
});

export default store;
