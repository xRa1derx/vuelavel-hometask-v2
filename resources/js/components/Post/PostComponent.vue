<template>
  <div class="post-wrapper" v-for="post in posts" :key="post.id">
    <h1 class="post-title">{{ post.title }}</h1>
    <div class="post-image-container" :id="post.title" ref="imageContainer">
      <base-lightbox :images="post.images" :title="post.title"></base-lightbox>
    </div>
    <div class="more-images text-center mb-3" @click="moreImages(post)">
      more...
    </div>
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
    moreImages(post) {
      const images = document.getElementById(`${post.title}`);
      const moreButton = images.nextElementSibling;
      images.classList.toggle("images-hidden");
      images.classList.contains("images-hidden")
        ? (moreButton.textContent = "more...")
        : (moreButton.textContent = "hide");
    },
  },
};
</script>

<style scoped>
.post-wrapper {
  margin: 0 0 5rem 0;
  position: relative;
  padding: 1rem 3rem;
  margin-right: 3px;
  border-bottom: 1px solid rgb(108, 108, 108);
  /* border-top: solid 1px var(--clr-accent);
    border-left: solid 1px #ff7700a9;
    border-bottom: solid 1px #ff77005e;
    border-radius: 1rem 0 0 1rem; */

  /* border-top: solid 1px rgba(255, 255, 255, 0.166);
  border-left: solid 1px rgba(255, 255, 255, 0.166);
  border-bottom: solid 1px rgba(255, 255, 255, 0.166);
  border-radius: 1rem 0 0 1rem; */
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
  display: grid;
  gap: 2px;
  grid-template-columns: repeat(2, 1fr);
}

.images-hidden {
  max-height: 300px;
  overflow: hidden;
}
.more-images {
  display: none;
  cursor: pointer;
  background-color: rgba(255, 255, 255, 0.103);
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
