<template>
    <div
        ref="closeEdit"
        class="modal fade"
        id="exampleModalLong"
        tabindex="-1"
        role="dialog"
        aria-labelledby="exampleModalLongTitle"
        aria-hidden="true"
    >
        <div
            class="modal-dialog modal-dialog-centered modal-lg"
            role="document"
        >
            <div class="modal-content bg-dark-blue">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">
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
                            <div v-if="!cropper" class="row mb-3">
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
                                        class="d-flex flex-column justify-content-center mx-auto"
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
                                                v-model="password"
                                            />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <crop-avatar-component
                                ref="cropAvatarClear"
                                @toBlob="toBlob"
                                @clearCroppedAvatar="clearCroppedAvatar"
                                @isCropped="isCropped"
                                @isSelectToCrop="isSelectToCrop"
                                @isCropping="isCropping"
                                :currentUserAvatar="currentUserAvatar"
                            >
                                <template v-slot:default
                                    >Change avatar</template
                                >
                            </crop-avatar-component>
                            <button
                                v-show="avatar && clearAvatarBtn"
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
                        type="button"
                        class="btn btn-secondary"
                        data-dismiss="modal"
                    >
                        Close
                    </button>
                    <button
                        data-toggle="modal"
                        data-target="#confirmToEdit"
                        type="button"
                        class="btn"
                        :disabled="!isChanged"
                        :class="!isChanged ? 'btn-secondary' : 'btn-warning'"
                    >
                        {{ isChanged ? "Save changes" : "No changes" }}
                    </button>
                </div>
                <save-edit-component>
                    <template v-slot:default>
                        <button
                            @click.prevent="updateUser()"
                            type="submit"
                            class="btn btn-warning px-5"
                            data-dismiss="modal"
                        >
                            Yes
                        </button>
                    </template>
                </save-edit-component>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";
import CropAvatarComponent from "./CropAvatarComponent.vue";
import SaveEditComponent from "../UI/SaveEditComponent.vue";
export default {
    components: {
        CropAvatarComponent,
        SaveEditComponent,
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
            avatar: null,
            name: "",
            email: "",
            password: null,
            isChanged: false,
            clearAvatarBtn: true,
            cropped: false,
            selectToCrop: false,
            cropper: null,
        };
    },
    mounted() {
        this.observer = new MutationObserver((mutations) => {
            for (const m of mutations) {
                const newValue = m.target.getAttribute(m.attributeName);
                this.$nextTick(() => {
                    this.onClassChange(newValue, m.oldValue);
                });
            }
        });

        this.observer.observe(this.$refs.closeEdit, {
            attributes: true,
            attributeOldValue: true,
            attributeFilter: ["class"],
        });
    },
    beforeDestroy() {
        this.observer.disconnect();
    },
    methods: {
        clearAvatarFromCropComponent() {
            this.$refs.cropAvatarClear.clearCroppedAvatar();
        },
        clearIncorrectFileTypeMessage(){
            this.$refs.cropAvatarClear.clearIncorrectFileTypeMessage();
        },
        clearCroppedAvatar(avatar, cropper) {
            this.avatar = avatar;
            this.cropper = cropper;
            this.clearAvatarBtn = true;
            if (this.avatar == this.currentUserAvatar) {
                this.isChanged = false;
            }
        },
        onClassChange(classAttrValue) {
            const classList = classAttrValue.split(" ");
            if (!classList.includes("show")) {
                this.clearIncorrectFileTypeMessage();
                this.isChanged = false;
                this.clearAvatarBtn = true;
                this.avatar = this.currentUserAvatar;
                if (this.$refs.imageSrc) {
                    this.$refs.imageSrc.src = `/uploads/${
                        this.currentUserAvatar || "no-user-image.png"
                    }`;
                }
                if (this.selectToCrop === true) {
                    this.clearAvatarFromCropComponent();
                    this.selectToCrop = false;
                }
                if (this.cropped === true && this.cropper != null) {
                    this.clearAvatarFromCropComponent();
                    this.cropped = false;
                }
            }
        },
        updateUser() {
            if (this.avatar === null) {
                console.log("null");
                let data = new FormData();
                data.append("name", this.name);
                data.append("email", this.email);
                data.append("password", this.password);
                axios
                    .post(`/api/admin/users/${this.currentUserId}`, data, {
                        _method: "patch",
                    })
                    .then((res) => {
                        {
                            this.$emit("updateUser");
                            this.$refs.imageSrc.src = `/uploads/no-user-image.png`;
                        }
                    })
                    .finally(() => (this.isLoading = false));
            } else if (typeof this.avatar == "string") {
                console.log("string");
                fetch(this.$refs.imageSrc.src)
                    .then((res) => res.blob())
                    .then((blob) => {
                        let data = new FormData();
                        data.append("avatar", blob);
                        data.append("name", this.name);
                        data.append("email", this.email);
                        data.append("password", this.password);
                        axios
                            .post(
                                `/api/admin/users/${this.currentUserId}`,
                                data,
                                {
                                    _method: "patch",
                                }
                            )
                            .then((res) => {
                                this.$emit("updateUser");
                                // this.clearAvatarFromCropComponent();
                            });
                    });
            } else {
                console.log("else");
                let data = new FormData();
                data.append("avatar", this.avatar);
                data.append("name", this.name);
                data.append("email", this.email);
                data.append("password", this.password);
                axios
                    .post(`/api/admin/users/${this.currentUserId}`, data, {
                        _method: "patch",
                    })
                    .then((res) => {
                        this.$emit("updateUser");
                        this.clearAvatarFromCropComponent();
                    });
            }
            this.clearAvatarBtn = true;
            this.cropped = false;
            this.isChanged = false;
        },
        isSelectToCrop() {
            this.selectToCrop = true;
            this.clearAvatarBtn = false;
        },
        isCropped(val, cropper) {
            this.isChanged = val;
            this.cropped = true;
            this.cropper = cropper;
            this.selectToCrop = !val;
        },
        isCropping(cropper) {
            this.cropper = cropper;
        },
        toBlob(blob) {
            this.avatar = blob;
        },
        clearAvatar() {
            this.avatar = null;
            this.isChanged = true;
            this.clearAvatarBtn = false;
            this.$refs.imageSrc.src = "/uploads/no-user-image.png";
        },
    },
    watch: {
        currentUserName(val) {
            this.name = val;
        },
        currentUserEmail(val) {
            this.email = val;
        },
        currentUserAvatar(val) {
            this.avatar = val;
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
        password() {
            if (this.password) {
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
    height: 100%;
}

form {
    padding: 2rem;
}

@media (max-width: 450px) {
    .info-wrapper {
        display: block !important;
        text-align: center;
    }
}
</style>
