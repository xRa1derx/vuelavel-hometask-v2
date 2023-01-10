


<template>
  <div ref="dropzone" class="dropzone"></div>
  <div v-if="!dropzone" id="template-preview">
    <div class="dz-preview dz-file-preview">
      <div class="dz-details d-flex">
        <div class="dz-custom-image-wrap">
          <img data-dz-thumbnail />
        </div>
        <div class="d-flex flex-column">
          <div class="dz-filename">
            <span class="text-muted">File name: </span>
            <span data-dz-name></span>
          </div>
          <div class="dz-size">
            <span class="text-muted">Size: </span><span data-dz-size></span>
          </div>
          <div class="dz-error-message">
            <span class="text-danger" data-dz-errormessage></span>
          </div>
        </div>
      </div>
      <div class="dz-progress">
        <span class="dz-upload" data-dz-uploadprogress></span>
      </div>
      <a class="dz-custom-remove text-warning" data-dz-remove
        ><i class="nav-icon fas fa-trash-alt"></i
      ></a>
    </div>
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
      url: "empty",
      autoProcessQueue: false,
      addRemoveLinks: true,
      maxFiles: 10,
      maxFilesize: 2,
      previewTemplate: document.getElementById("template-preview").innerHTML,
      acceptedFiles: "image/*",
    });
  },
  watch: {
    dropzone(val) {
      this.$emit("dropzone", val);
    },
  },
};
</script>

<style>
.dropzone {
  word-break: break-word;
}

.dz-button {
  width: 100%;
  text-align: center;
  padding: 2rem 1rem;
  border: dashed 5px rgba(255, 255, 255, 0.152);
  border-radius: 20px;
  background-color: #343a40;
  transition: all 0.3s linear;
  color: #fff;
}

.dz-preview {
  display: flex;
  /* flex-wrap: wrap; */
  justify-content: space-between;
  padding: 2rem;
  border-bottom: 1px solid var(--clr-dark-grey-strip);
}

.dz-button:hover {
  box-shadow: 0px 0px 10px 1px #fff;
  border: solid 5px rgba(255, 255, 255, 0.754);
  scale: 1.01;
}

.dz-remove {
  display: none;
}

.dz-details {
  flex-wrap: wrap;
  gap: 1rem;
}

.dz-custom-image-wrap {
  width: 120px;
}

.dz-details img {
  border-radius: 30px;
}

.dz-custom-remove {
  display: flex;
  align-self: center;
}

.dz-custom-remove > i {
  padding: 2rem;
  cursor: pointer;
}

.dz-filename {
  max-width: 250px;
}
</style>
