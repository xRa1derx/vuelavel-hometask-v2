import { createRouter, createWebHistory } from "vue-router";

import HomePage from "./pages/HomePage";
import AdminPage from "./pages/AdminPage";

import CategoryComponent from "./components/Category/CategoryComponent";
import TagComponent from "./components/Tag/TagComponent";

import UserComponent from "./components/User/UserComponent";
import CreateUserComponent from "./components/User/CreateUserComponent";

import CommentComponent from "./components/CommentComponent";
import CreatePostComponent from "./components/Post/CreatePostComponent";

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
                { path: 'posts/create', component: CreatePostComponent, name: "posts.create" },
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
