<template>
  <div class="modal-mask" @click.self="$emit('cancel')">
    <div class="modal-wrapper">
      <div class="post-wrapper" :class="{ 'overflow-auto': showModal }">
        <div class="title-wrap d-flex">
          <div class="post-title">
            <h1>{{ title }}</h1>
          </div>
        </div>
        <div class="post-image-container" ref="imageContainer">
          <img
            v-for="image in prevImage"
            :key="image.size"
            class="post-image"
            :src="`${image.dataURL}`"
            alt=""
          />
        </div>
        <div class="post-content ql-editor">
          <p v-html="content"></p>
        </div>
        <div class="post-footer d-flex justify-content-between">
          <div class="tags-wrap m-0 d-flex">
            <span class="text-muted align-self-center mr-1">Tags: </span>
            <span class="tags" v-for="tag in selectedTags" :key="tag.id">
              {{ tag.title }}
            </span>
          </div>
          <div class="category d-flex flex-nowrap">
            <span class="text-muted mr-1">Category: </span>
            <span> {{ selectedCategoryName }}</span>
          </div>
        </div>
        <div class="post-submit d-flex justify-content-between">
          <button class="btn btn-warning" @click="$emit('cancel')">
            <i class="nav-icon fas fa-arrow-left"></i>
          </button>
          <button class="btn btn-warning" @click="$emit('createPost')">
            <i class="nav-icon fas fa-solid fa-plus"></i>
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  emits: ["cancel, 'createPost"],
  props: [
    "onCancel",
    "showModal",
    "title",
    "selectedCategoryName",
    "selectedTags",
    "content",
    "images",
    "prevImage",
  ],
  data() {
    return {};
  },
  mounted() {
    if (this.$refs.imageContainer.childElementCount % 2 != 0) {
      this.$refs.imageContainer.classList.add("last-image");
    }
  },
};
</script>

<style>
.modal-mask {
  position: absolute;
  z-index: 9998;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: radial-gradient(
    circle,
    #242424,
    #24242489,
    #24242453,
    #2424241e,
    #24242400
  );
}

.modal-wrapper {
  width: 95%;
  height: fit-content;
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  max-width: 485px;
  margin: auto;
  background-color: #242424;
  border: 1px solid rgb(136, 136, 136);
  border-radius: 10px;
  box-shadow: 0 0 20px 1px rgb(136, 136, 136);
  z-index: 1;
}

.post-wrapper {
  position: relative;
  padding: 3rem 0;
  z-index: 1;
}

.overflow-auto {
  max-height: 80vh;
}

.title-wrap {
  justify-content: space-between;
}

.post-wrapper::-webkit-scrollbar {
  width: 0.8rem;
  background-color: #282828;
}
.post-wrapper::-webkit-scrollbar:horizontal {
  height: 12px;
  margin-right: 20px;
}
.post-wrapper::-webkit-scrollbar-thumb {
  background-color: #efe4e4;
  border: 1px solid #000000;
  border-radius: 16px;
}
.post-wrapper::-webkit-scrollbar-thumb:hover {
  background-color: #d3c9c9;
  border: 1px solid #333333;
}
.post-wrapper::-webkit-scrollbar-thumb:active {
  background-color: #9b8a8b;
  border: 1px solid #333333;
}

.post-title,
.post-content {
  margin: 0 0.8rem;
}

.post-title > h1 {
  margin-bottom: 0;
}

.post-footer {
  margin: 0 0.8rem 0.8rem 0.8rem;
}

.tags-wrap {
  width: 75%;
  flex-wrap: wrap;
  gap: 0.5rem;
}
.tags {
  background-color: #ffc107;
  padding: 5px;
  border-radius: 10px;
  color: black;
}

.category {
  align-self: center;
}

.post-image-container {
  position: relative;
  display: grid;
  gap: 0.8rem;
  grid-template-columns: repeat(2, 1fr);
  margin-bottom: 1rem;
  padding: 0.8rem;
}

.post-image {
  object-fit: cover;
  width: 100%;
  height: 250px;
  position: relative;
  border-radius: 10px;
}

.post-submit {
  margin: 0 0.8rem 0.8rem 0.8rem;
}

.last-image > img:last-of-type {
  grid-column: 1 / 3;
}

.ql-align-right {
  text-align: right;
}
.ql-align-center {
  text-align: center;
}
.ql-align-left {
  text-align: left;
}

.ql-toolbar {
  background-color: inherit;
}

.ql-container {
  border-radius: 0.25rem;
}
</style>
