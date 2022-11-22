import { createRouter, createWebHistory } from "vue-router";

import HomePage from "./pages/HomePage";
import AdminPage from "./pages/AdminPage";
import CategoryComponent from "./components/CategoryComponent";
import TagComponent from "./components/TagComponent";
import CommentComponent from "./components/CommentComponent";
import PostComponent from "./components/PostComponent";
import UserComponent from "./components/UserComponent";

const router = createRouter({
    history: createWebHistory(),
    routes: [
        { path: "/", component: HomePage, name: "home" },
        // { path: "/login", component: LoginPage, name: "login" },
        {
            path: "/admin",
            component: AdminPage,
            name: "admin",
            children: [
                { path: "categories", component: CategoryComponent },
                { path: "tags", component: TagComponent },
                { path: "users", component: UserComponent },
                { path: "comments", component: CommentComponent },
                { path: "posts", component: PostComponent },
            ],
        },
        // {
        //     path: "/admin/users",
        //     component: UserList,
        //     name: "admin.users",
        // },
        // {
        //     path: "/admin/users/chat/:id",
        //     component: UserChat,
        //     name: "admin.users.chat.id",
        //     props: true,
        //     children: [{ path: "edit", component: UserEdit }],
        // },
        // {
        //     path: "/admin/users/register",
        //     component: RegisterPage,
        //     name: "register",
        // },
        // {
        //     path: "/chat",
        //     component: UserPage,
        //     name: "chat",
        // },
        // {
        //     path: "/:notFound(.*)",
        //     component: NotFound,
        //     name: "notfound",
        // },
    ],
});

export default router;
