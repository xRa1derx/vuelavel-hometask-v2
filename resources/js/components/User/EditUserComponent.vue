<template>
    <div
        ref="closeEdit"
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
                                ref="cropAvatarClear"
                                @toBlob="toBlob"
                            >
                                <template v-slot:default
                                    >Change avatar</template
                                >
                            </crop-avatar-component>

                            <button
                                v-show="currentUserAvatar && clearAvatarBtn"
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
                        @click.prevent="updateUser()"
                        type="submit"
                        class="btn"
                        :class="!isChanged ? 'btn-secondary' : 'btn-warning'"
                    >
                        {{ isChanged ? "Save changes" : "No changes" }}
                    </button>
                    Avatar: {{ avatar }} currentUserAvatar:
                    {{ currentUserAvatar }}
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
            avatar: null,
            name: "",
            email: "",
            isChanged: false,
            clearAvatarBtn: true,
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
            this.$refs.cropAvatarClear.clearAvatar();
        },
        onClassChange(classAttrValue) {
            const classList = classAttrValue.split(" ");
            if (!classList.includes("show")) {
                console.log("has fully-in-viewport");
            }
        },
        updateUser() {
            if (this.avatar === null) {
                let data = new FormData();
                data.append("avatar", "");
                data.append("name", this.name);
                data.append("email", this.email);
                axios
                    .post(`/api/admin/users/${this.currentUserId}`, data, {
                        _method: "patch",
                    })
                    .then(() => {
                        this.$emit("updateUser");
                    });
            } else if (typeof this.avatar == "string") {
                fetch(this.$refs.imageSrc.src)
                    .then((res) => res.blob())
                    .then((blob) => {
                        let data = new FormData();
                        data.append("avatar", blob);
                        data.append("name", this.name);
                        data.append("email", this.email);
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
                                this.clearAvatarFromCropComponent();
                                this.clearAvatarBtn = true;
                            });
                    });
            } else {
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
                        this.clearAvatarFromCropComponent();
                        this.$refs.imageSrc.src = `/uploads/${res.data.avatar}`;
                        this.clearAvatarBtn = true;
                    });
            }
            this.isChanged = false;
        },
        toBlob(blob) {
            this.avatar = blob;
        },
        clearAvatar() {
            this.avatar = null;
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
        avatar(val, oldVal) {
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

/* .avatar {
    border-top: 1px solid var(--clr-dark-grey-strip);
    border-bottom: 1px solid var(--clr-dark-grey-strip);
} */

/* .avatar-label {
    width: min-content;
    padding-right: 1rem;
} */
/* .avatar-input {
    width: min-content;
} */

/* .avatar-image {
    width: 100%;
    border-radius: 50%;
} */

@media (max-width: 450px) {
    .info-wrapper {
        display: block !important;
        text-align: center;
    }
}
</style>
