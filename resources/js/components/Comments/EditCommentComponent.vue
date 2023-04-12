<template>
    <i
        v-if="this.comment.text !== editedComment"
        @click="updateComment"
        class="nav-icon fas fa-check update-btn"
    ></i>
    <textarea
        ref="textareaEdit"
        v-model="editedComment"
        oninput='this.style.height = "";this.style.height = this.scrollHeight + "px"'
    >
    </textarea>
</template>

<script>
import axios from "axios";
import { mapActions } from "vuex";
export default {
    props: ["comment"],
    data() {
        return {
            editedComment: this.comment.text,
        };
    },
    mounted() {
        this.$refs.textareaEdit.style.height =
            this.$refs.textareaEdit.scrollHeight + "px";
    },
    methods: {
        ...mapActions({
            isLoginOpen: "loginOpen",
        }),
        updateComment() {
            axios
                .patch(`/api/user/comment/${this.comment.id}`, {
                    user_id: this.comment.author.id,
                    text: this.editedComment,
                })
                .then((res) => {
                    this.comment.text = this.editedComment;
                    this.$store.dispatch("getEditCommentTextarea", null);
                })
                .catch((e) => {
                    if (e.response.status === 419) {
                        this.isLoginOpen();
                    }
                });
        },
    },
};
</script>

<style scoped>
textarea {
    height: 24px;
    padding: 0;
    margin-right: 0.8rem;
    margin-bottom: 1rem;
    border: none;
    resize: none;
}

.update-btn {
    position: absolute;
    top: 0.8rem;
    right: 0.8rem;
    align-self: flex-end;
    cursor: pointer;
}
</style>
