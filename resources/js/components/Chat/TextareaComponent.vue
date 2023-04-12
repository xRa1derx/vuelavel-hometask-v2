<template>
    <div class="textarea-wrapper">
        <textarea
            v-model="text"
            oninput='this.style.height = "";this.style.height = this.scrollHeight + "px"'
        ></textarea>
        <button class="send-message" @click="addMessage()">Submit</button>
    </div>
</template>

<script>
import axios from "axios";
import { ref } from "vue";
import { useRoute } from "vue-router";
import { v4 as uuidv4 } from "uuid";
import { useStore } from "vuex";
export default {
    emits: ["newMessage"],
    setup(_, { emit }) {
        const text = ref("");
        const route = useRoute();
        const store = useStore();
        const addMessage = () => {
            axios
                .post("/api/admin/chat", {
                    uuid: uuidv4(),
                    to: route.params.id,
                    from: store.state.auth.user.id,
                    message: text.value,
                })
                .then((res) => emit("newMessage", res.data));
            text.value = "";
        };
        return { addMessage, text };
    },
};
</script>

<style scoped>
.textarea-wrapper {
    display: flex;
    flex-direction: column;
    position: relative;
    width: 50%;
    margin: 0 auto;
    box-shadow: 0 2px 12px 0 rgba(0, 0, 0, 0.3);
}

@media (max-width: 500px) {
    .textarea-wrapper {
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
    border-radius: 10px;
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
</style>
