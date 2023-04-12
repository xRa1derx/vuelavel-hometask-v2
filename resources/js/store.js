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
            commentIdToDelete: null,
            quickTextarea: null,
            editCommentTextarea: null,
            isLoginOpen: false,
        };
    },
    mutations: {
        setUsers(state, users) {
            state.users = users;
        },
        setQuickTextarea(state, quickTextarea) {
            state.quickTextarea = quickTextarea;
        },
        setEditCommentTextarea(state, editCommentTextarea) {
            state.editCommentTextarea = editCommentTextarea;
        },
        setLoginOpen(state) {
            state.isLoginOpen = !state.isLoginOpen;
        },
        setCommentIdToDelete(state, commentIdToDelete) {
            state.commentIdToDelete = commentIdToDelete;
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
        getEditCommentTextarea(context, id) {
            context.commit("setEditCommentTextarea", id);
        },
        loginOpen(context) {
            context.commit("setLoginOpen");
        },
        getCommentIdToDelete(context, id) {
            context.commit("setCommentIdToDelete", id);
        },
    },
    getters: {
        users(state) {
            return state.users;
        },
        currentTextarea(state) {
            return state.quickTextarea;
        },
        loginOpen(state) {
            return state.isLoginOpen;
        },
    },
});

export default store;
