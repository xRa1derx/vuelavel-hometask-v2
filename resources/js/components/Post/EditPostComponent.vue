<template>
    <form class="mx-auto table-dark">
        <div class="form-group">
            <label for="title">Заголовок</label>
            <input
                type="title"
                class="form-control"
                id="title"
                placeholder="Enter title"
                aria-describedby="titleHelp"
                v-model="title"
            />
            <small id="titleHelp" class="form-text text-muted"
                >Заголовок для поста</small
            >
        </div>
        <div class="form-group">
            <div class="form-row align-items-start justify-content-between">
                <div class="col-auto my-1 w-50 category">
                    <label class="mr-sm-2" for="category">Категория</label>
                    <select
                        @change="onChangeCategories($event)"
                        class="custom-select mr-sm-2"
                        id="category"
                        aria-describedby="categoryHelp"
                    >
                        <option>{{ currentCategory }}</option>
                        <option
                            v-for="category in categories"
                            :key="category.id"
                            :value="category.id"
                        >
                            {{ category.title }}
                        </option>
                    </select>
                    <small id="categoryHelp" class="form-text text-muted"
                        >Выбрать категорию</small
                    >
                </div>
                <div class="tagSelect col-auto my-1 w-50">
                    <label for="tags">Теги</label>
                    <select
                        multiple
                        class="form-control mb-2"
                        id="tags"
                        aria-describedby="tagHelp"
                        @change="onChangeTags($event)"
                    >
                        <option
                            ref="selecting"
                            v-for="tag in data.all_tags"
                            :key="tag.id"
                            :value="tag.id"
                            :selected="
                                selectedTagsIds.some(
                                    (selectedTag) => selectedTag == tag.id
                                )
                            "
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
                        Используйте
                        <span class="text-warning"
                            >'CTRL' + 'левая кнопка мыши'</span
                        >
                        чтобы выбрать несколько тегов</small
                    >
                </div>
                <base-drop-zone
                    @dropzone="dropzone"
                    @removeImage="removeImage"
                ></base-drop-zone>
                <QuillEditor
                    class="mb-2"
                    v-model:content="content"
                    contentType="html"
                    :options="options"
                />
            </div>
            <div class="form-group">
                <button
                    type="submit"
                    class="btn btn-warning float-right"
                    @click.prevent="saveChanges()"
                >
                    Сохранить изменения
                </button>
            </div>
        </div>
    </form>
</template>

<script>
import axios from "axios";
import BaseDropZone from "../UI/BaseDropZone.vue";
import { QuillEditor } from "@vueup/vue-quill";
import "@vueup/vue-quill/dist/vue-quill.snow.css";
export default {
    components: { QuillEditor, BaseDropZone },
    data() {
        return {
            selectedTagsStyle:
                "background-color: #ffc107; color: black; border-radius: 10px; padding: 0 7px; margin: 0 5px 5px 0",
            data: {},
            title: "",
            images: [],
            categories: [],
            content: "",
            currentCategory: "",
            selectedCategoryId: "",
            selectedTags: [],
            selectedTagsIds: [],
            selectedTagsNames: [],
            imageIdsForDelete: [],
            options: {
                modules: {
                    toolbar: [
                        // [{ size: [] }],
                        [{ header: [1, 2, 3, 4, 5, 6, false] }],
                        ["bold", "italic", "underline", "strike"],
                        [{ color: [] }, { background: [] }],
                        ["blockquote"],
                        [
                            { list: "ordered" },
                            { list: "bullet" },
                            { indent: "-1" },
                            { indent: "+1" },
                        ],
                        [{ align: [] }],
                        ["link", "image", "video"],
                    ],
                },
            },
        };
    },
    mounted() {
        this.getPost();
    },
    methods: {
        onChangeCategories(event) {
            this.selectedCategoryId = event.target.value;
        },
        onChangeTags(event) {
            this.selectedTags = [];
            [...event.target].forEach((tag) =>
                tag.selected == true
                    ? this.selectedTags.push(this.findSelectedTag(+tag.value))
                    : ""
            );
        },
        findSelectedTag(tagId) {
            return this.data.all_tags.filter((item) => item.id == tagId)[0];
        },
        saveChanges() {
            const data = new FormData();
            data.append("title", this.title);
            data.append("category_id", this.selectedCategoryId);
            this.selectedTagsIds.forEach((tag) => {
                data.append("tags[]", tag);
            });
            data.append("content", this.content);
            const images = this.images.getAcceptedFiles();
            images.forEach((image) => {
                data.append("images[]", image);
                this.images.removeFile(image);
            });
            this.imageIdsForDelete.forEach((idForDelete) => {
                data.append("image_ids_for_delete[]", idForDelete);
            });
            data.append("_method", "patch");
            this.title = "";
            this.content = "";
            this.selectedCategoryId = "";
            this.selectedTags = [];
            this.selectedTagsIds = [];
            this.selectedTagsNames = [];
            axios
                .post(`/api/admin/post/${this.$route.params.id}`, data)
                .then(() => this.$router.push({ name: "posts" }));
        },
        getPost() {
            this.isLoading = true;
            axios
                .get(`/api/admin/post/${this.$route.params.id}`)
                .then((res) => {
                    this.data = res.data.data;
                    this.title = res.data.data.title;
                    res.data.data.images.forEach((image) => {
                        let file = {
                            id: image.id,
                            name: image.name,
                            size: image.size,
                        };
                        this.images.displayExistingFile(
                            file,
                            "/" + image.path
                            // `/images/posts/${this.title}/${image.preview}`
                        );
                    });
                    this.currentCategory = res.data.data.category.title;
                    this.selectedCategoryId = res.data.data.category.id;
                    this.categories = res.data.data.all_categories.filter(
                        (category) => category.title != this.currentCategory
                    );
                    this.selectedTags = res.data.data.tags;
                    this.content = res.data.data.content;
                });
        },
        dropzone(val) {
            this.images = val;
        },
        removeImage(val) {
            this.imageIdsForDelete.push(val.id);
        },
    },
    computed: {
        selectedTagsArr() {
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
form {
    position: relative;
    max-width: 800px;
    padding: 2rem;
    opacity: 1;
    animation: show 0.4s ease-in;
    z-index: 0;
}
.edit-image-container {
    width: 100%;
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
}
.category {
    align-self: flex-start;
}
@media (max-width: 505px) {
    .tagSelect {
        width: 100% !important;
    }
    .category {
        width: 100% !important;
    }
}
</style>
