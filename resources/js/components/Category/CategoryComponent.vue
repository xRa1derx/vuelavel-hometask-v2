<template>
    <div class="table-wrapper">
        <div v-if="isLoading">
            <base-spinner></base-spinner>
        </div>
        <table v-else class="table table-bordered table-dark col-9 mx-auto p-0">
            <tbody>
                <create-category-component
                    @createCategory="createCategory"
                ></create-category-component>
                <tr v-for="(category, index) in categories" :key="category.id">
                    <td scope="row" class="col-1 text-center align-middle">
                        {{ index + 1 }}
                    </td>
                    <td
                        v-if="
                            isCategoryShowEdit &&
                            currentCategoryId === category.id
                        "
                        class="col-md-7 align-middle"
                    >
                        <input
                            class="form-control"
                            type="text"
                            v-model.trim="currentCategoryToEdit"
                        />
                    </td>
                    <td v-else class="col-md-9 align-middle">
                        {{ category.title }}
                    </td>
                    <td class="col-1 text-center" colspan="2">
                        <div class="d-flex justify-content-between">
                            <button
                                v-if="
                                    isCategoryShowEdit &&
                                    currentCategoryId === category.id
                                "
                                type="button"
                                class="btn btn-success mr-1"
                                @click="editCategory(category.id)"
                            >
                                <i class="nav-icon fas fa-solid fa-check"></i>
                            </button>
                            <button
                                v-else
                                type="button"
                                class="btn btn-info mr-1"
                                @click="showCategory(category)"
                            >
                                <i class="nav-icon fas fa-light fa-pen"></i>
                            </button>
                            <button
                                type="button"
                                class="btn btn-light ml-1"
                                data-toggle="modal"
                                data-target="#deleteModal"
                                @click="deleteCategory(category)"
                            >
                                <i class="nav-icon fas fa-solid fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
        <delete-component @deleteConfirm="deleteConfirm"
            ><template v-slot:default>{{ currentCategoryToDelete }}</template
            ><template v-slot:type>category</template></delete-component
        >
    </div>
</template>

<script>
import axios from "axios";
import CreateCategoryComponent from "./CreateCategoryComponent.vue";
import DeleteComponent from "../DeleteComponent.vue";
export default {
    components: { DeleteComponent, CreateCategoryComponent },
    data() {
        return {
            currentCategoryToEdit: "",
            currentCategoryToDelete: "",
            categories: [],
            isCategoryShowEdit: false,
            currentCategoryId: null,
            categoryIdToDelete: null,
            isLoading: false,
        };
    },
    mounted() {
        this.getCategories();
    },
    methods: {
        getCategories() {
            this.isLoading = true;
            axios
                .get("/api/admin/categories")
                .then((res) => {
                    this.categories = res.data;
                })
                .finally(() => {
                    this.isLoading = false;
                });
        },
        showCategory(category) {
            this.isCategoryShowEdit = true;
            this.currentCategoryId = category.id;
            this.currentCategoryToEdit = category.title;
        },
        editCategory(id) {
            axios
                .patch(`/api/admin/categories/${id}`, {
                    title: this.currentCategoryToEdit,
                })
                .then(() => {
                    this.isCategoryShowEdit = false;
                });
            this.categories.map((category) => {
                if (category.id === this.currentCategoryId) {
                    category.title = this.currentCategoryToEdit;
                } else {
                    return category;
                }
            });
        },
        deleteCategory(category) {
            this.categoryIdToDelete = category.id;
            this.currentCategoryToDelete = category.title;
        },
        deleteConfirm() {
            axios
                .delete(`/api/admin/categories/${this.categoryIdToDelete}`)
                .then(() => (this.categoryIdToDelete = null));
            this.categories = this.categories.filter(
                (category) => category.id !== this.categoryIdToDelete
            );
        },
        createCategory() {
            this.getCategories();
        },
    },
};
</script>

<style scoped>
.table-wrapper {
    min-height: 50vh; /* for spinner */
    height: 100%;
}
</style>
