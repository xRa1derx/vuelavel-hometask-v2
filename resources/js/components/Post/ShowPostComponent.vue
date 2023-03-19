<template>
    <div class="posts-container mx-auto">
        <transition name="modal">
            <div v-if="isCreated" class="post-created">
                <base-check-mark></base-check-mark>
            </div>
        </transition>
        <base-spinner v-if="isLoading"></base-spinner>
        <div class="post-wrapper" v-for="post in posts" :key="post.id">
            <button
                :title="`Delete '${post.title}' post`"
                type="button"
                class="btn post-delete"
                data-toggle="modal"
                data-target="#deleteModal"
                @click="deletePost(post)"
            >
                <i :class="['nav-icon fas fa-minus-circle']"></i>
            </button>
            <div class="title-wrap d-flex">
                <div class="post-title">
                    <h1>{{ post.title }}</h1>
                    <router-link
                        class="post-edit"
                        :title="`Edit '${post.title}' post`"
                        :to="{ name: 'posts.edit.id', params: { id: post.id } }"
                        ><i class="nav-icon fas fa-pen-square"></i
                    ></router-link>
                </div>
                <div class="date">
                    <button class="button">{{ getFullDate(post) }}</button>
                </div>
            </div>
            <div
                class="post-image-container"
                ref="imageContainer"
                :class="{
                    'images-hidden': post.images.length >= 3,
                }"
                @click.self="moreImages(post, $event)"
                :id="post.title"
            >
                <base-lightbox :images="post.images"></base-lightbox>
            </div>
            <div class="post-content">
                <p class="ql-editor" v-html="post.content"></p>
                <button class="show-more" @click="showMoreText($event)">
                    show more
                </button>
            </div>
            <div class="post-footer d-flex justify-content-between">
                <div class="tags-wrap m-0 d-flex">
                    <span class="text-muted align-self-center mr-1"
                        >Tags:
                    </span>
                    <span class="tags" v-for="tag in post.tags" :key="tag.id">
                        {{ tag.title }}
                    </span>
                </div>
                <div class="category d-flex flex-nowrap">
                    <span class="text-muted mr-1">Category: </span>
                    <span> {{ post.category.title }}</span>
                </div>
            </div>
        </div>
        <delete-component @deleteConfirm="deleteConfirm">
            <template v-slot:default>{{ postTitleToDelete }}</template>
            <template v-slot:type>post</template>
        </delete-component>
    </div>
</template>

<script>
import axios from "axios";
import BaseLightbox from "../UI/BaseLightBox.vue";
import BaseCheckMark from "../UI/BaseCheckMark.vue";
import DeleteComponent from "../UI/DeleteComponent.vue";
export default {
    components: {
        BaseLightbox,
        BaseCheckMark,
        DeleteComponent,
    },
    emits: ["loading"],
    data() {
        return {
            posts: [],
            postIdToDelete: null,
            postTitleToDelete: null,
            isLoading: false,
            isLoaded: false,
            isCreated: false,
        };
    },
    mounted() {
        this.getPosts();
    },
    methods: {
        getPosts() {
            this.isLoading = true;
            this.$emit("loading", true);
            axios
                .get("/api/admin/posts")
                .then((res) => {
                    this.posts = res.data.data;
                })
                .then(() => {
                    if (!this.$refs.imageContainer) {
                        this.$router.push({ name: "posts.create" });
                        return;
                    }
                    this.$refs.imageContainer.forEach((element) => {
                        const countImages = element.childElementCount;
                        if (countImages >= 3) {
                            [...element.children].forEach((child) => {
                                child.style.zIndex = -1;
                            });
                        }
                        if (
                            element.nextElementSibling.firstChild
                                .scrollHeight >= 300
                        ) {
                            element.nextElementSibling.classList.add(
                                "post-content-hidden"
                            );
                            element.nextElementSibling.lastChild.style.display =
                                "block";
                        }
                    });
                })
                .finally(() => {
                    this.$emit("loading", false);
                    this.isLoading = false;
                });
        },
        getFullDate(post) {
            let date = post.created_at.slice(0, 16).replace("T", " ");
            let t = date.split(/[- :]/);
            let time = new Date(Date.UTC(t[0], t[1] - 1, t[2], t[3], t[4]));
            return time.toLocaleString("en-US", {
                day: "numeric",
                month: "short",
                year: "numeric",
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
        showMoreText(event) {
            if (event.target) {
                event.target.offsetParent.classList.remove(
                    "post-content-hidden"
                );
                event.target.style.display = "none";
            }
        },
        deletePost(post) {
            this.postIdToDelete = post.id;
            this.postTitleToDelete = post.title;
        },
        deleteConfirm() {
            axios
                .delete(`/api/admin/post/${this.postIdToDelete}`)
                .then(() => this.getPosts());
        },
    },
};
</script>

<style scoped>
.posts-container {
    min-height: 440px;
    max-width: 800px;
}

.post-wrapper:nth-child(odd) {
    box-shadow: inset 0px 0px 10px 0px rgba(0, 0, 0, 0.658);
}

.post-wrapper {
    position: relative;
    padding: 3rem 0;
    z-index: 1;
    border-radius: 10px;
    margin-bottom: 1rem;
}

.post-edit > .nav-icon {
    margin-left: 0.8rem;
    color: #17a2b8;
}
.title-wrap {
    justify-content: space-between;
}
.date {
    align-self: flex-end;
    margin-bottom: 2px;
    margin-right: 1rem;
}
.post-wrapper:nth-child(even) {
    background-color: var(--clr-bg-dark);
    /* border: 2px solid var(--clr-bg-light); */
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
.show-more {
    position: absolute;
    bottom: 0;
    right: 0;
    background-color: var(--clr-accent);
    padding: 0 10px;
    border: none;
    display: none;
}
/* .post-wrapper:nth-child(odd) .post-content {
  background-color: #282828d0;
} */
.post-title > h1 {
    display: inline;
    margin-bottom: 0;
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
    padding: 5px;
    border-radius: 10px;
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
    padding: 0.8rem;
}
.images-hidden {
    /* min-height: 300px; */
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
.button {
    position: relative;
    cursor: pointer;
    display: inline-block;
    border: 0;
    background: transparent;
    color: var(--clr-text);
    font-size: 1.125rem;
    padding: 0;
    margin-right: 0.8rem;
    right: -10px;
    z-index: 1;
}
.button::after {
    content: "";
    position: absolute;
    background: var(--clr-accent);
    height: 0.85em;
    width: 75%;
    right: 0;
    z-index: -1;
    transition: transform 175ms cubic-bezier(0.91, 0, 0.55, 1.64);
    transform-origin: top right;
}
.button:hover::after,
.button:focus::after {
    transform: scale(1.35, 1.85);
}
.post-created {
    position: absolute;
    top: 5px;
    right: 5px;
}
.post-delete {
    position: absolute;
    right: 1rem;
    top: 1rem;
    padding: 0;
}
.nav-icon {
    color: #fff;
}

.ql-editor {
    padding: 0;
}

.post-edit:hover > .nav-icon,
.post-delete:hover > .nav-icon {
    color: #ff7600;
}
.modal-enter-from {
    transform: rotateX(45deg);
    opacity: 0;
}
.modal-enter-active {
    transition: all 0.4s ease-in;
}
.modal-leave-active {
    transition: all 0.4s ease-out;
}
.modal-leave-to {
    opacity: 0;
    transform: rotateX(45deg);
}
</style>
