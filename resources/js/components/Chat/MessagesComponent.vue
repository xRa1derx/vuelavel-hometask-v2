<template>
    <div class="chat">
        <div class="messages-loader" v-if="moreMessagesLoader">
            <div class="spinner-border spinner-border-sm" role="status">
                <span class="sr-only">loading...</span>
            </div>
        </div>
        <div
            class="one-chat-day"
            v-for="(value, name) in messages"
            :key="value[0].id"
        >
            <div class="fulldate">
                {{ name }}
            </div>
            <div
                class="message"
                v-for="message in value"
                :key="message.id"
                :style="
                    quote.uuid === message.uuid
                        ? 'background-color: rgb(127 201 205 / 32%)'
                        : ''
                "
                :class="{
                    'new-message':
                        message.new === 1 &&
                        $store.state.auth.user.id !== message.sender.id,
                }"
            >
                <div
                    v-if="message.images && message.images.length"
                    class="message-image-container"
                    :class="[
                        $store.state.auth.user.id === message.sender.id
                            ? 'sender-images'
                            : 'receiver-images',
                        message.images.length <= 4
                            ? `pictures${message.images.length}`
                            : 'picturesSlider',
                    ]"
                >
                    <images-component
                        v-for="(image, index) in message.images"
                        :key="image.id"
                        :image="image"
                        :index="index"
                        :imagesQuantity="message.images.length"
                        :images="message.images"
                    ></images-component>
                </div>
                <div
                    v-if="message.files.length"
                    class="files text-dark bg-secondary"
                    :class="[
                        $store.state.auth.user.id === message.sender.id
                            ? 'sender-files'
                            : 'receiver-files',
                        {
                            replying: quote.uuid === message.uuid,
                        },
                    ]"
                >
                    <div
                        class="files-header"
                        v-if="$store.state.auth.user.id === message.sender.id"
                    >
                        <font-awesome-icon
                            @click="actionWithMessage(message, 'addFiles')"
                            :icon="['fas', 'plus']"
                            style="color: #363645"
                        />
                        <font-awesome-icon
                            @click="deleteAllFiles(message)"
                            :icon="['fas', 'xmark']"
                            style="color: #363645"
                            data-toggle="modal"
                            data-target="#deleteModal"
                        />
                    </div>
                    <files-component
                        v-for="file in message.files"
                        :key="file.id"
                        :file="file"
                        :message="message"
                        @deleteFile="deleteFile"
                        @fileContextMenu="contextMenu"
                    ></files-component>
                </div>
                <message-component
                    :message="message"
                    :quote="quote"
                    :messageToAction="messageToAction"
                    :isInputDisabled="isInputDisabled"
                    :spinnerForDelivery="spinnerForDelivery"
                    :messageStatus="messageStatus"
                    :messageTextareaEdit="messageTextareaEdit"
                    :messageToEdit="messageToEdit"
                    @contextMenu="contextMenu"
                    @confirmActionMessage="confirmActionMessage"
                    @inputDisabled="inputDisabled"
                    @cancelActionMessage="cancelActionMessage"
                    @editedMessageWatcher="editedMessageWatcher"
                ></message-component>
            </div>
        </div>
        <base-message-edit
            v-if="isContextMenu"
            :clientX="clientX"
            :clientY="clientY"
            @close="closeContextMenu()"
            @actionWithMessage="actionWithMessage"
            :message="selectedMessage"
        ></base-message-edit>
        <teleport to="body">
            <delete-component @deleteConfirm="deleteConfirm">
                <template v-slot:default>
                    <span class="text-warning text-break">{{
                        fileForDelete.name
                    }}</span>
                </template>
            </delete-component>
        </teleport>
    </div>
</template>

<script>
import { nextTick, ref, watch } from "vue";
import { useStore } from "vuex";
import BaseMessageEdit from "./BaseMessageEdit.vue";
import axios from "axios";
import MessageComponent from "./MessageComponent.vue";
import FilesComponent from "./FilesComponent.vue";
import DeleteComponent from "../UI/DeleteComponent.vue";
import ImagesComponent from "./ImagesComponent.vue";
export default {
    components: {
        BaseMessageEdit,
        FilesComponent,
        DeleteComponent,
        MessageComponent,
        ImagesComponent,
    },
    props: [
        "messages",
        "quote",
        "messageStatus",
        "spinnerForDelivery",
        "moreMessagesLoader",
    ],
    emits: [
        "deleteFileConfirm",
        "replyMessage",
        "addAdditionalFiles",
        "deleteMessageFromChatArray",
        "editMessageFromChatArray",
    ],
    setup(props, { emit }) {
        const store = useStore();
        const isContextMenu = ref(false);
        const isInputDisabled = ref(true);
        const clientX = ref(null);
        const clientY = ref(null);
        const fileForDelete = ref({ id: [], name: null });
        const selectedMessage = ref(null);
        const messageToAction = ref({ id: null, files: [] });
        const messageTextareaEdit = ref({});
        const messageDimensions = ref({});
        const messageToEdit = ref(null);
        const originalSelectedMessage = ref("");
        const inputDisabled = (val) => {
            isInputDisabled.value = val;
        };
        const editedMessageWatcher = (newMessage) => {
            messageToEdit.value = newMessage;
        };
        watch(
            () => [messageToEdit.value],
            () => {
                originalSelectedMessage.value === messageToEdit.value ||
                ((messageToEdit.value || "").trim() == "" &&
                    messageToAction.value.files === 0)
                    ? (isInputDisabled.value = true)
                    : (isInputDisabled.value = false);
            }
        );
        const contextMenu = (event, msg, source, ref) => {
            if (
                msg.message == null &&
                store.state.auth.user.id !== msg.sender.id
            ) {
                return;
            }
            if (source === "file") {
                const textInMessage =
                    ref.value.parentElement.nextElementSibling;
                setPositionToContextMenu(event);
                messageDimensions.value.width = textInMessage.clientWidth;
                messageDimensions.value.height = textInMessage.clientHeight;
                isContextMenu.value = !isContextMenu.value;
                selectedMessage.value = msg;
                return;
            }
            if (
                messageToAction.value.id != msg.uuid ||
                store.state.messageAction !== "edit"
            ) {
                messageDimensions.value.width = event.target.clientWidth;
                messageDimensions.value.height = event.target.scrollHeight;
            }
            isContextMenu.value = !isContextMenu.value;
            selectedMessage.value = msg;
            setPositionToContextMenu(event);
        };
        const setPositionToContextMenu = (event) => {
            let halfScreenX = document.documentElement.clientWidth / 2;
            let halfScreenY = document.documentElement.clientHeight / 2;

            if (halfScreenX < event.clientX) {
                clientX.value = event.clientX + window.scrollX - 100;
            } else {
                clientX.value = event.clientX + window.scrollX + 20;
            }

            if (halfScreenY < event.clientY) {
                clientY.value = event.clientY + window.scrollY - 120;
            } else {
                clientY.value = event.clientY + window.scrollY;
            }
        };
        const actionWithMessage = (message, action) => {
            if (action != "addFiles") {
                store.dispatch("getActionWithMessage", action);
                messageToAction.value = {
                    id: message.uuid,
                    files: message.files.length,
                };
            }
            nextTick(() => {
                if (action === "edit") {
                    if (message.message === null) {
                        originalSelectedMessage.value = "";
                        messageToEdit.value = "";
                    } else {
                        originalSelectedMessage.value = message.message;
                        messageToEdit.value = message.message;
                    }
                    messageTextareaEdit.value.width =
                        messageDimensions.value.width;
                    messageTextareaEdit.value.height =
                        messageDimensions.value.height;
                    return;
                }
                if (action === "reply") {
                    emit("replyMessage", message);
                    return;
                }
                if (action === "addFiles") {
                    emit("addAdditionalFiles", message);
                    return;
                }
            });
        };
        const confirmActionMessage = () => {
            if (store.state.messageAction === "delete") {
                axios.delete(`/api/admin/chat/${messageToAction.value.id}`);
                emit("deleteMessageFromChatArray", messageToAction.value.id);
            }
            if (store.state.messageAction === "edit") {
                if (messageToEdit.value.trim() == "") {
                    messageToEdit.value = null;
                }
                axios.patch(`/api/admin/chat/${messageToAction.value.id}`, {
                    message: messageToEdit.value,
                });
                emit(
                    "editMessageFromChatArray",
                    messageToAction.value.id,
                    messageToEdit.value
                );
                messageToEdit.value = null;
            }
            messageToAction.value.id = null;
            store.dispatch("getActionWithMessage", "");
        };
        const cancelActionMessage = () => {
            messageToAction.value.id = null;
            store.dispatch("getActionWithMessage", "");
            originalSelectedMessage.value = "";
        };
        const closeContextMenu = () => {
            isContextMenu.value = false;
            selectedMessage.value = null;
        };

        const deleteFile = (file) => {
            fileForDelete.value.id = file.id;
            fileForDelete.value.name = file.name;
        };

        const deleteAllFiles = (message) => {
            const idsForDelete = message.files.map((file) => file.id);
            const res = {
                id: idsForDelete,
                name: "ALL files",
            };
            deleteFile(res);
        };

        const deleteConfirm = () => {
            for (let id of fileForDelete.value.id) {
                axios.delete(`/api/file/${id}`).then((res) => {
                    emit("deleteFileConfirm", res.data);
                });
            }
        };

        return {
            contextMenu,
            closeContextMenu,
            setPositionToContextMenu,
            isContextMenu,
            clientX,
            clientY,
            actionWithMessage,
            selectedMessage,
            messageToAction,
            confirmActionMessage,
            cancelActionMessage,
            messageTextareaEdit,
            messageDimensions,
            messageToEdit,
            originalSelectedMessage,
            isInputDisabled,
            deleteFile,
            deleteConfirm,
            fileForDelete,
            inputDisabled,
            editedMessageWatcher,
            deleteAllFiles,
        };
    },
};
</script>

<style scoped>
.chat {
    width: 598px;
    margin: auto;
    position: relative;
    height: 10%;
    display: flex;
    flex: 1 0 auto;
    flex-direction: column-reverse;
    /* flex-flow: column-reverse; */
    gap: 20px;
    overflow: auto;
    overflow-x: hidden;
    background-color: #eee;
    border-radius: 10px;
    box-shadow: 0 2px 12px 0 rgba(0, 0, 0, 0.3);
}

@media (max-width: 992px) {
    .chat {
        width: 100%;
    }
}

.one-chat-day {
    display: flex;
    flex-direction: column-reverse;
    /* border: 5px solid black; */
}

.message-image-container {
    position: relative;
    gap: 10px;
    box-shadow: 0px 0px 3px 0px #707070;
    padding: 10px;
    background-color: #fff;
    border-radius: 10px;
}

.pictures1 {
    max-width: 500px;
}

.pictures2 {
    max-width: 500px;
    display: grid;
    grid-template-columns: 1fr 1fr;
}

.pictures3 {
    max-width: 500px;
    display: grid;
    grid-template-columns: 1fr 1fr;
    /* grid-template-rows: repeat(2, 1fr); */
    /* grid-auto-rows: 1fr; */
}

.pictures3 > #image1 {
    max-height: 400px;
    grid-column: 1 /2;
    grid-row: 1 / 3;
}

.pictures3 > #image2 {
    max-height: 195px;
    grid-column: 2 /3;
}

.pictures3 > #image3 {
    max-height: 195px;
    grid-column: 2 /3;
    grid-row: 2 / 3;
}

.pictures4 {
    max-width: 500px;
    display: grid;
    grid-template-columns: 1.5fr 1fr;
    /* grid-template-rows: repeat(3, 1fr); */
}

.pictures4 > #image1 {
    max-height: 320px;
    grid-column: 1 /2;
    grid-row: 1 / 4;
}

.pictures4 > #image2 {
    max-height: 100px;
    grid-column: 2 /3;
    grid-row: 1 / 2;
}

.pictures4 > #image3 {
    max-height: 100px;
    grid-column: 2 /3;
    grid-row: 2 / 3;
}

.pictures4 > #image4 {
    max-height: 100px;
    grid-column: 2 /3;
    grid-row: 3 / 4;
}

.picturesSlider {
    max-width: 500px;
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    /* grid-template-rows: repeat(3, 150px); */
}

.picturesSlider > #image1 {
    max-height: 340px;
    grid-column: 1 / -1;
    grid-row: 1 / 3;
}

.picturesSlider > #image2 {
    max-height: 110px;
    grid-row: 3;
    grid-column: 1 / 2;
}
.picturesSlider > #image3 {
    max-height: 110px;
    grid-row: 3;
    grid-column: 2 / 3;
}
.picturesSlider > #image4 {
    max-height: 110px;
    grid-row: 3;
    grid-column: 3 / 4;
}
.picturesSlider > #image5 {
    max-height: 110px;
    grid-row: 3;
    grid-column: 4 / 5;
}

.fulldate {
    order: 1;
    background-color: #fff;
    color: rgba(0, 0, 0, 0.83);
    padding: 3px 12px;
    font-size: 1em;
    width: fit-content;
    margin: 10px auto;
    border-radius: 25px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.549);
}

@media (max-width: 800px) {
    .fulldate {
        font-size: 0.8em;
    }
}

.message {
    position: relative;
    display: flex;
    flex-direction: column;
    /* margin-bottom: 20px; */
    padding: 20px;
    gap: 3px;
}

.new-message {
    background-color: #706d6b6e;
}

.files-header {
    display: flex;
    justify-content: space-between;
    background-color: #eee;
    padding: 2px 5px;
    border-top-right-radius: 10px;
    border-top-left-radius: 10px;
}

.files-header > svg {
    cursor: pointer;
    padding: 3px 5px;
    border-radius: 2px;
}

.files-header > svg:hover {
    background-color: #cecece;
}

.files {
    border-radius: 10px;
}

.sender-files,
.receiver-files {
    min-width: 130px;
    position: relative;
    color: black;
    white-space: pre-wrap;
    z-index: 0;
    box-shadow: 0px 0px 3px 0px #707070;
    font-size: 12px;
    /* top: 5px; */
}

.sender-files,
.sender-images {
    /* right: 10px; */
    /* margin-left: 60px; */
    align-self: flex-end;
    margin-left: 50px;
}

.receiver-files,
.receiver-images {
    /* left: 10px; */
    align-self: flex-start;
    margin-right: 50px;
}

.messages-loader {
    position: sticky;
    display: flex;
    justify-content: center;
    top: 0;
    width: 100%;
    height: 30px;
    background-color: #2424244d;
}

.messages-loader > div {
    align-self: center;
}

.chat::-webkit-scrollbar {
    width: 0.5rem;
    background-color: #242424f6;
}
.chat::-webkit-scrollbar:horizontal {
    height: 12px;
    margin-right: 20px;
}
.chat::-webkit-scrollbar-thumb {
    background-color: #efe4e4;
    border: 1px solid #000000;
}
.chat::-webkit-scrollbar-thumb:hover {
    background-color: #d3c9c9;
    border: 1px solid #333333;
}
.chat::-webkit-scrollbar-thumb:active {
    background-color: #9b8a8b;
    border: 1px solid #333333;
}
</style>
