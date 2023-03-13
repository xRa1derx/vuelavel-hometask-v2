<template>
    <div class="textarea-wrap">
        <div v-if="this.$store.state.auth.user.name" class="name text-muted">
            {{ this.$store.state.auth.user.name || "Guest" }}:
        </div>
        <div v-else class="login">
            <button @click="$emit('loginOpen')">Guest</button>
        </div>
        <textarea
            :value="message"
            name=""
            id=""
            @input="autoGrow($event)"
        ></textarea>
        <button @click="$emit('addComment', message)" class="send-comment-btn">
            <i class="fa-regular fas fa-paper-plane"></i>
        </button>
    </div>
</template>

<script>
export default {
    emits: ["addComment", "loginOpen"],
    data() {
        return {
            message: "",
        };
    },
    methods: {
        autoGrow(element) {
            this.message = element.target.value;
            element.target.style.height = "66px";
            element.target.style.height = `${element.target.scrollHeight}px`;
            element.target.parentElement.parentElement.style.height = "56px";
            element.target.parentElement.parentElement.style.height = `${
                element.target.parentElement.parentElement.scrollHeight + 10
            }px`;
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
    margin: 0.4rem 0.6rem;
}
textarea {
    max-height: 150px;
    width: 100%;
    border: none;
    border-radius: 10px;
    resize: none;
    
}
.send-comment-btn {
    color: #fff;
    background-color: inherit;
    border: none;
    align-self: center;
    font-size: 20px;
    /* transform: rotate(45deg); */
}
.name {
    /* padding: 3px; */
    align-self: center;
}

.login > button {
    background-color: inherit;
    border: none;
    color: var(--clr-accent);
    padding: 0;
}
</style>
