<template>
    <form class="mx-auto table-dark">
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
            >
              <option
                ref="selecting"
                v-for="tag in data.tags"
                :key="tag.id"
                :value="tag"
                :selected="
                  selectedTagsIds.some((selectedTag) => selectedTag == tag.id)
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
              Use
              <span class="text-warning">'CTRL' + 'left mouse click'</span>
              to select multiple tags</small
            >
          </div>
          <div
            class="edit-image-container"
            ref="imageContainer"
            :class="{
              'images-hidden': images.length >= 3,
            }"
            @click.self="moreImages(data.post, $event)"
            :id="title"
          >
            <base-lightbox :images="images" :title="title"></base-lightbox>
          </div>
          <QuillEditor
            v-model:content="content"
            contentType="html"
            :options="options"
          />
        </div>
      </div>
    </form>
</template>

<script>
import axios from "axios";
import BaseLightbox from "../UI/BaseLightBox.vue";
import { QuillEditor } from "@vueup/vue-quill";
import "@vueup/vue-quill/dist/vue-quill.snow.css";
export default {
  components: { BaseLightbox, QuillEditor },
  emits: ["cancel, 'createPost"],
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
      selectedTags: [],
      selectedTagsIds: [],
      selectedTagsNames: [],
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
    getPost() {
      this.isLoading = true;
      axios.get(`/api/admin/post/${this.$route.params.id}`).then((res) => {
        this.data = res.data;
        this.title = res.data.post.title;
        this.images = res.data.post.images;
        this.currentCategory = res.data.post.category.title;
        this.categories = res.data.categories.filter(
          (category) => category.title != this.currentCategory
        );
        this.selectedTags = res.data.post.tags;
        this.content = res.data.post.content;
        this.$nextTick(() => {
          const countImages = this.$refs.imageContainer.childElementCount;
          if (countImages >= 3) {
            [...this.$refs.imageContainer.children].forEach((element) => {
              element.style.zIndex = -1;
            });
          }
        });
      });
    },
    moreImages(post, event) {
      const target = event.target;
      if (target.classList.contains("edit-image-container")) {
        const images = document.getElementById(`${post.title}`);
        images.style.maxHeight =
          Math.ceil(target.childElementCount / 2) * 265 + "px";
        images.classList.remove("images-hidden");
        images.classList.add("images-show");
        [...images.children].forEach((child) => {
          child.style.zIndex = 0;
        });
      }
    },
  },
  computed: {
    selectedTagsArr() {
      // this.selectedTags.reduce((acc, tag) => {
      //   tag.title = tag["title"];
      //   tag.id = tag["id"];
      //   acc.push(tag);
      //   return acc;
      // }, []);
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
