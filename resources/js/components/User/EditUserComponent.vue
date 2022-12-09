<template>
    <div
        class="modal fade"
        id="exampleModalCenter"
        tabindex="-1"
        role="dialog"
        aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true"
    >
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content bg-dark-blue">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">
                        Edit {{ currentUserName }}'s profile
                    </h5>
                    <button
                        type="button"
                        class="close text-white"
                        data-dismiss="modal"
                        aria-label="Close"
                    >
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="table-wrapper">
                        <form enctype="multipart/form-data">
                            <div class="row mb-3">
                                <div class="info-wrapper d-flex">
                                    <img
                                        ref="imageSrc"
                                        class="card-img-top rounded-circle p-3 w-50"
                                        :src="`/uploads/${
                                            currentUserAvatar ||
                                            'no-user-image.png'
                                        }`"
                                        alt="Card image cap"
                                    />
                                    <div
                                        class="d-flex flex-column justify-content-center"
                                    >
                                        <div class="name form-group">
                                            <input
                                                type="text"
                                                class="form-control"
                                                name="name"
                                                id="name"
                                                v-model="name"
                                            />
                                        </div>
                                        <div class="email form-group">
                                            <input
                                                type="email"
                                                class="form-control"
                                                name="email"
                                                id="email"
                                                v-model="email"
                                            />
                                        </div>
                                        <div class="password form-group mb-0">
                                            <input
                                                class="form-control"
                                                name="password"
                                                id="password"
                                            />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <crop-avatar-component
                                @clearCroppedAvatar="clearCroppedAvatar"
                                @selectedToCropEmitter="selectedToCropEmitter"
                                @toBlob="toBlob"
                                @isCropped="isCropped"
                                ref="childComponent"
                            >
                                <template v-slot:default
                                    >Change avatar</template
                                >
                            </crop-avatar-component>

                            <button
                                v-show="
                                    !selectToCrop &&
                                    !cropped &&
                                    clearClick &&
                                    currentUserAvatar
                                "
                                class="btn btn-info"
                                @click.prevent="clearAvatar"
                            >
                                Clear Avatar
                            </button>
                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <button
                        @click="closeEdit()"
                        type="button"
                        class="btn btn-secondary"
                        data-dismiss="modal"
                    >
                        Close
                    </button>
                    <button
                        @click.prevent="updateUser()"
                        type="submit"
                        class="btn"
                        :class="!isChanged ? 'btn-secondary' : 'btn-warning'"
                        :disabled="!isChanged"
                    >
                        {{ isChanged ? "Save changes" : "No changes" }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";
import CropAvatarComponent from "./CropAvatarComponent.vue";
export default {
    components: {
        CropAvatarComponent,
    },
    props: [
        "currentUserId",
        "currentUserName",
        "currentUserEmail",
        "currentUserAvatar",
    ],
    emits: ["updateUser"],
    data() {
        return {
            avatar: "",
            name: "",
            email: "",
            selectToCrop: false,
            isChanged: false,
            cropped: false,
            clearClick: true,
        };
    },
    methods: {
        callMethodInChildComponent() {
            this.$refs.childComponent.clearCroppedAvatar();
        },
        updateUser() {
            this.isChanged = false;
            if (this.avatar === null || this.avatar == "") {
                let data = new FormData();
                data.append("avatar", "");
                data.append("name", this.name);
                data.append("email", this.email);
                axios
                    .post(`/api/admin/users/${this.currentUserId}`, data, {
                        _method: "patch",
                    })
                    .then((res) => {
                        this.$emit("updateUser");
                    });
            } else {
                this.cropped = false;
                this.clearClick = true;
                let data = new FormData();
                data.append("avatar", this.avatar);
                data.append("name", this.name);
                data.append("email", this.email);
                axios
                    .post(`/api/admin/users/${this.currentUserId}`, data, {
                        _method: "patch",
                    })
                    .then((res) => {
                        this.$emit("updateUser");
                        this.callMethodInChildComponent();
                    });
            }
        },
        clearAvatar() {
            this.isChanged = true;
            this.avatar = null;
            this.clearClick = false;
            this.$refs.imageSrc.src = "/uploads/no-user-image.png";
        },
        closeEdit() {
            if (this.cropped || this.selectToCrop) {
                this.cropped = false;
                this.selectToCrop = false;
                this.callMethodInChildComponent();
            }
            console.log(this.isChanged);
            // this.avatar = null;
            this.clearClick = true;
            this.$refs.imageSrc.src = `/uploads/${
                this.currentUserAvatar || "no-user-image.png"
            }`;
            setTimeout(() => {
                this.isChanged = false;
            }, 500);
        },
        selectedToCropEmitter(value) {
            this.selectToCrop = value.selectToCrop;
        },
        toBlob(blob) {
            this.avatar = blob;
        },
        isCropped(val) {
            this.cropped = val;
        },
        clearCroppedAvatar(avatar) {
            this.avatar = avatar;
        },
    },
    watch: {
        currentUserName(val) {
            this.name = val;
        },
        currentUserEmail(val) {
            this.email = val;
        },
        name() {
            if (this.name !== this.currentUserName) {
                this.isChanged = true;
            } else {
                this.isChanged = false;
            }
        },
        email() {
            if (this.email !== this.currentUserEmail) {
                this.isChanged = true;
            } else {
                this.isChanged = false;
            }
        },
        avatar(val, oldVal) {
            console.log(val);
            console.log(oldVal);
            if (this.avatar !== this.currentUserAvatar) {
                this.isChanged = true;
            } else {
                this.isChanged = false;
            }
        },
    },
};
</script>

<style scoped>
.modal-header {
    border-bottom: 1px solid var(--clr-dark-grey-strip);
}
.modal-footer {
    border-top: 1px solid var(--clr-dark-grey-strip);
}

.table-wrapper {
    min-height: 50vh; /* for spinner */
    height: 100%;
}

form {
    padding: 2rem;
}

.avatar {
    border-top: 1px solid var(--clr-dark-grey-strip);
    border-bottom: 1px solid var(--clr-dark-grey-strip);
}

.avatar-label {
    width: min-content;
    padding-right: 1rem;
}
.avatar-input {
    width: min-content;
}

.avatar-image {
    width: 100%;
    border-radius: 50%;
}

@media (max-width: 450px) {
    .info-wrapper {
        display: block !important;
        text-align: center;
    }
}
</style>
