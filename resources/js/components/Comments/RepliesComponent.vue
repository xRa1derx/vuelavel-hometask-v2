<template>
    <div class="comments d-flex" :style="setOffset">
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
                <!-- <div>depth: {{ comment.depth }}</div>
                <div>parent_id {{ comment.parent_id }}</div> -->
            </div>
            <div class="comment-body">
                <b>{{ comment.author.name }}</b>
                <!-- <div class="text-muted" :class="comment.parent ? 'quote' : ''">
                    <p v-if="comment.parent" class="h-100">
                        {{ comment.parent.text }}
                    </p>
                </div> -->
                <p>{{ comment.text }}</p>
                <div class="comment-footer d-flex justify-content-between">
                    <button class="reply" @click="replyData(comment)">
                        Reply
                    </button>
                    <p class="date text-muted">{{ getFullDate(comment) }}</p>
                </div>
                <div
                    v-if="this.$store.state.quickTextarea === comment.id"
                    class="quick-textarea"
                >
                    <comment-textarea> </comment-textarea>
                </div>
            </div>
        </div>
        <div
            class="show-more"
            :class="{ activeMore: showReply }"
            v-if="showMoreButton"
            @click="toggleReply()"
        >
            <span :class="toggleReplyIcon"></span>
            <span>more</span>
        </div>

        <div
            ref="showmore"
            v-if="hasReply"
            v-show="!showReply && !showMoreButton"
        >
            <replies-component
                v-for="reply in comment.replies"
                :key="reply.id"
                :comment="reply"
                @replyData="replyData"
            ></replies-component>
        </div>
        <div
            v-if="comment.parent_id != null && comment.depth != 5"
            class="comment-collapsing-area"
        ></div>
    </div>
</template>

<script>
import CommentTextarea from "../Comments/CommentTextarea.vue";
export default {
    components: {
        CommentTextarea,
    },
    props: {
        comment: {
            type: Object,
            required: true,
        },
    },
    data() {
        return {
            showReply: false,
        };
    },
    mounted() {
        this.$store.state.quickTextarea = null;
    },
    computed: {
        setOffset() {
            if (this.comment.depth === 5) {
                return this.resetOffset;
            } else if (this.comment.parent_id !== null) {
                return this.offset;
            }
        },
        offset() {
            return { "margin-left": `30px` };
        },
        resetOffset() {
            return {
                "margin-left": `-${27 * (this.comment.depth - 1)}px`,
                "box-shadow": "0px 0px 50px 1px #00000075",
                "border-radius": "5px",
                border: "1px solid #0000007d",
                "padding-bottom": "0.4rem",
            };
        },
        hasReply() {
            const { replies } = this.comment;
            return replies && replies.length;
        },
        toggleReplyIcon() {
            return this.showReply
                ? "nav-icon fas fa-angle-down move-down"
                : "fas fa-angle-right move-right";
        },
        showMoreButton() {
            const { replies } = this.comment;
            return replies && replies.length > 0 && this.comment.depth === 4;
        },
    },
    methods: {
        toggleReply() {
            const showMoreStyle = this.$refs.showmore.style;
            if (showMoreStyle.display != "block") {
                showMoreStyle.display = "block";
            } else {
                showMoreStyle.display = "none";
            }
            this.showReply = !this.showReply;
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
        replyData(data) {
            this.$emit("replyData", data);
        },
    },
};
</script>

<style scoped>
.comments {
    flex-direction: column;
    position: relative;
    background-color: var(--clr-bg-dark);
    z-index: 1;
    /* gap: 0.5rem; */
    /* padding: 0.5rem 0; */
}

.wrap {
    position: relative;
    gap: 0.5rem;
    padding: 0.5rem 0;
    margin: 0 0.8rem;
}

.comment-collapsing-area {
    position: absolute;
    top: 0;
    left: 0;
    width: 1px;
    height: 100%;
    background-color: rgba(128, 128, 128, 0.349);
}

.comment-body {
    display: flex;
    flex: 0 1 100%;
    flex-direction: column;
}

.comment-body > b {
    color: var(--clr-touch);
}

.quick-textarea {
    box-sizing: content-box;
    height: 45px;
    margin-top: 0.4rem;
    border-top: 1px solid rgba(128, 128, 128, 0.349);
    border-bottom: 1px solid rgba(128, 128, 128, 0.349);
}

.quick-textarea > .textarea-wrap {
    width: 100%;
}

.quote {
    width: fit-content;
    border: 1px solid gray;
    border-radius: 10px;
    font-family: cursive;
}

.quote > p {
    padding: 0.2rem 0.5rem;
    margin: 0;
}

.date {
    font-size: 12px;
    margin: 0;
    align-self: flex-end;
}

.reply {
    font-size: 12px;
    text-align: center;
    color: burlywood;
    border: none;
    background-color: inherit;
    padding: 0;
}

.show-more {
    display: flex;
    justify-content: center;
    width: fit-content;
    margin: auto;
    position: relative;
    margin-bottom: 0.8rem;
    gap: 0.5rem;
    cursor: pointer;
    color: rgba(255, 255, 255, 0.815);
    box-shadow: rgba(0, 0, 0, 0.26) 0px 0px 15px 1px;
    border-radius: 10px;
    padding: 0 1rem;
    transition: all 0.2s linear;
}

.show-more:hover {
    color: var(--clr-touch);
    box-shadow: inset rgba(0, 0, 0, 0.205) 0px 0px 15px 1px;
}

.show-more:active {
    color: var(--clr-touch);
    box-shadow: inset rgba(0, 0, 0, 0.63) 0px 0px 15px 1px;
}

.activeMore {
    color: var(--clr-touch);
    box-shadow: inset rgba(0, 0, 0, 0.322) 0px 0px 15px 1px;
}

.move-right {
    animation: 0.5s linear infinite alternate slideRight;
}

.move-down {
    animation: 0.5s linear infinite alternate slideDown;
}

@keyframes slideRight {
    from {
        transform: translateX(-4px);
    }
    to {
        transform: translateX(4px);
    }
}

@keyframes slideDown {
    from {
        transform: translateY(-4px);
    }
    to {
        transform: translateY(4px);
    }
}

.show-more > span {
    align-self: center;
}

.avatar_wrapper {
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.avatar {
    flex: 0 0 auto;
    width: 35px;
    height: 35px;
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
