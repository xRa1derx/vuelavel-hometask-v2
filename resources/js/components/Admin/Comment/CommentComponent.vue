<template>
    <div class="wrap d-flex">
        <div class="avatar_wrapper">
            <div class="avatar">
                <img
                    :src="`/uploads/avatars/${
                        comment.author.avatar || 'no-user-image.png'
                    }`"
                    alt=""
                />
            </div>
            <button class="reply" @click="reply(comment)">Reply</button>
        </div>

        <div class="comment">
            <b>{{ comment.author.name }}</b>
            <p class="h-100">{{ comment.text }}</p>

            <div class="comment-footer">
                <button
                    v-if="comment.verified === 0 && approvingButton"
                    class="verification"
                    @click="confirmation(comment)"
                >
                    Approve
                </button>
                <p v-if="comment.new === 1" class="has-read">New!</p>
                <p class="date text-muted">{{ getFullDate(comment) }}</p>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";
export default {
    props: ["comment"],
    emits: ["replyData", "getComments"],
    data() {
        return {
            approvingButton: true,
        };
    },
    mounted() {},
    methods: {
        confirmation(comment) {
            axios
                .patch(`/api/admin/comment/${comment.id}`)
                .then(() => this.$emit("getComments"));
            this.approvingButton = false;
        },
        getFullDate(comment) {
            let date = comment.created_at.slice(0, 16).replace("T", " ");
            let t = date.split(/[- :]/);
            let time = new Date(Date.UTC(t[0], t[1] - 1, t[2], t[3], t[4]));
            return time.toLocaleString("en-US", {
                day: "numeric",
                month: "short",
                year: "numeric",
            });
        },
        reply(comment) {
            this.$emit("replyData", comment);
        },
    },
};
</script>

<style scoped>
.wrap {
    gap: 0.5rem;
    padding: 0.5rem 0;
    border-bottom: 1px solid rgb(73, 73, 73);
}

.comment {
    display: flex;
    flex: 0 1 100%;
    flex-direction: column;
}

.comment > p {
    text-align: justify;
    margin: 5px 0;
}

.comment > b {
    color: var(--clr-touch);
}

.comment-footer p {
    font-size: 12px;
    align-self: flex-end;
    margin: 5px 0;
    margin-right: 0.8rem;
}

.reply {
    font-size: 12px;
    margin: 5px 0;
    text-align: center;
    color: burlywood;
    border: none;
    background-color: inherit;
}

.avatar_wrapper {
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.avatar {
    flex: 0 0 auto;
    width: 70px;
    height: 70px;
    padding: 0.5rem;
}

.avatar > img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 100%;
    /* -webkit-filter: drop-shadow(0px 0px 2px #000);
    filter: drop-shadow(0px 0px 2px #000); */
}

.comment-footer {
    display: flex;
    justify-content: flex-end;
}
</style>
