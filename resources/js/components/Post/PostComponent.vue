<template>
  <div class="post-wrapper" v-for="post in posts" :key="post.id">
    <h1 class="post-title">{{ post.title }}</h1>
    <img
      class="post-image mb-3"
      src="https://upload.wikimedia.org/wikipedia/commons/c/ce/Example_image.png"
      alt=""
    />
    <div class="post-content">
      <p>{{ post.content }}</p>
    </div>
    <div class="category">
      <button class="btn">{{ post.category.title }}</button>
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
export default {
  data() {
    return {
      posts: [],
    };
  },
  mounted() {
    this.getPosts();
  },
  methods: {
    getPosts() {
      axios.get("/api/admin/posts").then((res) => {
        this.posts = res.data.reverse();
      });
    },
    getFullDate(post) {
      let date = post.created_at.slice(0, 16).replace("T", " ");
      let t = date.split(/[- :]/);
      let time = new Date(Date.UTC(t[0], t[1] - 1, t[2], t[3], t[4]));
      return time.getDate() + "." + time.getMonth() + "." + time.getFullYear();
    },
  },
};
</script>

<style scoped>
.post-wrapper {
  margin: 0 0 3rem 0;
  position: relative;
  padding: 10px;
  margin-right: 3px;
  border-top: solid 1px var(--clr-accent);
  border-left: solid 1px #ff7700a9;
  border-bottom: solid 1px #ff77005e;
}

.post-wrapper::after {
  content: "";
  position: absolute;
  bottom: -25px;
  right: -24px;
  width: 3rem;
  height: 1px;
  transform: rotate(90deg);
  background-color: #ff770015;
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

.post-image {
  width: 100%;
}

.btn {
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

.btn::after {
  content: "";
  position: absolute;
  background: var(--clr-accent);
  height: 0.85em;
  width: 75%;
  right: 0;
  /* top: 50%; */
  z-index: -1;
  transition: transform 175ms cubic-bezier(0.91, 0, 0.55, 1.64);
  transform-origin: top right;
}

.btn:hover::after,
.btn:focus::after {
  transform: scale(1.35, 1.85);
}
</style>