<template>
    <div
        v-if="fileProgress > 0 && fileProgress != 100"
        class="progress"
        style="height: 5px"
    >
        <div
            class="progress-bar"
            role="progressbar"
            :style="{ width: fileProgress + '%' }"
            aria-valuenow="25"
            aria-valuemin="0"
            aria-valuemax="100"
        ></div>
    </div>
    <div class="textarea-wrapper">
        <div class="attach-image-preview">
            <div
                class="image-preview"
                v-for="image in previewImages"
                :key="image.id"
            >
                <img :src="image.image" alt="" />
                <font-awesome-icon
                    @click="removeImage(image.id)"
                    icon="circle-xmark"
                    size="2xl"
                    style="color: #e1e4ea"
                />
            </div>
        </div>
        <div class="addFile-container" id="file-container">
            <button class="addFile" @click.prevent="attachMenu()">
                <font-awesome-icon :icon="['fas', 'paperclip']" />
            </button>
            <input
                v-on:change="onChangeFileUpload"
                :key="fileInputKey"
                type="file"
                multiple
                style="display: none"
                id="attach-file"
            />
            <input
                v-on:change="onChangeImageUpload"
                :key="fileInputKey"
                type="file"
                multiple
                style="display: none"
                id="attach-image"
                accept="image/x-png,image/gif,image/jpeg"
            />
        </div>
        <div class="textarea" id="chat-textarea-container">
            <div
                class="attach-menu-backdrop"
                v-if="isAttachMenu"
                @click="isAttachMenu = false"
            >
                <teleport to="#chat-textarea-container">
                    <div class="attach-menu">
                        <div class="attach-container" @click="attachImages()">
                            <div class="attach-image">
                                <font-awesome-icon icon="image" size="2xl" />
                            </div>
                            <p class="m-0">attach image</p>
                        </div>
                        <div class="attach-container" @click="attachFiles()">
                            <div class="attach-file">
                                <font-awesome-icon icon="file" size="2xl" />
                            </div>
                            <p class="m-0">attach file</p>
                        </div>
                    </div>
                </teleport>
            </div>

            <transition @enter="enter" @leave="leave" key="quote">
                <p key="quote" v-show="quote.message" class="quote">
                    {{ subString }}
                </p>
            </transition>
            <div
                v-if="selectedFile"
                class="position-relative p-2 pr-4 text-break"
            >
                <div v-for="file in selectedFile" :key="file.name">
                    {{ file.name }}
                </div>
                <i @click="removeFiles()" class="remove-files">
                    <font-awesome-icon :icon="['fas', 'xmark']" />
                </i>
            </div>
            <!-- <div v-if="selectedImage.length" class="position-relative">
                <i @click="removeFiles()" class="remove-files">
                    <font-awesome-icon :icon="['fas', 'xmark']" />
                </i>
            </div> -->
            <i @click="$emit('cancelReply')" class="cancel">
                <font-awesome-icon
                    :icon="['fas', 'xmark']"
                    v-if="quote.message"
                />
            </i>
            <textarea
                ref="chatTextarea"
                @keyup.ctrl.enter="addMessage($event)"
                @keydown.tab.exact.prevent="tabLeft($event)"
                v-model="text"
                oninput='this.style.height = "";this.style.height = this.scrollHeight + "px"'
                :style="quote.message ? 'border-top-left-radius: 0px;' : ''"
                v-if="!messageForAddingFile"
            ></textarea>
            <textarea
                v-else
                v-model="messageForAddingFile.message"
                disabled
            ></textarea>
            <button
                class="send-message"
                :class="{ inputDisable: isInputDisabled === true }"
                :disabled="isInputDisabled"
                @click="addMessage($event)"
                v-if="!messageForAddingFile"
            >
                Send
            </button>
            <button
                v-else
                class="send-message"
                :class="{ inputDisable: isInputDisabled === true }"
                :disabled="isInputDisabled"
                @click="additionlFile($event)"
            >
                Add {{ selectedFile.length > 1 ? "files" : "file" }}
            </button>
        </div>
    </div>
</template>

<script>
import axios from "axios";
import { computed, ref, watch } from "vue";
import { useRoute } from "vue-router";
import { v4 as uuidv4 } from "uuid";
import { useStore } from "vuex";
import gsap from "gsap/all";
export default {
    emits: [
        "newMessage",
        "newMessageStatus",
        "cancelReply",
        "addFiles",
        "addImages",
    ],
    props: ["quote"],
    setup(props, { emit, expose }) {
        const fileInputKey = ref(0);
        const fileProgress = ref(0);
        const isAttachMenu = ref(false);
        const previewImages = ref([]);
        // const currentFileUpload = ref(null);
        const isInputDisabled = ref(true);
        const prepareToAddNewFile = ref(null);
        const messageForAddingFile = ref(null);
        const text = ref("");
        const route = useRoute();
        const store = useStore();
        const chatTextarea = ref(null);
        const selectedFile = ref(null);
        const selectedImage = ref([]);
        const attachMenu = () => {
            isAttachMenu.value = !isAttachMenu.value;
        };
        const attachFiles = (message) => {
            document.getElementById("attach-file").click();
            isAttachMenu.value = false;
            if (message) {
                prepareToAddNewFile.value = message;
            }
        };
        const attachImages = () => {
            document.getElementById("attach-image").click();
            isAttachMenu.value = false;
        };
        const onChangeImageUpload = (e) => {
            selectedImage.value = [];
            previewImages.value = [];
            let fileList = e.target.files;
            for (let i = 0; i < fileList.length; i++) {
                let image = fileList[i];
                if (!image.type.match("image.*")) {
                    return;
                }
                selectedImage.value.push({ image, id: i });
                let reader = new FileReader();
                reader.onload = function (e) {
                    previewImages.value.push({ image: e.target.result, id: i });
                };
                reader.readAsDataURL(image);
            }
            selectedFile.value = null;
        };
        const onChangeFileUpload = (e) => {
            const files = e.target.files;
            selectedImage.value = [];
            if (e.target.files.length > 0) {
                messageForAddingFile.value = prepareToAddNewFile.value;
                selectedFile.value = files;
                store.dispatch("getActionWithMessage", "addFiles");
            }
        };
        const removeFiles = () => {
            selectedFile.value = null;
            selectedImage.value = [];
            messageForAddingFile.value = null;
            prepareToAddNewFile.value = null;
            clearFileInputKey();
            store.dispatch("getActionWithMessage", "");
        };
        const removeImage = (id) => {
            console.log(id);
            const fileListArray = Array.from(selectedImage.value);
            selectedImage.value = selectedImage.value.filter(
                (image) => image.id != id
            );
            previewImages.value = previewImages.value.filter(
                (image) => image.id != id
            );
        };
        const clearFileInputKey = () => {
            fileInputKey.value++;
        };
        const enter = (el, done) => {
            gsap.to(el, {
                height: 35,
                duration: 0.3,
                ease: "ease.in",
                onComplete: done,
            });
        };
        const leave = (el, done) => {
            gsap.to(el, {
                height: 0,
                duration: 0.3,
                ease: "ease.out",
                onComplete: done,
            });
        };
        const subString = computed(() => {
            if (props.quote.message && props.quote.message.length > 25) {
                return props.quote.message.slice(0, 25) + "...";
            }
            return props.quote.message;
        });
        watch(
            () => text.value,
            () =>
                text.value.trim() == ""
                    ? (isInputDisabled.value = true)
                    : (isInputDisabled.value = false)
        );
        watch(
            () => selectedFile.value,
            () =>
                selectedFile.value == null
                    ? (isInputDisabled.value = true)
                    : (isInputDisabled.value = false)
        );
        const additionlFile = () => {
            const formData = new FormData();
            const attachedFiles = [];
            if (selectedFile.value) {
                for (let i = 0; i < selectedFile.value.length; i++) {
                    let file = selectedFile.value[i];
                    formData.append(`files[${i}]`, file);
                    attachedFiles.push(file);
                }
            }
            formData.append("uuid", messageForAddingFile.value.uuid);
            formData.append("to", +route.params.id || 1);
            formData.append("from", store.state.auth.user.id);
            axios.post("/api/file", formData).then((res) => {
                const files = {
                    files: res.data,
                    uuid: res.data[0].message_uuid,
                };
                emit("addFiles", files);
                fileProgress.value = 0;
            });
            removeFiles();
        };
        const addMessage = (event) => {
            if (text.value.trim() !== "" || selectedFile.value != null) {
                if (event.ctrlKey || event.type == "click") {
                    const config = {
                        "Content-Type": "multipart/form-data",
                    };
                    const uuid = uuidv4();
                    const replyMessage = props.quote.message || "";
                    const message = text.value;
                    const formData = new FormData();
                    if (selectedFile.value) {
                        for (let i = 0; i < selectedFile.value.length; i++) {
                            let file = selectedFile.value[i];
                            formData.append(`files[${i}]`, file);
                        }
                    }
                    if (selectedImage.value.length) {
                        for (let i = 0; i < selectedImage.value.length; i++) {
                            let image = selectedImage.value[i].image;
                            formData.append(`images[${i}]`, image);
                        }
                    }
                    formData.append("uuid", uuid);
                    formData.append("to", +route.params.id || 1);
                    formData.append("from", store.state.auth.user.id);
                    formData.append("message", message);
                    formData.append("replyMessage", replyMessage);
                    axios
                        .post(
                            `/api/admin/chat/${
                                route.params.id || store.state.auth.user.id
                            }`,
                            formData,
                            {
                                onUploadProgress: (itemUpload) => {
                                    fileProgress.value = Math.round(
                                        (itemUpload.loaded / itemUpload.total) *
                                            100
                                    );
                                },
                                config,
                            }
                        )
                        .then((res) => {
                            if (res.data.files.length) {
                                emit("addFiles", res.data);
                                fileProgress.value = 0;
                            }
                            if (res.data.images.length) {
                                emit("addImages", res.data);
                            }
                            emit("newMessageStatus", "accept");
                        })
                        .catch((err) => emit("newMessageStatus", "denied"));
                    const data = {
                        uuid,
                        message: message || null,
                        replyMessage,
                        created_at: new Date().toISOString(),
                        new: 1,
                        created_at_for_humans: "sending",
                        sender: {
                            id: store.state.auth.user.id,
                            name: store.state.auth.user.name,
                            admin: store.state.auth.isAdmin ? 1 : 0,
                        },
                        files: [],
                        images: [],
                    };
                    emit("newMessage", data);
                    text.value = "";
                    removeFiles();
                    selectedImage.value = [];
                    previewImages.value = [];
                    chatTextarea.value.style.height = "24px";
                } else {
                    let caret = event.target.selectionStart;
                    event.target.setRangeText("\n", caret, caret, "end");
                    text.value = event.target.value;
                }
            }
        };
        const tabLeft = (event) => {
            let message = text.value;
            let originalSelectionStart = event.target.selectionStart,
                textStart = message.slice(0, originalSelectionStart),
                textEnd = message.slice(originalSelectionStart);

            text.value = `${textStart}\t${textEnd}`;
            event.target.value = text.value;
            event.target.selectionEnd = event.target.selectionStart =
                originalSelectionStart + 1;
        };
        expose({ attachFiles, removeFiles });
        return {
            addMessage,
            tabLeft,
            text,
            chatTextarea,
            isInputDisabled,
            subString,
            enter,
            leave,
            onChangeFileUpload,
            selectedFile,
            removeFiles,
            attachFiles,
            fileInputKey,
            fileProgress,
            // currentFileUpload,
            messageForAddingFile,
            prepareToAddNewFile,
            additionlFile,
            isAttachMenu,
            attachMenu,
            onChangeImageUpload,
            attachImages,
            selectedImage,
            previewImages,
            removeImage,
        };
    },
};
</script>

<style scoped>
.textarea-wrapper {
    width: 598px;
    align-self: center;
    display: flex;
    justify-content: space-between;
    gap: 8px;
}

@media (max-width: 992px) {
    .textarea-wrapper {
        width: 100%;
    }
}
.textarea {
    position: relative;
    display: flex;
    flex-direction: column;
    width: 100%;
    box-shadow: 0 2px 12px 0 rgba(0, 0, 0, 0.3);
    border-top-left-radius: 10px;
}

.attach-menu {
    display: flex;
    flex-direction: column;
    gap: 15px;
    position: absolute;
    bottom: 102px;
    left: -37px;
    width: 100%;
    height: 100%;
    z-index: 999;
}

.attach-container {
    display: flex;
    gap: 5px;
    width: fit-content;
    height: fit-content;
    cursor: pointer;
    padding: 2px 10px;
}

.attach-container:hover {
    border-radius: 20px;
    box-shadow: 0 0 3px #fff;
}

.attach-container > p {
    line-height: 2;
}

.attach-file,
.attach-image {
    width: 32px;
    height: 32px;
}

.attach-menu-backdrop {
    position: fixed;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    background-color: rgba(0, 0, 0, 0.7);
    z-index: 998;
}

.addFile-container {
    height: 0;
}

.addFile {
    background-color: inherit;
    border: none;
    color: #fff;
}

.attach-image-preview {
    display: flex;
    position: absolute;
    width: 100%;
    max-width: 598px;
    height: fit-content;
    bottom: 45px;
    align-items: flex-end;
    flex-wrap: wrap;
}

.image-preview {
    position: relative;
    flex-basis: 25%;
    height: 10vh;
    padding: 2px;
    box-shadow: 0 0 20px 1px #0000003d;
}

.image-preview > svg {
    position: absolute;
    left: 50%;
    top: 50%;
    transform: translate(-50%, -50%);
    cursor: pointer;
}

.image-preview > svg:hover {
    color: rgb(88, 88, 88) !important;
}

.image-preview > img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.cancel {
    position: absolute;
    top: 0px;
    right: 4px;
    cursor: pointer;
    color: rgb(141, 141, 141);
}

.cancel:hover {
    color: #ff7600;
}

@media (max-width: 500px) {
    .textarea {
        width: 100%;
    }
}

textarea {
    height: 24px;
    max-height: 80px;
    width: 100%;
    border: none;
    font: inherit;
    resize: none;
    padding: 0;
    padding-left: 5px;
    border-bottom-left-radius: 10px;
    border-top-left-radius: 10px;
}

textarea:focus {
    outline: none;
}

.send-message {
    position: absolute;
    bottom: 0;
    right: 0;
    border: none;
    border-top-left-radius: 20px;
    border-bottom-left-radius: 20px;
    background-color: #ff7600;
    color: #fff;
    width: 5em;
    height: 24px;
}

.inputDisable {
    background-color: rgb(213, 213, 213);
    color: black;
}

.quote {
    position: relative;
    border: none;
    font: inherit;
    font-style: italic;
    margin: 5px;
    padding: 5px;
    border-left: solid 3px #106064;
    height: 0px;
}

.remove-files {
    position: absolute;
    top: 0px;
    right: 4px;
    cursor: pointer;
    color: rgb(141, 141, 141);
}
</style>
