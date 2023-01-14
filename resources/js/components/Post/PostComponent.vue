<template>
    <div class="post-wrapper" v-for="post in posts" :key="post.id">
        <div class="title-wrap d-flex">
            <div class="post-title">
                <h1>{{ post.title }}</h1>
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
            <base-lightbox
                :images="post.images"
                :title="post.title"
            ></base-lightbox>
        </div>
        <div class="post-content">
            <p v-html="post.content"></p>
        </div>
        <div class="post-footer d-flex justify-content-between">
            <div class="tags-wrap m-0 d-flex">
                <span class="text-muted align-self-center mr-1">Tags: </span>
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
</template>

<script>
import axios from "axios";
import BaseLightbox from "../UI/BaseLightBox.vue";
export default {
    components: {
        BaseLightbox,
    },
    emits: ["loading"],
    data() {
        return {
            posts: [],
            isLoaded: false,
        };
    },
    mounted() {
        this.getPosts();
    },
    methods: {
        async getPosts() {
            this.$emit("loading", true);
            axios
                .get("/api/admin/posts")
                .then((res) => {
                    this.posts = res.data;
                })
                .then(() => {
                    this.$refs.imageContainer.forEach((element) => {
                        const countImages = element.childElementCount;
                        if (countImages >= 3) {
                            [...element.children].forEach((child) => {
                                child.style.zIndex = -1;
                            });
                            //   element.nextElementSibling.style.display = "block";
                        }
                    });
                })
                .finally(() => {
                    this.$emit("loading", false);
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
                images.style.maxHeight = event.target.scrollHeight + "px";
                images.classList.remove("images-hidden");
                images.classList.add("images-show");
                [...images.children].forEach((child) => {
                    child.style.zIndex = 0;
                });
            }
        },
    },
};
</script>

<style scoped>
.post-wrapper {
    position: relative;
    padding: 3rem 0;
    z-index: 1;
    border-radius: 10px;
}

.title-wrap {
    justify-content: space-between;
}

.date {
    align-self: flex-end;
    margin-bottom: 2px;
}

@media (min-width: 800px) {
    .post-wrapper {
        margin: 0 0 0 0.8rem;
    }
}

.post-wrapper:nth-child(even) {
    background-color: var(--clr-bg-light);
}

.post-title,
.post-content {
    margin: 0 0.8rem;
}

.post-title > h1 {
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
    min-height: 300px;
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
    background-color: #24242400;
    transition: opacity 0.3s ease-in-out;
}

.images-hidden:hover::before {
    opacity: 1;
}

.images-show {
    transition: max-height 0.25s ease-in;
    overflow: hidden;
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
</style>
