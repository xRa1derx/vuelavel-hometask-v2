<template>
    <div class="table-wrapper">
        <table class="table table-bordered table-dark col-9 mx-auto p-0">
            <tbody>
                <tr v-for="category in categories" :key="category.id">
                    <th scope="row" class="col-1 text-center align-middle">
                        1
                    </th>
                    <td class="col-md-7 align-middle">{{ category.title }}</td>
                    <td class="col-1 text-center">
                        <button type="button" class="btn btn-info">Edit</button>
                    </td>
                    <td class="col-1 text-center">
                        <button type="button" class="btn btn-warning">
                            Delete
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
        <div class="col-9 mx-auto p-0">
            <router-link to="/admin/categories/create"
                ><button type="button" class="btn btn-info float-right">
                    Create new category
                </button></router-link
            >
        </div>
    </div>
</template>

<script>
import axios from "axios";
export default {
    data() {
        return {
            categories: [],
        };
    },
    mounted() {
        this.showCategories();
    },
    methods: {
        showCategories() {
            axios.get("/api/admin/categories").then((res) => {
                this.categories = res.data;
            });
        },
    },
};
</script>

<style scoped>
.table-wrapper {
    height: 100%;
}
</style>
