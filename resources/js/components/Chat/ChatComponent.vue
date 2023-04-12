<template>
    <div class="chat-wrapper">
        <message-component :messages="messages"></message-component>
        <textarea-component @newMessage="newMessage"></textarea-component>
    </div>
</template>

<script>
import { useRoute } from "vue-router";
import { onMounted, onUnmounted, ref } from "vue";
import MessageComponent from "./MessageComponent.vue";
import axios from "axios";
import TextareaComponent from "./TextareaComponent.vue";
export default {
    components: {
        MessageComponent,
        TextareaComponent,
    },
    setup() {
        const route = useRoute();
        const messages = ref([]);
        onMounted(() => {
            getUser();
            document.body.style.position = "fixed";
        });
        onUnmounted(() => (document.body.style.position = "relative"));
        const getUser = () => {
            axios.get(`/api/admin/chat/${route.params.id}`).then((res) => {
                messages.value = res.data;
            });
        };
        const newMessage = (message) => {
            messages.value.push(message);
        };
        return { messages, newMessage };
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

@media (min-width: 750px) {
    .chat-wrapper {
        /* height: calc(100vh - 55px); */
    }
}
</style>
