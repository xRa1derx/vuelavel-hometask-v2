<template>
    <div>
        <div class="row" v-show="imageSrc">
            <img
                ref="img"
                class="avatar-image mb-3"
                :src="imageSrc"
                alt="avatar-image"
            />
        </div>
        <div class="row mx-0">
            <div
                class="avatar pt-3 pb-3 w-100 form-group d-flex flex-wrap justify-content-between"
            >
                <button
                    v-if="!selectToCrop && !selectedFile"
                    class="btn btn-secondary mr-3"
                    @click.prevent="$refs.imageInput.click()"
                >
                    <slot></slot>
                </button>
                <div class="selectFile d-flex justify-content-between">
                    <button
                        v-if="selectToCrop"
                        class="btn btn-warning mr-3"
                        @click.prevent="handleImageCropped"
                    >
                        Crop image
                    </button>
                    <button
                        v-if="!selectToCrop && cropped && imageSrc"
                        class="btn btn-info mr-3"
                        @click="clearCroppedAvatar"
                    >
                        Clear
                    </button>
                    <span class="text-break text-right"
                        ><span class="text-muted">Selected file: </span>
                        <span :class="{ 'text-danger': incorrectFileType }">
                            {{
                                selectedFile
                                    ? selectedFile.name
                                    : incorrectFileType
                                    ? "incorrect file type"
                                    : "no image selected"
                            }}</span
                        ></span
                    >
                </div>
                <input
                    ref="imageInput"
                    class="avatar-input"
                    type="file"
                    id="avatar"
                    v-on:change="onChangeFileUpload"
                    :style="{ display: 'none' }"
                />
            </div>
        </div>
    </div>
</template>

<script>
import Cropper from "cropperjs";
import "cropperjs/dist/cropper.css";
export default {
    props: ["currentUserAvatar"],
    emits: [
        "toBlob",
        "isSelectToCrop",
        "clearCroppedAvatar",
        "isCropping",
        "isCropped",
        "cropper",
    ],
    data() {
        return {
            avatar: null,
            selectedFile: null,
            imageSrc: null,
            cropper: null,
            selectToCrop: false,
            cropped: false,
            incorrectFileType: false,
        };
    },
    methods: {
        clearCroppedAvatar() {
            this.cropper.destroy();
            this.avatar = this.currentUserAvatar;
            this.imageSrc = null;
            this.selectToCrop = false;
            this.selectedFile = null;
            this.cropper = null; // ???
            this.$emit("clearCroppedAvatar", this.avatar, this.cropper);
        },
        handleImageCropped() {
            this.cropper
                .getCroppedCanvas({
                    width: 512,
                    height: 512,
                })
                .toBlob((blob) => {
                    this.avatar = blob;
                    this.$emit("toBlob", blob);
                }, "image/jpeg");
            // this.cropped = true;
            setTimeout(() => {
                this.imageSrc = URL.createObjectURL(this.avatar);
                this.selectToCrop = false;
                this.cropped = true;
                this.cropper.destroy();
                this.$emit("isCropped", true, this.cropper);
            }, 100);
        },
        onChangeFileUpload(e) {
            const files = e.target.files;
            this.incorrectFileType = false;
            if (files.length) {
                if (
                    !["image/png", "image/jpeg", "image/svg"].includes(
                        files[0]["type"]
                    )
                ) {
                    this.incorrectFileType = true;
                    return;
                }
                this.selectedFile = files[0];
                this.avatar = files[0];
                this.imageSrc = URL.createObjectURL(this.avatar);
                this.selectToCrop = true;
                this.$emit("isSelectToCrop", true);
                setTimeout(() => {
                    this.cropper = new Cropper(this.$refs.img, {
                        aspectRatio: 1,
                        minCropBoxWidth: 64,
                        minCropBoxHeight: 64,
                        viewMode: 3,
                        dragMode: "move",
                        background: false,
                        // cropBoxMovable: false,
                        // cropBoxResizable: false,
                    });
                    this.$emit("isCropping", this.cropper);
                }, 0);
            }
        },
        clearIncorrectFileTypeMessage() {
            this.incorrectFileType = false;
        },
    },
};
</script>

<style scoped>
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

.btn {
    height: max-content;
    align-self: center;
}
</style>
