<template>
    <tr>
        <td class="text-center align-middle">#</td>
        <td colspan="2">
            <form class="d-flex flex-column">
                <p
                    v-if="!formIsValid"
                    class="form-validation mb-0 text-center warning"
                    style="color: #ffc106"
                >
                    This field must not be empty
                </p>
                <div class="input-group">
                    <input
                        placeholder="new category"
                        type="text"
                        class="form-control align-self-center mr-1"
                        :class="!formIsValid ? 'is-invalid' : ''"
                        id="category"
                        v-model.trim="category"
                    />
                    <button
                        @click.prevent="addCategory"
                        type="submit"
                        class="btn btn-warning align-self-center ml-1"
                    >
                        <i class="nav-icon fas fa-solid fa-plus"></i>
                    </button>
                </div>
            </form>
        </td>
    </tr>
</template>

<script>
import axios from "axios";
export default {
    emits: ["createCategory"],
    data() {
        return {
            category: "",
            formIsValid: true,
        };
    },
    methods: {
        addCategory() {
            if (this.category === "") {
                this.formIsValid = false;
            } else {
                this.formIsValid = true;
                axios
                    .post("/api/admin/categories/create", {
                        title: this.category,
                    })
                    .then(() => {
                        this.$emit("createCategory");
                    });
            }
        },
    },
};
</script>

<style></style>
