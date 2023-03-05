import { createRouter, createWebHistory } from "vue-router";
import store from "./store";

import HomePage from "./pages/HomePage";
import AdminPage from "./pages/AdminPage";
import DashboardPage from "./pages/DashboardPage";

import CategoryComponent from "./components/Category/CategoryComponent";
import TagComponent from "./components/Tag/TagComponent";

import UserComponent from "./components/User/UserComponent";
import CreateUserComponent from "./components/User/CreateUserComponent";

import CommentComponent from "./components/Comments/CommentComponent";

import ShowPostComponent from "./components/Post/ShowPostComponent";
import CreatePostComponent from "./components/Post/CreatePostComponent";
import EditPostComponent from "./components/Post/EditPostComponent";

const router = createRouter({
    history: createWebHistory(),
    routes: [
        {
            path: "/",
            component: HomePage,
            name: "home",
            meta: {
                middleware: "guest",
                title: "Home",
            },
        },
        {
            path: "/admin",
            component: AdminPage,
            name: "admin",
            meta: {
                middleware: "admin",
                title: "Admin",
            },
            children: [
                {
                    path: "categories",
                    component: CategoryComponent,
                    name: "categories",
                },
                { path: "tags", component: TagComponent },
                { path: "users", component: UserComponent, name: "users" },
                {
                    path: "users/create",
                    component: CreateUserComponent,
                    name: "users.create",
                },
                { path: "comments", component: CommentComponent },
                { path: "posts", component: ShowPostComponent, name: "posts" },
                {
                    path: "posts/create",
                    component: CreatePostComponent,
                    name: "posts.create",
                },
                {
                    path: "posts/edit/:id",
                    component: EditPostComponent,
                    name: "posts.edit.id",
                },
            ],
        },
        {
            path: "/dashboard",
            component: DashboardPage,
            name: "dashboard",
            meta: {
                middleware: "auth",
                title: "Dashboard",
            },
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

router.beforeEach((to, from, next) => {
    document.title = `${to.meta.title} - ${process.env.MIX_APP_NAME}`;
    if (to.meta.middleware == "admin") {
        if (store.state.auth.isAdmin) {
            next();
        } else {
            next({ name: "home" });
        }
    } else if (to.meta.middleware == "auth") {
        if (store.state.auth.authenticated && !store.state.auth.isAdmin) {
            next();
        } else {
            next({ name: "home" });
        }
    } else {
        next();
    }
});

export default router;
