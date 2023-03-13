<template>
    <base-spinner v-if="isLoading"></base-spinner>
    <div class="wrapper" ref="wrapper">
        <transition name="fade">
            <div v-show="!isLoading" class="post-wrapper">
                <div ref="title" class="title-wrap d-flex">
                    <button
                        v-if="!isLoading"
                        class="back-btn"
                        @click="$emit('backToBlog')"
                    >
                        <i class="right fas fa-angle-left"></i>
                    </button>
                    <div class="post-title">
                        <h5 @click="$emit('backToBlog')">{{ post.title }}</h5>
                    </div>
                </div>
                <div class="date">
                    <p>{{ time }}</p>
                </div>
                <div
                    class="post-image-container"
                    ref="imageContainer"
                    @click.self="moreImages(post, $event)"
                    :id="post.title"
                >
                    <base-lightbox :images="post.images"></base-lightbox>
                </div>
                <div class="post-content">
                    <p class="ql-editor" ref="post" v-html="post.content"></p>
                    <button class="show-more" @click="showMoreText($event)">
                        show more
                    </button>
                </div>
                <div
                    v-if="!isLoading"
                    class="post-footer d-flex justify-content-between"
                >
                    <div class="tags-wrap m-0 d-flex">
                        <span class="text-muted align-self-center mr-1"
                            >Tags:
                        </span>
                        <span
                            class="tags"
                            v-for="tag in post.tags"
                            :key="tag.id"
                        >
                            {{ tag.title }}
                        </span>
                    </div>
                    <div class="category d-flex flex-nowrap">
                        <span class="text-muted mr-1">Category: </span>
                        <span> {{ category }}</span>
                    </div>
                </div>
                <replies-component
                    v-for="comment in comments"
                    :key="comment.id"
                    :comment="comment"
                    @replyData="replyData"
                ></replies-component>
                <div class="post-comments-section">
                    <comment-textarea
                        v-if="!isLoading"
                        @addComment="addComment($event, post.id)"
                        @loginOpen="$emit('loginOpen')"
                    ></comment-textarea>
                </div>
            </div>
        </transition>
    </div>
</template>

<script>
import axios from "axios";
import BaseLightbox from "../UI/BaseLightBox.vue";
import CommentTextarea from "../Comments/CommentTextarea.vue";
import BaseSpinner from "../UI/BaseSpinner.vue";
export default {
    components: {
        BaseLightbox,
        CommentTextarea,
        BaseSpinner,
    },
    props: ["postSelected"],
    emits: ["loading", "backToBlog", "loginOpen"],
    data() {
        return {
            replyId: null,
            replyName: "",
            post: {},
            isLoaded: false,
            category: "",
            time: "",
            comments: [],
            isLoading: false,
            depth: null,
        };
    },
    mounted() {
        this.getPost();
        const blog = document.querySelector(".blog");
        const wrapper = this.$refs.wrapper;
        blog.style.overflow = "hidden";
        wrapper.style.overflow = "auto";
        wrapper.style.height = "calc(80vh - 60px)";
        this.handleDebouncedScroll = _.debounce(
            (e) => this.showBackBtn(e),
            100
        );
        wrapper.addEventListener("scroll", this.handleDebouncedScroll);
    },
    beforeUnmount() {
        const blog = document.querySelector(".blog");
        blog.style.overflow = "auto";
    },
    methods: {
        showBackBtn(blog) {
            if (blog.target.scrollTop > 250) {
                this.$refs.title.classList.add("back-sticky");
            } else {
                this.$refs.title.classList.remove("back-sticky");
            }
        },
        getPost() {
            this.isLoading = true;
            this.$emit("loading", true);
            axios
                .get(`/api/admin/post/${this.postSelected}`)
                .then(({ data }) => {
                    this.post = data.data;
                    this.category = data.data.category.title;
                    this.comments = data.data.comments;
                    this.getFullDate();
                    console.log(data.data.comments);
                })
                .then(() => {
                    const images = this.$refs.imageContainer;
                    const countImages = images.childElementCount;
                    if (countImages >= 3) {
                        [...images.children].forEach((child) => {
                            child.style.zIndex = -1;
                        });
                        images.classList.add("images-hidden");
                    }
                    if (
                        images.nextElementSibling.firstChild.scrollHeight >= 300
                    ) {
                        images.nextElementSibling.classList.add(
                            "post-content-hidden"
                        );
                        images.nextElementSibling.lastChild.style.display =
                            "block";
                    }
                    setTimeout(() => {
                        this.scrollDown();
                    }, 0);
                })
                .finally(() => {
                    this.isLoading = false;
                    this.$emit("loading", false);
                });
        },
        moreImages(post, event) {
            const target = event.target;
            if (target.classList.contains("post-image-container")) {
                const images = document.getElementById(`${post.title}`);
                images.style.maxHeight =
                    Math.ceil(target.childElementCount / 2) * 275 + "px";
                images.classList.remove("images-hidden");
                images.classList.add("images-show");
                [...images.children].forEach((child) => {
                    child.style.zIndex = 0;
                });
            }
        },
        getFullDate() {
            let date = this.post.created_at.slice(0, 16).replace("T", " ");
            let t = date.split(/[- :]/);
            let time = new Date(Date.UTC(t[0], t[1] - 1, t[2], t[3], t[4]));
            this.time = time.toLocaleString("en-US", {
                day: "numeric",
                month: "short",
                year: "numeric",
            });
        },
        showMoreText(event) {
            if (event.target) {
                event.target.offsetParent.classList.remove(
                    "post-content-hidden"
                );
                event.target.style.display = "none";
            }
        },
        async addComment(text, post_id) {
            this.isLoading = true;
            let replyId = this.replyId;
            let user_id = this.$store.state.auth.user.id || 0;
            if (this.depth != null && this.depth < 5) {
                this.depth++;
            } else {
                this.depth = 0;
            }
            let depth = this.depth;
            const data = {
                text: text,
                post_id,
                user_id,
                parent_id: replyId,
                depth,
            };
            await axios
                .post("/api/user/comment/create", data)
                .finally(() => (this.isLoading = false));
            this.getPost();
            this.depth = null;
            this.replyId = null;
            this.replyName = "";
        },
        replyData(val) {
            this.$store.dispatch("getQuickTextarea", val.id);
            this.replyId = val.id;
            this.replyName = val.author.name;
            this.depth = val.depth;
        },
        scrollDown() {
            if (this.$refs.wrapper) {
                this.$refs.wrapper.scrollTop = this.$refs.wrapper.scrollHeight;
            }
        },
    },
};
</script>

<style scoped>
.post-wrapper {
    /* padding: 0.3rem 0; */
    position: relative;
    z-index: 1;
    /* margin-bottom: 0.5rem; */
    /* margin: 0.8rem 0 0.8rem 0.8rem; */
    /* border-radius: 10px; */
}

.title-wrap {
    margin: 0.5rem 0.5rem 0.8rem 0.5rem;
}

.back-btn {
    display: flex;
    background-color: inherit;
    border: none;
    color: #fff;
    align-self: center;
}

.back-btn > i {
    align-self: end;
}

.back-sticky {
    position: sticky;
    top: 0;
    background-color: rgba(255, 255, 255, 0.938);
    color: #000;
    z-index: 2;
    transition: all 0.3s linear;
    box-shadow: 0px 0px 1px 1px rgba(0, 0, 0, 0.5);
}

.back-sticky > button {
    background-color: rgba(255, 255, 255, 0);
    color: #000;
}

.back-sticky > .post-title > h5 {
    font-weight: 500;
}

.date {
    position: absolute;
    top: 0;
    right: 0;
    margin-right: 0.8rem;
    font-size: 10px;
    padding: 0.5rem 0;
}

.date > p {
    margin: 0;
}

.post-wrapper:nth-child(even) {
    background-color: var(--clr-bg-light);
}

.post-title {
    flex: 0 1 auto;
}

.post-title,
.post-content {
    margin: 0 0.8rem;
}

.post-content-hidden {
    max-height: 285px;
    position: relative;
    overflow: hidden;
    margin-bottom: 1rem;
}

.post-comments-section{
    height: 56px;
}

.show-more {
    position: absolute;
    bottom: 0;
    right: 0;
    background-color: var(--clr-accent);
    padding: 0 10px;
    border: none;
    display: none;
}

.post-title > h5 {
    cursor: pointer;
    margin-bottom: 0;
    padding: 0.5rem 0;
}

.post-footer {
    margin: 0 0.8rem 0.8rem 0.8rem;
}

.tags-wrap {
    width: 75%;
    flex-wrap: wrap;
    gap: 0.5rem;
}
.tags {
    background-color: #ffc107;
    padding: 0 8px;
    /* border-radius: 10px; */
    color: black;
}

.category {
    align-self: center;
}

.post-image-container {
    min-height: 250px;
    max-height: 300px;
    position: relative;
    display: grid;
    gap: 0.8rem;
    grid-template-columns: repeat(2, 1fr);
    margin-bottom: 1rem;
}

.images-hidden {
    overflow: hidden;
    background-image: linear-gradient(
        to bottom,
        #24242400,
        #24242418,
        #24242444,
        #242424b7,
        #242424e3
    );
    background-size: cover;
    position: relative;
    cursor: pointer;
}

.images-hidden::before {
    content: "";
    display: block;
    opacity: 0;
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: #9090901f;
    transition: opacity 0.3s ease-in-out;
}

.images-hidden:hover::before {
    opacity: 1;
}

.images-show {
    transition: max-height 0.25s ease-in;
    overflow: hidden;
    background-color: #242424d5;
}
nav > a {
    padding: 1px 15px;
    color: #fff;
    transition: all 0.3s linear;
}

.nav_link:hover {
    background-color: var(--clr-touch);
}

.ql-editor {
    padding: 0;
}

.wrapper::-webkit-scrollbar {
    width: 0.5rem;
    background-color: #242424f6;
    /* border-radius: 10px; */
}
.wrapper::-webkit-scrollbar:horizontal {
    height: 12px;
    margin-right: 20px;
}
.wrapper::-webkit-scrollbar-thumb {
    background-color: #efe4e4;
    border: 1px solid #000000;
    /* border-radius: 16px; */
}
.wrapper::-webkit-scrollbar-thumb:hover {
    background-color: #d3c9c9;
    border: 1px solid #333333;
}
.wrapper::-webkit-scrollbar-thumb:active {
    background-color: #9b8a8b;
    border: 1px solid #333333;
}

.fade-enter-from {
    opacity: 0.1;
}
.fade-enter-active {
    transition: all 0.5s linear;
}
.fade-leave-active {
    transition: all 0.1s ease-out;
}
.fade-leave-to {
    opacity: 0;
}
</style>
