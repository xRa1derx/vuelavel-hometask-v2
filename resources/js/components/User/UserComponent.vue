<template>
    <div class="row row-cols-2 row-cols-md-4 g-4">
        <user-card-component
            v-for="user in users"
            :user="user"
            :key="user.id"
            :currentUserId="currentUserId"
            :currentUserName="currentUserName"
            :currentUserEmail="currentUserEmail"
            :currentUserAvatar="currentUserAvatar"
            @deleteConfirm="deleteConfirm"
            @userAction="userAction"
            @updateUser="updateUser"
        ></user-card-component>
    </div>
</template>

<script>
import axios from "axios";
import UserCardComponent from "./UserCardComponent.vue";
export default {
    components: { UserCardComponent },
    data() {
        return {
            users: [],
            currentUserId: null,
            currentUserName: null,
            currentUserEmail: null,
            currentUserAvatar: null,
        };
    },
    mounted() {
        this.getUsers();
    },
    methods: {
        getUsers() {
            axios.get("/api/admin/users").then((res) => {
                this.users = res.data;
            });
        },
        userAction(user) {
            this.currentUserId = user.id;
            this.currentUserName = user.name;
            this.currentUserEmail = user.email;
            this.currentUserAvatar = user.avatar;
        },
        deleteConfirm() {
            axios
                .delete(`/api/admin/users/${this.currentUserId}`)
                .then(() => (this.currentUserId = null));
            this.users = this.users.filter(
                (user) => user.id !== this.currentUserId
            );
        },
        updateUser() {
            this.getUsers();
        },
    },
};
</script>

<style scoped></style>
