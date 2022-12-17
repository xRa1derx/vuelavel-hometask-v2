<template>
    <form class="mx-auto">
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
                <div class="col-auto my-1">
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
                        <option v-for="tag in tags" :key="tag.id" :value="tag">
                            {{ tag.title }}
                        </option>
                    </select>
                    <div
                        v-if="selectedTags.length"
                        v-html="selectedTagsArr"
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
        <div class="form-group">
            <label for="text">Content</label>
            <textarea
                class="form-control"
                id="text"
                rows="3"
                v-model="content"
            ></textarea>
        </div>
        <button type="submit" @click.prevent="createPost">Create</button>
    </form>
</template>

<script>
import axios from "axios";
export default {
    data() {
        return {
            selectedTagsStyle:
                "background-color: #ffc107; color: black; border-radius: 10px; padding: 5px; margin-right: 5px;",
            title: "",
            categories: [],
            tags: [],
            selectedTags: [],
            selectedTagsNames: [],
            selectedTagsIds: [],
            selectedCategory: "",
            content: "",
        };
    },
    mounted() {
        this.getTags();
        this.getCategories();
    },
    methods: {
        createPost() {
            axios
                .post("/api/admin/posts/create", {
                    title: this.title,
                    category_id: this.selectedCategory,
                    tags: this.selectedTagsIds,
                    content: this.content,
                })
                .then((res) => console.log(res));
        },
        getTags() {
            axios.get("/api/admin/tags").then((res) => (this.tags = res.data));
        },
        getCategories() {
            axios
                .get("/api/admin/categories")
                .then((res) => (this.categories = res.data));
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
            return `<span style="${
                this.selectedTagsStyle
            }">${(this.selectedTagsNames = this.selectedTags.map(
                (tag) => tag.title
            )).join(`</span><span style="${this.selectedTagsStyle}">`)}</span>`;
        },
    },
};
</script>

<style scoped>
form {
    max-width: 800px;
}

@media (max-width: 391px) {
    .tagSelect {
        width: 100% !important;
    }
}
</style>
