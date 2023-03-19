<template>
    <base-spinner v-if="loading"></base-spinner>
    <div v-else class="post-wrapper" v-for="post in posts" :key="post.id">
        <div class="title-wrap d-flex">
            <div class="post-title">
                <h5>{{ post.title }}</h5>
            </div>
            <div class="date">
                <p>{{ getFullDate(post) }}</p>
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
            <p class="ql-editor" ref="post" v-html="post.content"></p>
            <button class="show-more" @click="showMoreText($event)">
                show more
            </button>
        </div>
        <div class="post-footer d-flex justify-content-between">
            <div class="tags-and-category-wrap">
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

            <div class="comments">
                <button @click="commentsLink(post.id)">
                    <i class="comments-quantity">
                        {{ post.all_comments.length }}</i
                    >
                    <i class="far fa-comment"> </i>
                </button>
            </div>
        </div>
        <div class="post-comments-section">
            <comment-textarea
                @addComment="addComment($event, post.id)"
                @loginOpen="$emit('loginOpen')"
            ></comment-textarea>
        </div>
    </div>
    <nav v-if="pagination.total > 3">
        <ul class="pagination">
            <li
                :class="{ disabled: !pagination.prev_page_url }"
                class="page-item"
            >
                <button
                    @click="getPosts(pagination.prev_page_url)"
                    class="page-link"
                    :disabled="!pagination.prev_page_url"
                >
                    Previous
                </button>
            </li>
            <li class="page-item disabled">
                <button class="page-link">
                    Page {{ pagination.current_page }} of
                    {{ pagination.last_page }}
                </button>
            </li>
            <li
                :class="{ disabled: !pagination.next_page_url }"
                class="page-item"
            >
                <button
                    class="page-link"
                    @click.prevent="getPosts(pagination.next_page_url)"
                    :disabled="!pagination.next_page_url"
                >
                    Next
                </button>
            </li>
        </ul>
    </nav>
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
    emits: ["loading", "commentLink", "loginOpen"],
    props: ["current_page_url"],
    data() {
        return {
            posts: [],
            pagination: {},
            loading: false,
        };
    },
    mounted() {
        this.getPosts(this.current_page_url);
    },
    methods: {
        commentsLink(id) {
            this.$emit("commentLink", id, this.pagination.current_page_url);
        },
        getPosts(page_url) {
            page_url = page_url || "/api/admin/posts";
            this.$emit("loading", true);
            axios
                .get(page_url)
                .then((res) => {
                    this.posts = res.data.data;
                    this.makePagination(res.data);
                })
                .then(() => {
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
                });
        },
        makePagination(response) {
            let pagination = {
                current_page: response.current_page,
                last_page: response.last_page,
                prev_page_url: response.prev_page_url,
                next_page_url: response.next_page_url,
                current_page_url: `${response.path}?page=${response.current_page}`,
                total: response.total,
            };
            this.pagination = pagination;
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
        async addComment(text, post_id) {
            this.loading = true;
            let user_id = this.$store.state.auth.user.id || 0;
            const data = { text, post_id, user_id };
            await axios
                .post("/api/user/comment/create", data)
                .then((res) => this.getPosts())
                .finally(() => (this.loading = false));
        },
    },
};
</script>

<style scoped>
.post-wrapper {
    position: relative;
    /* padding: 0.3rem 0; */
    z-index: 1;
    margin-bottom: 1rem;
    /* border-radius: 10px; */
    /* margin: 0.8rem 0 0.8rem 0.8rem; */
}

.title-wrap {
    margin-bottom: 0.5rem;
    justify-content: space-between;
}

.date {
    font-size: 10px;
    display: inline-flex;
    align-self: end;
    margin-right: 0.8rem;
    margin-left: auto;
    flex: 0 0 auto;
    padding-bottom: 0.5rem;
}

.date > p {
    margin: 0;
}

.post-wrapper:nth-child(even) {
    background-color: var(--clr-bg-light);
    /* border: 2px solid var(--clr-bg-light); */
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

.post-title > h5 {
    margin-bottom: 0;
    padding: 0.5rem 0;
}

.post-footer {
    margin: 0.8rem;
}

.tags-wrap {
    gap: 0.5rem;
}
.tags {
    background-color: #ffc107;
    padding: 0 8px;
    /* border-radius: 10px; */
    color: black;
}

.category {
    gap: 0.5rem;
}

.post-image-container {
    min-height: 250px;
    max-height: 300px;
    position: relative;
    display: grid;
    gap: 0.3rem;
    grid-template-columns: repeat(2, 1fr);
    margin-bottom: 1rem;
    /* padding: 0.8rem; */
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

.post-comments-section {
    /* margin: 0 0.8rem; */
    /* border-radius: 10px; */
}

.comments {
    display: flex;
    position: relative;
}

.comments > button {
    font-size: 2rem;
    display: flex;
    border: none;
    background: inherit;
    align-self: end;
    padding: 0;
    gap: 3px;
}

.fa-comment {
    color: rgba(255, 255, 255, 0.055);
}

.comments-quantity {
    font-size: 1rem;
    font-style: inherit;
    color: var(--clr-accent);
    font-weight: 600;
    align-self: flex-end;
}

.ql-editor {
    padding: 0;
}

.pagination {
    justify-content: center;
}

.page-link {
    background-color: inherit !important;
    border: none;
    color: var(--clr-accent);
}
</style>
