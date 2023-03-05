<template>
    <div
        class="comments"
        v-for="comment in comments"
        :key="comment.id"
        :class="[{ offset: depth % 3 === 0 }]"
    >
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
                <button class="reply" @click="replyData(comment)">Reply</button>
            </div>
            <p>depth: {{ depth }}</p>
            <div class="comment">
                <b>{{ comment.author.name }}</b>
                <p class="h-100">{{ comment.text }}</p>
                <p class="date text-muted">{{ getFullDate(comment) }}</p>
            </div>
        </div>
        <replies-component
            :comments="comment.replies"
            @replyData="replyData"
            :depth="depth + 1"
        ></replies-component>
    </div>
</template>

<script>
export default {
    props: ["comments", "depth"],
    emits: ["replyData"],
    data() {
        return {};
    },
    methods: {
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
        replyData(data) {
            this.$emit("replyData", data);
        },
    },
};
</script>

<style scoped>
.comments {
    margin-left: 50px;
}

.offset {
    /* margin-left: -15px; */
}

.hidden {
    height: 0;
    visibility: hidden;
    position: relative;
}

.wrap {
    position: relative;
    gap: 0.5rem;
    padding: 0.5rem 0;
    /* border-bottom: 1px solid rgb(73, 73, 73); */
}

.comment {
    display: flex;
    flex: 0 1 100%;
    flex-direction: column;
}

.comment > p {
    /* text-align: justify; */
    margin: 5px 0;
}

.comment > b {
    color: var(--clr-touch);
}

.date {
    font-size: 12px;
    margin: 0;
    align-self: flex-end;
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
</style>
