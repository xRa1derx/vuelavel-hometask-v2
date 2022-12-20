<template>
    <tr>
        <td colspan="1" class="text-center align-middle">#</td>
        <td colspan="2">
            <form class="d-flex flex-column">
                <p
                    v-if="!formIsValid"
                    class="form-validation mb-0 text-center warning"
                    style="color: #ffc106"
                >
                    This field must not be empty
                </p>
                <p
                    v-if="alreadyExist"
                    class="form-validation mb-0 text-center warning"
                    style="color: #ffc106"
                >
                    This tag is already exist
                </p>
                <div class="input-group">
                    <input
                        placeholder="new tag"
                        type="text"
                        class="form-control align-self-center mr-1 w-50"
                        :class="
                            !formIsValid || alreadyExist ? 'is-invalid' : ''
                        "
                        id="tag"
                        v-model.trim="tag"
                    />
                    <button
                        @click.prevent="addTag"
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
    emits: ["createTag"],
    props: ["tags"],
    data() {
        return {
            tag: "",
            formIsValid: true,
            alreadyExist: false,
        };
    },
    methods: {
        addTag() {
            if (this.tag === "") {
                this.formIsValid = false;
                this.alreadyExist = false;
                return;
            }
            if (
                Object.values(this.tags).find((tag) => tag.title === this.tag)
            ) {
                this.alreadyExist = true;
                this.formIsValid = true;
                return;
            }
            this.formIsValid = true;
            this.alreadyExist = false;
            axios
                .post("/api/admin/tags/create", {
                    title: this.tag,
                })
                .then(() => {
                    this.$emit("createTag");
                });
        },
    },
};
</script>

<style scoped></style>
