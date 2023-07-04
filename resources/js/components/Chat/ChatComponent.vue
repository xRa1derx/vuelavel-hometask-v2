<template>
    <div class="chat-wrapper">
        <div class="chat-header">
            <div
                class="chat-name"
                v-for="user in getCurrentUserName"
                :key="user.id"
            >
                {{ user.name }}
            </div>
            <div class="chat-status">
                {{
                    $store.getters.onlineUsers.find(
                        (user) => user.id == ($route.params.id || 1)
                    )
                        ? "online"
                        : "offline"
                }}
            </div>
            <div class="new-message-alert" v-if="newMessageAlert === true">
                <font-awesome-icon
                    @click="scrollDown()"
                    :icon="['fas', 'bell']"
                    shake
                    size="lg"
                />
            </div>
        </div>
        <start-chat-component
            v-if="!$route.params.id && $route.meta.title !== 'Dashboard'"
        ></start-chat-component>
        <messages-component
            v-else
            :messages="sortedChatByDate"
            @deleteMessageFromChatArray="deleteMessageFromChatArray"
            @editMessageFromChatArray="editMessageFromChatArray"
            @replyMessage="replyMessage"
            @deleteFileConfirm="deleteFileConfirm"
            @addAdditionalFiles="addAdditionalFiles"
            :quote="quote"
            :messageStatus="messageStatus"
            :spinnerForDelivery="spinnerForDelivery"
            :moreMessagesLoader="moreMessagesLoader"
        ></messages-component>
        <textarea-component
            v-if="$route.params.id || $route.meta.title === 'Dashboard'"
            @newMessage="newMessage"
            @addFiles="addFiles"
            @newMessageStatus="newMessageStatus"
            :quote="quote"
            @cancelReply="quote = {}"
            ref="textareaComponent"
        ></textarea-component>
    </div>
</template>

<script>
import { useRoute } from "vue-router";
import { onMounted, onUnmounted, ref, nextTick, computed, watch } from "vue";
import { useStore } from "vuex";
import MessagesComponent from "./MessagesComponent.vue";
import axios from "axios";
import TextareaComponent from "./TextareaComponent.vue";
import StartChatComponent from "./StartChatComponent.vue";
export default {
    components: {
        MessagesComponent,
        TextareaComponent,
        StartChatComponent,
    },
    setup() {
        const route = useRoute();
        const newMessageAlert = ref(false);
        const messages = ref([]);
        const quote = ref({});
        const messageStatus = ref(null);
        const spinnerForDelivery = ref({ status: false, messageId: null });
        const moreMessagesLoader = ref(false);
        const store = useStore();
        const handleDebouncedScroll = ref(null);
        const idOfCurrentUser = ref(null);
        const textareaComponent = ref(null);
        const stopFindingMoreMessages = ref(false);
        const getCurrentUserName = computed(() => {
            return store.getters.users.filter(
                (user) => user.id == route.params.id
            );
        });
        watch(
            () => [messageStatus.value, messages.value], // <-- check messages?? for what?!
            (val) => {
                if (messageStatus.value != "denied") {
                    messageStatus.value = null;
                    spinnerForDelivery.value = {
                        status: false,
                        messageId: null,
                    };
                }
            }
        );
        onMounted(() => {
            const chat = document.querySelector(".chat");
            document.body.style.position = "fixed";
            getUser(chat);
            webSocketStoreMessage();
            if (store.state.auth.isAdmin) {
                axios.get("/api/admin/users").then((res) => {
                    const users = res.data.filter(
                        (user) => user.id != 0 && user.id != 1
                    );
                    connecting(users);
                });
                // setTimeout(() => {
                //     connecting(store.state.users);
                // }, 2000);
            } else {
                connecting([{ id: store.state.auth.user.id }]);
            }
            if (chat) {
                handleDebouncedScroll.value = _.debounce(
                    () => checkScrollPositon(chat),
                    500
                );
                chat.addEventListener("scroll", handleDebouncedScroll.value);
            }
        });
        onUnmounted(() => {
            document.body.style.position = "relative";
            const chat = document.querySelector(".chat");
            if (chat) {
                chat.removeEventListener("scroll", handleDebouncedScroll);
            }
            window.Echo.private(
                `store_message_${
                    idOfCurrentUser.value || store.state.auth.user.id
                }`
            ).stopListening(".store_message");
        });
        const findNewMessages = () => {
            const newMessages = messages.value.filter(
                (message) =>
                    message.new != 0 &&
                    store.state.auth.user.id !== message.sender.id
            );
            if (newMessages.length) {
                // get uuid of all new messages
                const arrayUuidOfNewMessages = newMessages.map(
                    (message) => message.uuid
                );
                setMessageAsRead(arrayUuidOfNewMessages);
                setTimeout(() => {
                    newMessages.map((message) => (message.new = 0));
                }, 3000);
            }
        };
        const webSocketStoreMessage = () => {
            const chat = document.querySelector(".chat");
            window.Echo.private(
                `store_message_${route.params.id || store.state.auth.user.id}`
            )
                .listen(".store_message", (res) => {
                    const newMessage = res.message;
                    if (chat.scrollTop >= -10) {
                        newMessage.new = 0;
                        messages.value.unshift(newMessage);
                        setMessageAsRead([newMessage.uuid]);
                        scrollDown();
                    } else {
                        newMessageAlert.value = true;
                        messages.value.unshift(newMessage);
                    }
                })
                .listen(".update_message", (res) => {
                    messages.value.forEach((message) => {
                        if (message.uuid === res.message.uuid) {
                            message.message = res.message.message;
                            return;
                        }
                    });
                })
                .listen(".delete_message", (res) => {
                    messages.value = messages.value.filter(
                        (message) => message.uuid != res.message
                    );
                })
                .listen(".add_additional_files", (res) => {
                    messages.value.forEach((message) => {
                        if (message.uuid == res.uuid) {
                            message.files = message.files.concat(res.files);
                            return;
                        }
                    });
                })
                .listen(".mark_as_read", (res) => {
                    const message = messages.value.find(
                        (message) => message.uuid == res.message.uuid
                    );
                    if (message) {
                        message.new = 0;
                    }
                });
        };
        const connecting = (users) => {
            users.map((user) =>
                window.Echo.join(`connecting_status_${user.id}`)
                    .here((users) => {
                        let currentUsers =
                            store.state.onlineUsers.concat(users);
                        store.state.onlineUsers = currentUsers;
                    })
                    .joining((user) => {
                        store.state.onlineUsers.push(user);
                    })
                    .leaving((user) => {
                        store.state.onlineUsers =
                            store.state.onlineUsers.filter(
                                (onlineUsers) => onlineUsers.id !== user.id
                            );
                    })
            );
        };
        const getUser = (chat) => {
            axios.get(`/api/admin/chat/${route.params.id || 1}`).then((res) => {
                idOfCurrentUser.value = route.params.id;
                messages.value = res.data;
                nextTick(() => {
                    findNewMessages();
                });
            });
        };
        const newMessage = (message) => {
            spinnerForDelivery.value.status = true;
            spinnerForDelivery.value.messageId = message.uuid;
            messages.value.unshift(message);
            quote.value = {};
            scrollDown();
        };
        const addFiles = (files) => {
            const currentFile = messages.value.filter(
                (message) => message.uuid === files.uuid
            );
            currentFile[0].files = currentFile[0].files.concat(files.files);
            scrollDown();
        };

        const addAdditionalFiles = (message) => {
            textareaComponent.value.attachFiles(message);
            quote.value = {};
        };
        const newMessageStatus = (status) => {
            messageStatus.value = status;
            if (messageStatus.value === "accept") {
                messages.value[0].created_at_for_humans = "just added";
            } else {
                messages.value[0].created_at_for_humans =
                    "connection problems!";
            }
        };
        const setMessageAsRead = (arrayOfNewMessages) => {
            arrayOfNewMessages.forEach((messageUuid) =>
                axios.patch(`/api/admin/chat/markasread/${messageUuid}`, {
                    new: 0,
                })
            );
            newMessageAlert.value = false;
        };
        const scrollDown = () => {
            nextTick(() => {
                const chat = document.querySelector(".chat");
                if (chat) {
                    chat.scrollTop = 0;
                }
            });
        };
        const deleteMessageFromChatArray = (messageId) => {
            messages.value = messages.value.filter(
                (message) => message.uuid != messageId
            );
            if (quote.value && quote.value.uuid === messageId) {
                quote.value = {};
            }
        };
        const editMessageFromChatArray = (messageId, messageText) => {
            messages.value.filter(
                (message) => message.uuid === messageId
            )[0].message = messageText;
            if (quote.value && quote.value.uuid === messageId) {
                quote.value = {};
            }
        };
        const deleteFileConfirm = (deleteFIle) => {
            messages.value.forEach((message) => {
                if (message.uuid === deleteFIle.message_uuid) {
                    message.files = message.files.filter(
                        (file) => file.id != deleteFIle.id
                    );
                    if (
                        message.files.length === 0 &&
                        message.message === null
                    ) {
                        axios.delete(
                            `/api/admin/chat/${deleteFIle.message_uuid}`
                        );
                        deleteMessageFromChatArray(deleteFIle.message_uuid);
                    }
                }
            });
        };
        const replyMessage = (message) => {
            quote.value = message;
            textareaComponent.value.removeFiles();
        };
        const sortedChatByDate = computed(() => {
            const res = messages.value.reduce((acc, message) => {
                const dayMonthYear = getFullDate(message)
                    .toLocaleString()
                    .slice(0, 10);
                acc[dayMonthYear] = acc[dayMonthYear] || [];
                acc[dayMonthYear].push(message);
                return acc;
            }, {});
            return res;
        });
        const getFullDate = (msg) => {
            let date = msg.created_at.slice(0, 16).replace("T", " ");
            let t = date.split(/[- :]/);
            let time = new Date(Date.UTC(t[0], t[1] - 1, t[2], t[3], t[4]));
            return time;
        };
        const checkScrollPositon = (chat) => {
            const top = chat.clientHeight - chat.scrollHeight;
            if (chat.scrollTop <= 50) {
                findNewMessages();
            }
            if (top >= chat.scrollTop - 50) {
                if (stopFindingMoreMessages.value === false) {
                    moreMessagesLoader.value = true;
                }
                if (moreMessagesLoader.value === true) {
                    axios
                        .post(`/api/admin/chat/load/${route.params.id || 1}`, {
                            uuid: messages.value[messages.value.length - 1]
                                .uuid,
                        })
                        .then((res) => {
                            if (res.data.length != 0) {
                                messages.value = messages.value.concat(
                                    res.data
                                );
                                const chat = document.querySelector(".chat");
                                if (chat) {
                                    chat.scrollTop = top + 60;
                                }
                            } else {
                                stopFindingMoreMessages.value = true;
                            }
                        })
                        .finally(() => (moreMessagesLoader.value = false));
                }
            }
        };
        return {
            messages,
            quote,
            getFullDate,
            newMessage,
            newMessageStatus,
            deleteMessageFromChatArray,
            editMessageFromChatArray,
            replyMessage,
            sortedChatByDate,
            messageStatus,
            spinnerForDelivery,
            handleDebouncedScroll,
            idOfCurrentUser,
            getCurrentUserName,
            newMessageAlert,
            scrollDown,
            connecting,
            addFiles,
            deleteFileConfirm,
            addAdditionalFiles,
            textareaComponent,
            moreMessagesLoader,
            findNewMessages,
            stopFindingMoreMessages,
        };
    },
};
</script>

<style scoped>
.chat-wrapper {
    position: absolute;
    left: 0;
    top: 0;
    width: 100%;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    height: 100%;
    gap: 10px;
    padding: 10px 0;
}

.chat-header {
    display: flex;
    gap: 5px;
}

.new-message-alert {
    margin-left: auto;
    cursor: pointer;
    animation: move 0.5s ease-in infinite alternate;
}

@keyframes move {
    0% {
        margin-right: 20px;
    }
}
</style>
