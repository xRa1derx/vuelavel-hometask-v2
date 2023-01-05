<template>
    <div class="prev-wrap">
        <!-- <transition name="modal"> -->
        <preview-post-component
            v-if="showModal"
            @close="showModal = false"
            @createPost="createPost"
            :title="title"
            :selectedCategory="selectedCategory"
            :selectedTags="selectedTags"
            :content="content"
            :images="images"
            :prevImage="prevImage"
            :showModal="showModal"
        ></preview-post-component>
        <!-- </transition> -->
        <form class="mx-auto table-dark" :class="{ hide: showModal }">
            <div class="form-group">
                <label for="title">Title</label>
                <input
                    type="title"
                    class="form-control"
                    id="title"
                    placeholder="Enter title"
                    aria-describedby="titleHelp"
                    v-model="title"
                />
                <small id="titleHelp" class="form-text text-muted"
                    >Give a title to your new post</small
                >
            </div>
            <div class="form-group">
                <div class="form-row align-items-start justify-content-between">
                    <div class="col-auto my-1 w-50 category">
                        <label class="mr-sm-2" for="category">Category</label>
                        <select
                            class="custom-select mr-sm-2"
                            id="category"
                            aria-describedby="categoryHelp"
                            v-model="selectedCategory"
                        >
                            <option disabled value="">Please select one</option>
                            <option
                                v-for="category in categories"
                                :key="category.id"
                                :value="category.id"
                            >
                                {{ category.title }}
                            </option>
                        </select>
                        <small id="categoryHelp" class="form-text text-muted"
                            >Choose a category</small
                        >
                    </div>
                    <div class="tagSelect col-auto my-1 w-50">
                        <label for="tags">Tags</label>
                        <select
                            multiple
                            class="form-control mb-2"
                            id="tags"
                            aria-describedby="tagHelp"
                            v-model="selectedTags"
                        >
                            <option
                                v-for="tag in tags"
                                :key="tag.id"
                                :value="tag"
                            >
                                {{ tag.title }}
                            </option>
                        </select>
                        <div
                            v-if="selectedTags.length"
                            v-html="selectedTagsArr"
                            class="d-flex flex-wrap"
                        ></div>
                        <small id="tagHelp" class="form-text text-muted">
                            Use
                            <span class="text-warning"
                                >'CTRL' + 'left mouse click'</span
                            >
                            to select multiple tags</small
                        >
                    </div>
                </div>
            </div>
            <div class="form-group mb-0">
                <label> Upload images </label>
            </div>
            <base-drop-zone
                @dropzone="dropzone"
                class="dropZone bg-dark"
            ></base-drop-zone>
            <div class="form-group">
                <label for="text">Content</label>
                <textarea
                    class="form-control"
                    id="text"
                    rows="3"
                    v-model="content"
                ></textarea>
            </div>
            <div class="d-flex w-100 justify-content-end">
                <!-- <button
                    @click.prevent="createPost"
                    type="submit"
                    class="btn btn-warning"
                >
                    <i class="nav-icon fas fa-solid fa-plus"></i>
                </button> -->
                <button
                    id="show-modal"
                    class="btn btn-warning"
                    @click.prevent="previewPost()"
                >
                    Preview
                </button>
            </div>
        </form>
    </div>
</template>

<script>
import axios from "axios";
import BaseDropZone from "../UI/BaseDropZone.vue";
import PreviewPostComponent from "./PreviewPostComponent.vue";
export default {
    components: { BaseDropZone, PreviewPostComponent },
    data() {
        return {
            selectedTagsStyle:
                "background-color: #ffc107; color: black; border-radius: 10px; padding: 0 7px; margin: 0 5px 5px 0",
            title: "",
            categories: [],
            tags: [],
            selectedTags: [],
            selectedTagsNames: [],
            selectedTagsIds: [],
            selectedCategory: "",
            content: "",
            images: null,
            prevImage: null,
            showModal: false,
        };
    },
    mounted() {
        this.getTags();
        this.getCategories();
    },
    methods: {
        createPost() {
            const data = new FormData();
            data.append("title", this.title);
            data.append("category_id", this.selectedCategory);
            this.selectedTagsIds.forEach((tag) => {
                data.append("tags[]", tag);
            });
            data.append("content", this.content);
            const images = this.images.getAcceptedFiles();
            images.forEach((image) => {
                data.append("images[]", image);
                this.images.removeFile(image);
            });

            this.title = "";
            this.content = "";
            this.selectedCategory = "";
            this.selectedTags = [];
            this.selectedTagsIds = [];
            this.selectedTagsNames = [];
            axios
                .post("/api/admin/posts/create", data)
                .then((res) => console.log(res));
        },
        previewPost() {
            this.showModal = true;
            const images = this.images.getAcceptedFiles();
            this.prevImage = images;
        },
        getTags() {
            axios.get("/api/admin/tags").then((res) => (this.tags = res.data));
        },
        getCategories() {
            axios
                .get("/api/admin/categories")
                .then((res) => (this.categories = res.data));
        },
        dropzone(val) {
            this.images = val;
        },
    },
    computed: {
        selectedTagsArr() {
            this.selectedTags.reduce((acc, tag) => {
                tag.title = tag["title"];
                tag.id = tag["id"];
                acc.push(tag);
                return acc;
            }, []);
            this.selectedTagsIds = this.selectedTags.map((tag) => tag.id);
            return `<div style="${
                this.selectedTagsStyle
            }">${(this.selectedTagsNames = this.selectedTags.map(
                (tag) => tag.title
            )).join(`</div><div style="${this.selectedTagsStyle}">`)}</div>`;
        },
    },
};
</script>

<style scoped>
.prev-wrap {
    position: relative;
}

form {
    max-width: 800px;
    padding: 2rem;
    opacity: 1;
    animation: show 0.3s ease-in;
}

.hide {
    opacity: 0;
    animation: hide 0.3s ease-out;
    display: none;
}

@keyframes show {
    0% {
        opacity: 0;
    }
    100% {
        opacity: 1;
    }
}

@keyframes hide {
    0% {
        opacity: 1;
    }
    100% {
        opacity: 0;
    }
}

.dropZone {
    width: 100%;
    margin-bottom: 1rem;
    text-align: center;
    padding: 5rem 1rem;
    cursor: pointer;
    border-radius: 20px;
    box-shadow: 0px 0px 5px 0px #fff;
    transition: all 0.3s ease-in-out;
}

.dropZone:hover {
    box-shadow: 0px 0px 10px 1px #fff;
}

@media (max-width: 505px) {
    .tagSelect {
        width: 100% !important;
    }

    .category {
        width: 100% !important;
    }
}

/* .modal-enter-from {
    opacity: 0;
}

.modal-enter-active {
    transition: all 0.3s ease-in;
}
.modal-leave-active {
    transition: all 0.3s ease-in-out;
}

.modal-leave-to {
    opacity: 0;
} */
</style>
