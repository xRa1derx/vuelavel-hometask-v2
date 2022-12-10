<template>
    <div class="col">
        <div class="card">
            <img
                class="card-img-top rounded-circle p-3"
                :src="`/uploads/${user.avatar || 'no-user-image.png'}`"
                alt="Card image cap"
            />
            <div class="card-body">
                <h5 class="card-title">{{ user.name }}</h5>
                <p class="card-text">
                    {{ user.email }}
                </p>
                <p class="card-text">
                    <small class="text-muted">Last updated 3 mins ago</small>
                </p>
            </div>
            <button
                @click="userAction(user)"
                type="button"
                class="btn delete-user"
                :title="`Delete ${user.name}'s profile`"
                data-toggle="modal"
                data-target="#deleteModal"
            >
                <i :class="['nav-icon fas fa-minus-circle']"></i>
            </button>

            <delete-component @deleteConfirm="deleteConfirm">
                <template v-slot:default>{{ currentUserName }}'s</template>
                <template v-slot:type>profile</template>
            </delete-component>
            <button
                @click="userAction(user)"
                class="btn edit-user"
                :title="`Edit ${user.name}'s profile`"
                type="button"
                data-toggle="modal"
                data-target="#exampleModalCenter"
            >
                <i :class="['nav-icon fas fa-pen-square']"></i>
            </button>
            <edit-user-component
                @updateUser="updateUser"
                :currentUserId="currentUserId"
                :currentUserName="currentUserName"
                :currentUserEmail="currentUserEmail"
                :currentUserAvatar="currentUserAvatar"
            >
            </edit-user-component>
        </div>
    </div>
</template>

<script>
import DeleteComponent from "../DeleteComponent.vue";
import EditUserComponent from "./EditUserComponent.vue";
export default {
    components: { DeleteComponent, EditUserComponent },
    props: [
        "user",
        "currentUserId",
        "currentUserName",
        "currentUserEmail",
        "currentUserAvatar",
    ],
    emits: ["userAction", "deleteConfirm", "updateUser"],
    data() {
        return {};
    },
    methods: {
        deleteConfirm() {
            this.$emit("deleteConfirm");
        },
        userAction(user) {
            this.$emit("userAction", user);
        },
        updateUser() {
            this.$emit("updateUser");
        },
    },
};
</script>

<style scoped>
.card {
    background-color: #212529;
    font-size: clamp(0.6rem, 1.3vw, 5rem);
}

.delete-user {
    position: absolute;
    right: 0;
    top: 0;
}

.edit-user {
    position: absolute;
    right: 0;
    bottom: 0;
}

.nav-icon {
    color: #6c757d;
}

.edit-user:hover > .nav-icon,
.delete-user:hover > .nav-icon {
    color: #ff7600;
}
</style>
