<template>
    <div class="chat">
        <div class="message" v-for="message in messages" :key="message.id">
            <div
                @click="contextMenu($event, message)"
                :class="
                    $store.state.auth.authenticated === true
                        ? 'sender'
                        : 'receiver'
                "
            >
                <p>
                    {{ message.message }}
                </p>
                <span class="time"
                    ><p>{{ message.created_at }}</p></span
                >
            </div>
        </div>
        <base-message-edit
            v-if="isContextMenu"
            :clientX="clientX"
            :clientY="clientY"
            @close="isContextMenu = false"
        ></base-message-edit>
    </div>
</template>

<script>
import { ref } from "vue";
import BaseMessageEdit from "./BaseMessageEdit.vue";
export default {
    components: {
        BaseMessageEdit,
    },
    props: ["messages"],
    setup() {
        const isContextMenu = ref(false);
        const clientX = ref(null);
        const clientY = ref(null);
        const contextMenu = (event, msg) => {
            console.log(event);
            console.log(msg);
            event.preventDefault();
            isContextMenu.value = !isContextMenu.value;
            // this.messageId = msg.id;
            // this.hideEditAndDeleteButtons(msg);
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
                clientY.value = event.clientY + window.scrollY - 50;
            } else {
                clientY.value = event.clientY + window.scrollY;
            }
        };
        return {
            contextMenu,
            setPositionToContextMenu,
            isContextMenu,
            clientX,
            clientY,
        };
    },
};
</script>

<style scoped>
.chat {
    height: 10%;
    display: flex;
    flex: 1 0 auto;
    flex-direction: column;
    gap: 20px;
    overflow: auto;
    background-color: #ffffff;
    border-radius: 10px;
    background-image: url("/images/chat-background.png");
    box-shadow: 0 2px 12px 0 rgba(0, 0, 0, 0.3);
}

.message {
    position: relative;
    display: flex;
    flex-direction: column;
    margin: 10px;
}

.message > div {
    max-width: 90%;
    overflow-wrap: break-word;
    line-height: 24px;
    position: relative;
    padding: 10px;
    border-radius: 10px;
}

.message > div > p {
    margin: 0;
    float: right;
}

.time {
    position: absolute;
    bottom: -20px;
    right: 0;
    cursor: default;
}

.time > p {
    font-size: 0.7em;
    font-style: italic;
    text-align: end;
    margin: 0;
    white-space: nowrap;
}

.sender {
    min-width: 100px;
    position: relative;
    align-self: flex-end;
    color: black;
    background-color: #ffa04f;
    cursor: pointer;
    white-space: pre-wrap;
}
.receiver {
    min-width: 100px;
    position: relative;
    align-self: flex-start;
    background-color: #ffe083;
    cursor: pointer;
    white-space: pre-wrap;
    color: #000;
}

.chat::-webkit-scrollbar {
    width: 0.5rem;
    background-color: #242424f6;
    /* border-radius: 10px; */
}
.chat::-webkit-scrollbar:horizontal {
    height: 12px;
    margin-right: 20px;
}
.chat::-webkit-scrollbar-thumb {
    background-color: #efe4e4;
    border: 1px solid #000000;
    /* border-radius: 16px; */
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
