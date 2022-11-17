import { createRouter, createWebHistory } from "vue-router";

import HomePage from "./pages/HomePage";

const router = createRouter({
    history: createWebHistory(),
    routes: [
        { path: "/", component: HomePage, name: "home" },
        // { path: "/login", component: LoginPage, name: "login" },
        // {
        //     path: "/admin",
        //     component: AdminPage,
        //     name: "admin",
        // },
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
