<template>
    <div class="comments d-flex" :style="setOffset">
        <div
            class="wrap d-flex"
            :class="{
                selected:
                    $store.state.quickTextarea === comment.id ||
                    $store.state.editCommentTextarea === comment.id,
            }"
        >
            <!-- <div v-if="comment.parent_id !== null && comment.depth != 5" class="line"></div> -->
            <div class="avatar_wrapper">
                <div class="avatar">
                    <img
                        :src="`/uploads/avatars/${
                            comment.author.avatar || 'no-user-image.png'
                        }`"
                        alt=""
                    />
                </div>
            </div>
            <div class="comment-body">
                <b>{{ comment.author.name }}</b>
                <!-- <div class="text-muted" :class="comment.parent ? 'quote' : ''">
                    <p v-if="comment.parent" class="h-100">
                        {{ comment.parent.text }}
                    </p>
                </div> -->
                <edit-comment-component
                    v-if="$store.state.editCommentTextarea === comment.id"
                    :comment="comment"
                ></edit-comment-component>
                <p v-else>{{ comment.text }}</p>
                <div class="comment-footer d-flex justify-content-between">
                    <button
                        class="reply"
                        :class="{
                            activeBtn:
                                $store.state.quickTextarea === comment.id,
                        }"
                        @click="replyData(comment)"
                    >
                        Reply
                    </button>
                    <button
                        class="comment-edit"
                        :class="{
                            activeBtn:
                                $store.state.editCommentTextarea === comment.id,
                        }"
                        @click="commentEdit(comment)"
                        v-if="
                            comment.author.id === $store.state.auth.user.id &&
                            !comment.replies.length
                        "
                    >
                        Edit
                    </button>
                    <button
                        @click="commentDelete(comment)"
                        class="comment-delete"
                        data-toggle="modal"
                        v-if="
                            comment.author.id === $store.state.auth.user.id &&
                            !comment.replies.length
                        "
                        data-target="#deleteModal"
                    >
                        <i class="nav-icon fas fa-trash-alt"></i>
                    </button>
                    <p class="date text-muted">
                        {{ getFullDate(comment) }}
                    </p>
                </div>
                <teleport to="body">
                    <delete-component
                        @deleteConfirm="deleteConfirm"
                        :commentId="comment.id"
                    >
                        <template v-slot:type>a comment</template>
                    </delete-component>
                </teleport>
            </div>
        </div>
        <transition name="slide">
            <div
                v-if="this.$store.state.quickTextarea === comment.id"
                class="post-comments-section"
            >
                <comment-textarea
                    :placeholder="`Type your reply text`"
                    @addComment="addComment"
                >
                </comment-textarea>
            </div>
        </transition>
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
                @addComment="addComment"
            ></replies-component>
        </div>
        <div
            v-if="
                comment.parent_id != null &&
                comment.depth != 5 &&
                this.$store.state.quickTextarea !== comment.id &&
                this.$store.state.editCommentTextarea !== comment.id
            "
            class="comment-collapsing-area"
        ></div>
    </div>
</template>

<script>
import CommentTextarea from "../Comments/CommentTextarea.vue";
import EditCommentComponent from "../Comments/EditCommentComponent.vue";
import DeleteComponent from "../UI/DeleteComponent.vue";
export default {
    components: {
        CommentTextarea,
        EditCommentComponent,
        DeleteComponent,
    },
    emits: ["replyData", "addComment", "deleteComment"],
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
        commentEdit(val) {
            this.$store.dispatch("getQuickTextarea", null);
            this.$store.dispatch("getEditCommentTextarea", val.id);
        },
        commentDelete(val) {
            this.$store.dispatch("getCommentIdToDelete", val.id);
        },
        addComment(event, text) {
            this.$emit("addComment", event, text);
        },
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
        deleteConfirm() {
            axios
                .delete(
                    `/api/user/comment/${this.$store.state.commentIdToDelete}`
                )
                .then(() => this.$emit("deleteComment"));
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
    margin-top: 0.4rem;
}

/* .line {
    position: absolute;
    top: -0.8rem;
    left: 0;
    width: 15px;
    height: 100%;
    border: 1px solid rgba(128, 128, 128, 0.349);
    border-width: 0 0 1px 1px;
    border-bottom-left-radius: 8px;
} */

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

.comment-body p {
    word-break: break-all;
    margin-right: 0.8rem;
}

.comment-body > b {
    color: var(--clr-touch);
}

.post-comments-section {
    background-color: #fff;
    height: 40px;
    padding: 5px 0;
    position: relative;
    z-index: -1000;
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

.comment-footer {
    align-items: center;
    gap: 15px;
}

.date {
    font-size: 12px;
    margin: 0;
    margin-right: 0.8rem;
    align-self: flex-end;
}

.reply,
.comment-edit {
    font-size: 12px;
    text-align: center;
    color: burlywood;
    border: none;
    background-color: inherit;
    padding: 0 0.8rem;
    border-radius: 10px;
    margin-left: -0.8rem;
}

.comment-edit {
    margin-right: auto;
}

.comment-delete {
    font-size: 0.7rem;
    margin-left: auto;
    color: burlywood;
    cursor: pointer;
    background-color: inherit;
    border: none;
    padding: 0 0.8rem;
    border-radius: 10px;
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

.show-more:hover,
.comment-delete:hover {
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

.activeBtn {
    color: var(--clr-touch);
    box-shadow: inset rgba(0, 0, 0, 0.322) 0px 0px 15px 1px;
}

.reply:hover,
.comment-edit:hover {
    color: var(--clr-touch);
    box-shadow: inset rgba(0, 0, 0, 0.205) 0px 0px 15px 1px;
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
    margin-left: 0.8rem;
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
}

.selected {
    background-color: #3d4247;
}

.slide-enter-from {
    transform: translateY(-20px);
}

.slide-enter-to {
    transition: all 0.2s ease-in;
}

.slide-leave-from {
    opacity: 1;
}

.slide-leave-to {
    opacity: 0;
    transition: all 0.5s ease-out;
}
</style>
