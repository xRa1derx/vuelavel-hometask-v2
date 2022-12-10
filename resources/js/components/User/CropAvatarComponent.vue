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
        <div class="row">
            <div
                class="avatar pt-3 pb-3 w-100 form-group d-flex flex-wrap justify-content-between"
            >
                <button
                    v-if="!selectToCrop && !selectedFile"
                    class="btn btn-secondary px-3"
                    @click.prevent="$refs.imageInput.click()"
                >
                    <slot></slot>
                </button>
                <button
                    v-else-if="selectToCrop"
                    class="btn btn-warning px-3"
                    @click.prevent="handleImageCropped"
                >
                    Crop image
                </button>
                <button
                    v-if="!selectToCrop && cropped && imageSrc"
                    class="btn btn-info"
                    @click="clearAvatar"
                >
                    Clear
                </button>
                <span class="align-self-center"
                    ><span class="text-muted">Selected file:</span>
                    {{
                        selectedFile ? selectedFile.name : "no image selected"
                    }}</span
                >
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
    props: ["savedAvatar"],
    emits: ["toBlob", "isSelectToCrop", "clearAvatar"],
    data() {
        return {
            avatar: "",
            selectedFile: null,
            imageSrc: null,
            cropper: null,
            selectToCrop: false,
            cropped: false,
        };
    },
    methods: {
        clearAvatar() {
            this.cropper.destroy();
            this.avatar = "";
            this.imageSrc = null;
            this.selectToCrop = false;
            this.selectedFile = null;
            this.cropper = null;
            this.$emit("clearAvatar", this.avatar);
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
            this.cropped = true;
            this.$emit("isSelectToCrop", false);
        },
        onChangeFileUpload(e) {
            const files = e.target.files;
            if (files.length) {
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
                }, 0);
            }
        },
    },
    watch: {
        cropped() {
            setTimeout(() => {
                this.imageSrc = URL.createObjectURL(this.avatar);
                this.selectToCrop = false;
                this.cropper.destroy();
            }, 500);
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
</style>
