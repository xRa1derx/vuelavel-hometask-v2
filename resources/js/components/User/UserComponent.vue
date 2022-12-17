<template>
    <div>
        <div class="row row-cols-2 row-cols-md-6 g-4">
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
    </div>
</template>

<script>
import axios from "axios";
import { mapActions, mapGetters } from "vuex";
import UserCardComponent from "./UserCardComponent.vue";
export default {
    components: { UserCardComponent },
    data() {
        return {
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
        ...mapActions(["getUsers"]),
        userAction(user) {
            this.currentUserId = user.id;
            this.currentUserName = user.name;
            this.currentUserEmail = user.email;
            this.currentUserAvatar = user.avatar;
        },
        deleteConfirm() {
            axios
                .delete(`/api/admin/users/${this.currentUserId}`)
                .then(() => {});
            this.$store.state.users = this.$store.state.users.filter(
                (user) => user.id !== this.currentUserId
            );
        },
        updateUser() {
            this.getUsers();
        },
    },
    computed: {
        ...mapGetters(["users"]),
    },
};
</script>

<style scoped></style>
