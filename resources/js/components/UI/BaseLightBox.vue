<template>
  <!-- <img
    v-for="(image, index) in images"
    :key="image.id"
    class="post-image"
    :src="`/images/posts/${title}/${image.preview}`"
    alt=""
    @click="showMultiple(index)"
  /> -->
  <lazy-component
    v-for="(image, index) in images"
    :key="image.id"
    :class="{
      'last-image': index === images.length - 1 && images.length % 2 != 0,
    }"
  >
    <img
      class="post-image"
      alt=""
      :src="`/images/posts/${title}/${image.preview}`"
      @click="showMultiple(index)"
    />
  </lazy-component>
  <teleport to="body">
    <vue-easy-lightbox
      scrollDisabled
      escDisabled
      moveDisabled
      :visible="visible"
      :imgs="imgs"
      :index="index"
      @hide="handleHide"
    ></vue-easy-lightbox>
  </teleport>
</template>

<script>
import VueEasyLightbox from "vue-easy-lightbox";
export default {
  components: {
    VueEasyLightbox,
  },
  props: ["images", "title"],
  data() {
    return {
      imgs: "",
      visible: false,
      index: 0,
    };
  },
  computed: {
    test() {
      return "123";
    },
  },
  methods: {
    showMultiple(index) {
      this.imgs = this.images.reduce((acc, image) => {
        acc.push(`/images/posts/${this.title}/${image.name}`);
        return acc;
      }, []);
      this.index = index;
      this.show();
    },
    show() {
      this.visible = true;
    },
    handleHide() {
      this.visible = false;
    },
  },
};
</script>

<style scoped>
.post-image {
  object-fit: cover;
  width: 100%;
  height: 250px;
  cursor: pointer;
  position: relative;
}

.last-image {
  grid-column: 1 / 3;
}

/* .last-image > img:last-of-type {
  grid-column: 1 / 3;
} */
</style>
