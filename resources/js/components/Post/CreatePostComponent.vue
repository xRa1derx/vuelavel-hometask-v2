<template>
  <div class="prev-wrap">
    <teleport to="body">
      <transition name="modal">
        <preview-post-component
          v-if="showModal"
          @cancel="showModal = false"
          @createPost="createPost"
          :title="title"
          :selectedCategoryName="selectedCategoryName"
          :selectedTags="selectedTags"
          :content="content"
          :images="images"
          :prevImage="prevImage"
          :showModal="showModal"
        ></preview-post-component>
      </transition>
    </teleport>
    <form class="mx-auto table-dark" :class="{ hide: showModal }">
      <transition name="modal">
        <div v-if="isCreated" class="post-created">
          <base-check-mark></base-check-mark>
        </div>
      </transition>
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
              v-model="selectedCategoryId"
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
              class="d-flex flex-wrap"
            ></div>
            <small id="tagHelp" class="form-text text-muted">
              Use
              <span class="text-warning">'CTRL' + 'left mouse click'</span>
              to select multiple tags</small
            >
          </div>
        </div>
      </div>
      <div class="form-group">
        <label> Upload images </label>
        <base-drop-zone @dropzone="dropzone"></base-drop-zone>
      </div>
      <div class="form-group">
        <label for="text">Content</label>

        <QuillEditor
          v-model:content="content"
          contentType="html"
          :options="options"
          v-model="content"
        />
      </div>
      <div class="d-flex w-100 justify-content-end">
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
import BaseCheckMark from "../UI/BaseCheckMark.vue";
import BaseDropZone from "../UI/BaseDropZone.vue";
import PreviewPostComponent from "./PreviewPostComponent.vue";
import { QuillEditor } from "@vueup/vue-quill";
import "@vueup/vue-quill/dist/vue-quill.snow.css";
export default {
  components: {
    BaseDropZone,
    PreviewPostComponent,
    BaseCheckMark,
    QuillEditor,
  },
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
      selectedCategoryId: "",
      selectedCategoryName: "",
      content: null,
      images: null,
      prevImage: null,
      showModal: false,
      isCreated: false,
      isCreatedTimer: null,
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
        placeholder: "Type something...",
      },
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

      this.title = "";
      this.content = "";
      this.selectedCategoryId = "";
      this.selectedTags = [];
      this.selectedTagsIds = [];
      this.selectedTagsNames = [];
      axios
        .post("/api/admin/post/create", data)
        .then((res) => console.log(res));
      this.showModal = false;
      this.isCreated = true;
      if (this.isCreatedTimer) clearTimeout(this.isCreatedTimer);
      this.isCreatedTimer = setTimeout(() => {
        this.isCreated = false;
      }, 5000);
    },
    previewPost() {
      if (this.selectedCategoryId) {
        this.selectedCategoryName = this.categories.filter(
          (category) => category.id == this.selectedCategoryId
        )[0].title;
      }
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
      return `<div style="${this.selectedTagsStyle}">${(this.selectedTagsNames =
        this.selectedTags.map((tag) => tag.title)).join(
        `</div><div style="${this.selectedTagsStyle}">`
      )}</div>`;
    },
  },
};
</script>

<style scoped>
.prev-wrap {
  position: relative;
}

form {
  position: relative;
  max-width: 800px;
  padding: 2rem;
  opacity: 1;
  animation: show 0.4s ease-in;
}

.post-created {
  position: absolute;
  top: 5px;
  right: 5px;
}

.hide {
  opacity: 0;
  animation: hide 0.4s ease-out;
  height: 0px;
  transition: height 0.4s ease-out;
}

.category {
  align-self: flex-start;
}

@keyframes show {
  0% {
    opacity: 0;
    transform: rotateX(90deg);
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

    transform: rotateX(90deg);
  }
}

@media (max-width: 505px) {
  .tagSelect {
    width: 100% !important;
  }

  .category {
    width: 100% !important;
  }
}

.modal-enter-from {
  transform: rotateX(45deg);
  opacity: 0;
}

.modal-enter-active {
  transition: all 0.4s ease-in;
}
.modal-leave-active {
  transition: all 0.4s ease-out;
}

.modal-leave-to {
  opacity: 0;
  transform: rotateX(45deg);
}
</style>
