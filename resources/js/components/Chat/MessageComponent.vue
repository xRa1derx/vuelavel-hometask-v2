<template>
    <div
        @click="contextMenu($event)"
        class="message-container"
        :class="[
            $store.state.auth.user.id === message.sender.id
                ? 'sender'
                : 'receiver',
            {
                replying: quote.uuid === message.uuid,
            },
        ]"
        :style="[
            ($store.state.messageAction == 'delete' &&
                message.uuid == messageToAction.id) ||
            ($store.state.messageAction == 'edit' &&
                message.uuid == messageToAction.id)
                ? 'margin: 0.5rem 2rem'
                : '',
            message.message === null
                ? 'background-color: inherit; padding: 0; box-shadow: none; margin-bottom: 6px;'
                : '',
        ]"
    >
        <span class="replied-container" v-if="message.replyMessage">
            <p class="replied">
                {{ message.replyMessage }}
            </p>
        </span>
        <transition name="slide">
            <div
                class="actions"
                v-if="
                    $store.state.messageAction &&
                    message.uuid == messageToAction.id
                "
            >
                <i
                    @click.stop="$emit('confirmActionMessage')"
                    class="confirm"
                    :style="
                        $store.state.auth.user.id === message.sender.id
                            ? 'left: -1.5rem'
                            : 'right: -1.5rem'
                    "
                >
                    <font-awesome-icon
                        v-if="$store.state.messageAction === 'delete'"
                        :icon="['fas', 'trash']"
                    />

                    <transition name="slide">
                        <font-awesome-icon
                            v-if="
                                $store.state.messageAction === 'edit' &&
                                !isInputDisabled
                            "
                            :icon="['fas', 'check']"
                        />
                    </transition>
                </i>
                <i
                    @click.stop="$emit('cancelActionMessage')"
                    class="cancel"
                    :style="
                        $store.state.auth.user.id === message.sender.id
                            ? 'right: -1.5rem'
                            : 'left: -1.5rem'
                    "
                >
                    <font-awesome-icon
                        :icon="['fas', 'xmark']"
                        v-if="$store.state.messageAction != 'reply'"
                    />
                </i>
            </div>
        </transition>
        <textarea
            @click.stop
            v-if="
                $store.state.messageAction === 'edit' &&
                message.uuid === messageToAction.id
            "
            oninput='this.style.height = "";this.style.height = this.scrollHeight + "px"'
            :style="{
                width: messageTextareaEdit.width + 'px',
                height: messageTextareaEdit.height + 'px',
            }"
            :value="messageToEdit"
            @input="newMessage($event)"
        ></textarea>
        <p class="text-message" ref="messageRef" v-else>
            {{ message.message }}
        </p>
    </div>
    <div
        class="message-footer"
        :class="[
            $store.state.auth.user.id === message.sender.id
                ? 'footer-sender'
                : 'footer-receiver',
        ]"
    >
        <div
            v-if="
                spinnerForDelivery.status === true &&
                spinnerForDelivery.messageId === message.uuid &&
                messageStatus === null
            "
            class="spinner-border"
            role="status"
        >
            <span class="sr-only">Loading...</span>
        </div>
        <div
            v-else-if="
                messageStatus === 'denied' &&
                spinnerForDelivery.messageId === message.uuid
            "
            class="message-status"
        >
            <font-awesome-icon :icon="['fas', 'circle-exclamation']" />
        </div>
        <div
            v-else-if="$store.state.auth.user.id === message.sender.id"
            class="message-status delivered"
        >
            <font-awesome-icon :icon="['fas', 'check']" />
        </div>
        <div
            v-if="
                message.new === 0 &&
                $store.state.auth.user.id === message.sender.id
            "
            class="message-status have-read"
        >
            <font-awesome-icon :icon="['fas', 'check']" />
        </div>
        <span @click.stop class="time">
            <p>
                {{
                    new Date().toLocaleString().slice(0, 10) ==
                    getFullDate(message.created_at)
                        .toLocaleString()
                        .slice(0, 10)
                        ? message.created_at_for_humans
                        : getFullDate(message.created_at)
                              .toLocaleString()
                              .slice(12, 17)
                }}
            </p></span
        >
    </div>
</template>

<script>
import { ref } from "vue";
export default {
    props: [
        "message",
        "quote",
        "messageToAction",
        "isInputDisabled",
        "spinnerForDelivery",
        "messageStatus",
        "messageTextareaEdit",
        "messageToEdit",
    ],
    emits: [
        "contextMenu",
        "confirmActionMessage",
        "cancelActionMessage",
        "inputDisabled",
        "editedMessageWatcher",
    ],
    setup(props, { emit }) {
        const messageRef = ref(null);
        const newMessage = (event) => {
            emit("editedMessageWatcher", event.target.value);
        };
        const contextMenu = (event) => {
            emit("contextMenu", event, props.message, "message", messageRef);
        };
        const getFullDate = (messageDate) => {
            let date = messageDate.slice(0, 16).replace("T", " ");
            let t = date.split(/[- :]/);
            let time = new Date(Date.UTC(t[0], t[1] - 1, t[2], t[3], t[4]));
            return time;
        };
        return { messageRef, contextMenu, newMessage, getFullDate };
    },
};
</script>

<style scoped>
textarea {
    min-width: 100%;
    max-width: 100%;
    min-height: 24px;
    height: 26px;
    padding: 0;
    resize: none;
}

.message-container {
    position: relative;
    max-width: 80%;
    overflow-wrap: break-word;
    padding: 10px;
    border-radius: 10px;
    min-width: 130px;
    color: black;
    cursor: pointer;
    white-space: pre-wrap;
    transition: margin 0.2s linear;
    z-index: 0;
    box-shadow: 0px 0px 3px 0px #707070;
}

.sender {
    align-self: flex-end;
    background-color: #ffa04f;
}
.receiver {
    align-self: flex-start;
    background-color: #ffe083;
}

.sender:hover {
    background-color: #ff9437;
}

.receiver:hover {
    align-self: flex-start;
    background-color: #ffd965;
}

.confirm {
    position: absolute;
    color: var(--clr-bg-light);
}

.cancel {
    position: absolute;
    color: var(--clr-bg-light);
}

.text-message {
    margin: 0;
    float: right;
}

.message-footer {
    display: flex;
    cursor: default;
    gap: 3px;
    color: #3c3c3c;
    font-size: 0.7em;
}

.footer-sender {
    justify-content: end;
    padding-right: 15px;
}

.footer-receiver {
    justify-content: start;
    padding-left: 15px;
}

.spinner-border {
    width: 0.7rem;
    height: 0.7rem;
    align-self: center;
}

.have-read {
    position: relative;
    left: -9px;
}

.time > p {
    font-style: italic;
    text-align: end;
    margin: 0;
    white-space: nowrap;
}

.replying {
    box-shadow: 0 0 3px 1px #106064;
}

.replied-container {
    position: relative;
    z-index: -1;
}

.replied {
    font-style: italic;
    padding: 5px 10px;
    border: 1px solid rgba(0, 0, 0, 0.185);
    border-radius: 10px;
    margin: 0px;
    margin-bottom: 10px;
    color: #000;
    font-size: 0.9rem;
    background-color: #fff;
    opacity: 0.5;
}

/* animations */

.slide-enter-from {
    margin-left: 30%;
    opacity: 0;
}

.slide-enter-to {
    transition: all 0.2s ease-in;
}

.slide-leave-from {
    opacity: 1;
}

.slide-leave-to {
    opacity: 0;
    transition: all 0.1s ease-out;
}
</style>
