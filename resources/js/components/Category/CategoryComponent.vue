<template>
  <div class="table-wrapper">
    <table class="table table-bordered table-dark col-9 mx-auto p-0">
      <tbody>
        <tr>
          <td colspan="4">
            <router-link to="/admin/categories/create"
              ><button type="button" class="btn btn-info float-right">
                Create new category
              </button></router-link
            >
          </td>
        </tr>
        <tr v-for="(category, index) in categories" :key="category.id">
          <th scope="row" class="col-1 text-center align-middle">
            {{ index + 1 }}
          </th>
          <td class="col-md-7 align-middle">{{ category.title }}</td>
          <td class="col-1 text-center" colspan="2">
            <div class="d-flex justify-content-center">
              <button type="button" class="btn btn-info mr-2">Edit</button>
              <button type="button" class="btn btn-warning ml-2">Delete</button>
            </div>
          </td>
        </tr>
      </tbody>
    </table>
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
