<template>
    <div class="textarea-wrap">
        <div v-if="this.$store.state.auth.user.name" class="name text-muted">
            {{ this.$store.state.auth.user.name || "Гость" }}:
        </div>
        <div v-else class="login">
            <button @click="isLoginOpen()">Гость</button>
        </div>

        <textarea
            ref="commentTextarea"
            :placeholder="placeholder"
            :value="message"
            @input="autoGrow($event)"
        ></textarea>
        <button @click="addComment($event)" class="send-comment-btn">
            <i :id="textareaType" class="fa-regular fas fa-paper-plane"></i>
        </button>
    </div>
</template>

<script>
import { mapActions } from "vuex";
export default {
    emits: ["addComment", "loginOpen"],
    props: ["placeholder", "textareaType"],
    data() {
        return {
            message: "",
        };
    },
    methods: {
        ...mapActions({
            isLoginOpen: "loginOpen",
        }),
        autoGrow(element) {
            this.message = element.target.value;
            element.target.style.height = "30px";
            element.target.style.height = `${element.target.scrollHeight}px`;
            element.target.parentElement.parentElement.style.height = "40px";
            element.target.parentElement.parentElement.style.height = `${element.target.parentElement.parentElement.scrollHeight}px`;
        },
        addComment(event) {
            this.$emit("addComment", event, this.message);
            this.message = "";
            const postCommentSection = document.querySelector(
                ".post-comments-section"
            );
            postCommentSection.style.height = "40px";
            this.$refs.commentTextarea.style.height = "30px";
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
}
.name {
    margin-left: 0.8rem;
    align-self: center;
}

.login {
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
