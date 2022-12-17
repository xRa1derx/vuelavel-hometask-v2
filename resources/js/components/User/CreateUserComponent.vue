<template>
    <div class="table-wrapper mb-5">
        <form class="col-md-4 mx-auto" enctype="multipart/form-data">
            <div class="row mb-3">
                <div
                    class="name form-group w-100 d-flex justify-content-between"
                >
                    <label for="name" class="align-self-center mb-0 mr-1"
                        >Name</label
                    >
                    <input
                        type="text"
                        class="form-control w-75"
                        id="name"
                        v-model.trim="name"
                    />
                </div>
            </div>
            <div class="row mb-3">
                <div
                    class="email form-group w-100 d-flex justify-content-between"
                >
                    <label for="email" class="align-self-center mb-0 mr-1"
                        >Email</label
                    >
                    <input
                        type="email"
                        class="form-control w-75"
                        id="email"
                        v-model.trim="email"
                    />
                </div>
            </div>
            <div class="row mb-3">
                <div
                    class="password form-group w-100 d-flex justify-content-between"
                >
                    <label for="password" class="align-self-center mb-0 mr-3"
                        >Password</label
                    >
                    <input
                        type="password"
                        class="form-control w-75"
                        id="password"
                        v-model.trim="password"
                    />
                </div>
            </div>

            <crop-avatar-component
                @isSelectToCrop="isSelectToCrop"
                @isCropped="isCropped"
                @toBlob="toBlob"
                @clearCroppedAvatar="clearCroppedAvatar"
                ><template v-slot:default
                    >*Select avatar</template
                ></crop-avatar-component
            >
            <div class="row justify-content-between">
                <p class="align-self-end mb-0 text-muted">*optional</p>
                <button
                    @click.prevent="addUser()"
                    type="submit"
                    class="btn btn-warning"
                    :disabled="!selectToCrop"
                >
                    <i class="nav-icon fas fa-solid fa-plus"></i>
                </button>
            </div>
        </form>
    </div>
</template>

<script>
import axios from "axios";
import CropAvatarComponent from "./CropAvatarComponent.vue";
export default {
    components: {
        CropAvatarComponent,
    },
    data() {
        return {
            avatar: "",
            name: "",
            email: "",
            password: null,
            selectToCrop: true,
        };
    },
    methods: {
        addUser() {
            let data = new FormData();
            data.append("avatar", this.avatar);
            data.append("name", this.name);
            data.append("email", this.email);
            data.append("password", this.password);
            axios
                .post("/api/admin/users/create", data)
                .then((res) => this.$router.push({ name: "users" }));
        },
        clearCroppedAvatar() {
            this.avatar = "";
        },
        isSelectToCrop(value) {
            this.selectToCrop = !value;
        },
        isCropped(value) {
            this.selectToCrop = value;
        },
        toBlob(blob) {
            this.avatar = blob;
        },
    },
};
</script>

<style scoped>
.table-wrapper {
    min-height: 50vh; /* for spinner */
    height: 100%;
}

form {
    background-color: #212529;
    padding: 2rem;
    border: 1px solid #383f45;
}

.cropper-view-box {
    border-radius: 50%;
}
</style>
