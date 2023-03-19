<template>
    <div class="textarea-wrap">
        <div v-if="this.$store.state.auth.user.name" class="name text-muted">
            {{ this.$store.state.auth.user.name || "Guest" }}:
        </div>
        <div v-else class="login">
            <button @click="$emit('loginOpen')">Guest</button>
        </div>
        <textarea
            :placeholder="placeholder"
            :value="message"
            name=""
            id=""
            @input="autoGrow($event)"
        ></textarea>
        <button
            @click="$emit('addComment', $event, message)"
            class="send-comment-btn"
        >
            <i :id="textareaType" class="fa-regular fas fa-paper-plane"></i>
        </button>
    </div>
</template>

<script>
export default {
    emits: ["addComment", "loginOpen"],
    props: ["placeholder", "textareaType"],
    data() {
        return {
            message: "",
        };
    },
    methods: {
        autoGrow(element) {
            this.message = element.target.value;
            element.target.style.height = "30px";
            element.target.style.height = `${element.target.scrollHeight}px`;
            element.target.parentElement.parentElement.style.height = "40px";
            element.target.parentElement.parentElement.style.height = `${element.target.parentElement.parentElement.scrollHeight}px`;
        },
    },
};
</script>

<style scoped>
.textarea-wrap {
    display: flex;
    max-width: 100%;
    justify-content: space-between;
    gap: 0.5rem;
    height: 30px;
    background-color: #fff;
}
textarea {
    max-height: 150px;
    width: 100%;
    border: none;
    resize: none;
    padding-left: 5px;
}
.send-comment-btn {
    background-color: inherit;
    border: none;
    align-self: center;
    font-size: 20px;
    padding: 0;
    margin-right: 0.8rem;
    /* transform: rotate(45deg); */
}
.name {
    /* padding: 3px; */
    margin-left: 0.8rem;
    align-self: center;
}

.login > button {
    background-color: inherit;
    border: none;
    color: var(--clr-accent);
    padding: 0;
}
</style>
