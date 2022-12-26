<template>
  <div ref="dropzone" :style="{ wordBreak: 'break-word' }">
    Drag and drop images here or just click
  </div>
</template>

<script>
import Dropzone from "dropzone";
export default {
  emits: ["dropzone"],
  data() {
    return {
      dropzone: null,
    };
  },
  mounted() {
    this.dropzone = new Dropzone(this.$refs.dropzone, {
      url: "asf",
      autoProcessQueue: false,
      addRemoveLinks: true,
      maxFiles: 10,
      maxFilesize: 1,
    });
  },
  watch: {
    dropzone(val) {
      this.$emit("dropzone", val);
    },
  },
};
</script>

<style scoped>
</style>
