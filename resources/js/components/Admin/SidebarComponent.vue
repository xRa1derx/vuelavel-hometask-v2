<template>
    <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex justify-content-center">
            <div class="info pl-1">
                <router-link class="nav-link" :to="{ name: 'chat.admin' }"
                    >New messages</router-link
                >
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
                        :style="
                            $route.params.id == user.id
                                ? 'background-color: #eee; color: #000;'
                                : ''
                        "
                        :to="{
                            name: 'chat.admin.id',
                            params: { id: user.id },
                        }"
                        ><i
                            class="nav-icon fas fa-circle-notch accent-color user-icon"
                        >
                            <i
                                v-if="getOnlineUsers(user.id)"
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
export default {
    data() {
        return {
            currnetStudent: null,
            currnetStudentName: null,
        };
    },
    mounted() {
        this.getUsers();
    },
    computed: {
        users() {
            return this.$store.state.users.filter(
                (user) => user.id !== 1 && user.id != 0
            );
        },
    },
    methods: {
        getUsers() {
            this.$store.dispatch("getUsers");
        },
        getOnlineUsers(id) {
            return this.$store.state.onlineUsers.find((user) => user.id === id);
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
