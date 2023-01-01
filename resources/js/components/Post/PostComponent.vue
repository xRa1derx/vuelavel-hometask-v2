<template>
  <div class="post-wrapper" v-for="post in posts" :key="post.id">
    <h1 class="post-title">{{ post.title }}</h1>
    <div
      class="post-image-container"
      :id="post.title"
      ref="imageContainer"
      @click="moreImages(post, $event)"
    >
      <base-lightbox :images="post.images" :title="post.title"></base-lightbox>
    </div>
    <!-- <div class="more-images text-center mb-3">more...</div> -->
    <div class="post-content">
      <p>{{ post.content }}</p>
    </div>
    <div class="category">
      <button class="button">{{ post.category.title }}</button>
    </div>
    <div class="post-footer d-flex justify-content-between">
      <div class="tags-wrap m-0 d-flex">
        <span class="text-muted align-self-center mr-1">Tags: </span>
        <span class="tags" v-for="tag in post.tags" :key="tag.id">
          {{ tag.title }}
        </span>
      </div>
      <div class="date align-self-center">
        <span>{{ getFullDate(post) }}</span>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import BaseLightbox from "../UI/BaseLightBox.vue";
export default {
  components: {
    BaseLightbox,
  },
  data() {
    return {
      posts: [],
    };
  },
  mounted() {
    this.getPosts();
  },
  methods: {
    async getPosts() {
      await axios
        .get("/api/admin/posts")
        .then((res) => {
          this.posts = res.data;
        })
        .then(() => {
          this.$refs.imageContainer.forEach((element) => {
            const countImages = element.childElementCount;
            if (countImages >= 3) {
              [...element.children].forEach(
                (child) => (child.style.zIndex = -1)
              );
              element.nextElementSibling.style.display = "block";
              element.classList.add("images-hidden");
            }
            if (countImages % 2 != 0) {
              element.classList.add("last-image");
            }
          });
        });
    },
    getFullDate(post) {
      let date = post.created_at.slice(0, 16).replace("T", " ");
      let t = date.split(/[- :]/);
      let time = new Date(Date.UTC(t[0], t[1] - 1, t[2], t[3], t[4]));
      return time.getDate() + "." + time.getMonth() + "." + time.getFullYear();
    },
    moreImages(post, event) {
      const images = document.getElementById(`${post.title}`);
      if (event.target.classList.contains("post-image-container")) {
        images.style.maxHeight = event.target.scrollHeight + "px";
      }
      images.classList.remove("images-hidden");
      images.classList.add("images-show");
      [...images.children].forEach((child) => {
        child.style.zIndex = 0;
      });
    },
  },
};
</script>

<style scoped>
.post-wrapper {
  position: relative;
  /* margin-right: 3px; */
  padding: 5rem 0;
  z-index: 1;
}

.post-wrapper:nth-child(odd) {
  background-color: var(--clr-bg-light);
}

.post-title,
.post-content {
  margin: 0 1rem;
}

.post-footer {
 margin: 0 1rem;
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
  position: absolute;
  top: -10px;
  right: 10px;
}

.post-image-container {
  position: relative;
  display: grid;
  gap: 1rem;
  grid-template-columns: repeat(2, 1fr);
  margin-bottom: 1rem;
}

.images-hidden {
  max-height: 300px;
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

.images-show {
  transition: max-height 0.25s ease-in;
  overflow: hidden;
}

.images-hidden::after {
  content: "more...";
  position: absolute;
  background: var(--clr-accent);
  height: 0.85em;
  width: fit-content;
  right: 0;
  transition: all 0.3s linear;
  bottom: 15%;
}

.images-hidden:hover::after,
.images-hidden:focus::after {
  height: 1em;
  padding: 0.2rem;
}

.button {
  cursor: pointer;
  display: inline-block;
  border: 0;
  background: transparent;
  color: var(--clr-text);
  font-size: 1.125rem;
  padding: 0.5em;
  position: relative;
  align-self: start;
  justify-self: start;
  right: -10px;
  z-index: 1;
}

.button::after {
  content: "";
  position: absolute;
  background: var(--clr-accent);
  height: 0.85em;
  width: 75%;
  right: 0;
  z-index: -1;
  transition: transform 175ms cubic-bezier(0.91, 0, 0.55, 1.64);
  transform-origin: top right;
}

.button:hover::after,
.button:focus::after {
  transform: scale(1.35, 1.85);
}
</style>
