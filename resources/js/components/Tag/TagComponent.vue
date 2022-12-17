<template>
    <div class="table-wrapper mx-auto">
        <base-spinner v-if="isLoading"></base-spinner>
        <table v-else class="table table-bordered table-dark col-12">
            <tbody>
                <create-tag-component
                    :tags="tags"
                    @createTag="createTag"
                ></create-tag-component>
                <tr v-for="(tag, index) in tags" :key="tag.id">
                    <td scope="row" class="col-1 text-center align-middle">
                        {{ index + 1 }}
                    </td>
                    <td
                        v-if="isTagShowEdit && currentTagId === tag.id"
                        class="col-md-7 align-middle"
                    >
                        <input
                            class="form-control"
                            type="text"
                            v-model.trim="currentTagToEdit"
                        />
                    </td>
                    <td v-else class="col-md-9 align-middle">
                        {{ tag.title }}
                    </td>
                    <td class="col-1 text-center" colspan="2">
                        <div class="d-flex justify-content-between">
                            <button
                                v-if="isTagShowEdit && currentTagId === tag.id"
                                type="button"
                                class="btn btn-success mr-1"
                                @click="editTag(tag.id)"
                            >
                                <i class="nav-icon fas fa-solid fa-check"></i>
                            </button>
                            <button
                                v-else
                                type="button"
                                class="btn btn-info mr-1"
                                @click="showTag(tag)"
                            >
                                <i class="nav-icon fas fa-light fa-pen"></i>
                            </button>
                            <button
                                type="button"
                                class="btn btn-light ml-1"
                                data-toggle="modal"
                                data-target="#deleteModal"
                                @click="deleteTag(tag)"
                            >
                                <i class="nav-icon fas fa-solid fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
        <delete-component @deleteConfirm="deleteConfirm">
            <template v-slot:default>{{ currentTagToDelete }}</template>
            <template v-slot:type>tag</template>
        </delete-component>
    </div>
</template>

<script>
import axios from "axios";
import CreateTagComponent from "./CreateTagComponent.vue";
import DeleteComponent from "../UI/DeleteComponent.vue";
export default {
    components: { DeleteComponent, CreateTagComponent },
    data() {
        return {
            currentTagToEdit: "",
            currentTagToDelete: "",
            tags: [],
            isTagShowEdit: false,
            currentTagId: null,
            tagIdToDelete: null,
            isLoading: false,
        };
    },
    mounted() {
        this.getTags();
    },
    methods: {
        getTags() {
            this.isLoading = true;
            axios
                .get("/api/admin/tags")
                .then((res) => {
                    this.tags = res.data;
                })
                .finally(() => {
                    this.isLoading = false;
                });
        },
        showTag(tag) {
            this.isTagShowEdit = true;
            this.currentTagId = tag.id;
            this.currentTagToEdit = tag.title;
        },
        editTag(id) {
            axios
                .patch(`/api/admin/tags/${id}`, {
                    title: this.currentTagToEdit,
                })
                .then(() => {
                    this.isTagShowEdit = false;
                });
            this.tags.map((tag) => {
                if (tag.id === this.currentTagId) {
                    tag.title = this.currentTagToEdit;
                } else {
                    return tag;
                }
            });
        },
        deleteTag(tag) {
            this.tagIdToDelete = tag.id;
            this.currentTagToDelete = tag.title;
        },
        deleteConfirm() {
            axios
                .delete(`/api/admin/tags/${this.tagIdToDelete}`)
                .then(() => (this.tagIdToDelete = null));
            this.tags = this.tags.filter(
                (tag) => tag.id !== this.tagIdToDelete
            );
        },
        createTag() {
            this.getTags();
        },
    },
};
</script>

<style scoped>
.table-wrapper {
    max-width: 800px;
    min-height: 50vh; /* for spinner */
    height: 100%;
}
</style>
