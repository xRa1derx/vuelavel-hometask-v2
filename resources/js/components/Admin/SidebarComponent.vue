<template>
    <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <!-- <img
                    src="../../assets/images/avatar_admin.png"
                    class="img-circle elevation-2"
                    alt="User Image"
                /> -->
            </div>
            <div class="info">
                <a href="#" class="d-block">{{
                    currnetStudentName || "Chat"
                }}</a>
            </div>
        </div>
        <nav class="sidebar-menu mt-2 overflow-auto">
            <ul
                class="nav nav-pills nav-sidebar flex-column"
                data-widget="treeview"
                role="menu"
                data-accordion="false"
            >
                <li class="nav-header">
                    <router-link class="nav-link" :to="{ name: 'admin' }">
                        <strong>BACK</strong
                        ><i class="nav-icon fas fa-arrow-left accent-color"></i
                    ></router-link>
                </li>
                <li class="nav-item" v-for="user in users" :key="user.id">
                    <router-link
                        class="nav-link"
                        :to="{
                            name: 'chat.admin.id',
                            params: { id: user.id },
                        }"
                        ><i
                            class="nav-icon fas fa-circle-notch accent-color user-icon"
                        >
                            <i
                                v-if="user.status === 'online'"
                                class="online"
                            ></i></i
                        >{{ user.name }}
                    </router-link>
                </li>
            </ul>
        </nav>
    </div>
</template>

<script>
import axios from "axios";
// export default {
//     setup() {
//         const students = [
//             { name: "Oleg", id: 1 },
//             {
//                 name: "Varvara",
//                 id: 2,
//                 status: "online",
//                 messages: [{ id: 1, text: "124" }],
//             },
//             {
//                 name: "Lera",
//                 id: 3,
//                 status: "online",
//                 messages: [{ id: 1, text: "124" }],
//             },
//             { name: "Nika", id: 4, messages: [{ id: 1, text: "124" }] },
//             { name: "John", id: 5, messages: [{ id: 1, text: "124" }] },
//             {
//                 name: "Olya",
//                 id: 6,
//                 status: "online",
//                 messages: [{ id: 1, text: "124" }],
//             },
//             { name: "Anya", id: 7, messages: [{ id: 1, text: "124" }] },
//             { name: "Vera", id: 8, messages: [{ id: 1, text: "124" }] },
//             {
//                 name: "Masha",
//                 id: 9,
//                 status: "online",
//                 messages: [{ id: 1, text: "124" }],
//             },
//             { name: "Anton", id: 10, messages: [{ id: 1, text: "124" }] },
//             { name: "Sergey", id: 11, messages: [{ id: 1, text: "124" }] },
//             {
//                 name: "Pavel",
//                 id: 12,
//                 status: "online",
//                 messages: [{ id: 1, text: "124" }],
//             },
//         ];
//         return {
//             students,
//         };
//     },
// };
export default {
    data() {
        return {
            currnetStudent: null,
            currnetStudentName: null,
            users: [],
        };
    },
    mounted() {
        this.getUsers();
    },
    methods: {
        getUsers() {
            axios
                .get("/api/admin/users")
                .then(
                    (res) =>
                        (this.users = res.data.filter(
                            (user) => user.id !== 1 && user.id != 0
                        ))
                );
        },
    },
};
</script>

<style scoped>
.sidebar-menu {
    height: 60vh;
}
.online {
    position: absolute;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    margin: auto;
    width: 14px;
    height: 14px;
    border-radius: 50px;
    background-color: rgba(255, 255, 255, 0.856);
    z-index: -1;
    animation: 1.5s ease infinite alternate flick;
}

@keyframes flick {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

.user-icon {
    position: relative;
}

.sidebar-menu::-webkit-scrollbar {
    width: 0.5rem;
    background-color: #242424f6;
    /* border-radius: 10px; */
}
.sidebar-menu::-webkit-scrollbar:horizontal {
    height: 12px;
    margin-right: 20px;
}
.sidebar-menu::-webkit-scrollbar-thumb {
    background-color: #efe4e4;
    border: 1px solid #000000;
    /* border-radius: 16px; */
}
.sidebar-menu::-webkit-scrollbar-thumb:hover {
    background-color: #d3c9c9;
    border: 1px solid #333333;
}
.sidebar-menu::-webkit-scrollbar-thumb:active {
    background-color: #9b8a8b;
    border: 1px solid #333333;
}
</style>
