<template>
  <form class="mx-auto">
    <div class="form-group">
      <label for="title">Title</label>
      <input
        type="title"
        class="form-control"
        id="title"
        placeholder="Enter title"
        aria-describedby="titleHelp"
      />
      <small id="titleHelp" class="form-text text-muted"
        >Give a title to your new post</small
      >
    </div>
    <div class="form-group">
      <div class="form-row align-items-start justify-content-between">
        <div class="col-auto my-1">
          <label class="mr-sm-2" for="category">Category</label>
          <select
            class="custom-select mr-sm-2"
            id="category"
            aria-describedby="categoryHelp"
          >
            <option selected>Choose...</option>
            <option value="1">One</option>
            <option value="2">Two</option>
            <option value="3">Three</option>
          </select>
          <small id="categoryHelp" class="form-text text-muted"
            >Choose a category</small
          >
        </div>
        <div class="tagSelect col-auto my-1 w-50">
          <label for="tags">Tags</label>
          <select
            multiple
            class="form-control"
            id="tags"
            aria-describedby="tagHelp"
          >
            <option>1</option>
            <option>2</option>
            <option>3</option>
            <option>4</option>
            <option>5</option>
          </select>
          <small id="tagHelp" class="form-text text-muted"
            >Select a tag. <br />
            Use <span class="text-warning">'CTRL' + 'left mouse click'</span> to
            select multiple tags</small
          >
        </div>
      </div>
    </div>
    <div class="form-group">
      <label for="text">Content</label>
      <textarea class="form-control" id="text" rows="3"></textarea>
    </div>
  </form>
</template>

<script>
export default {};
</script>

<style scoped>
form {
  max-width: 800px;
}

@media (max-width: 391px) {
  .tagSelect {
    width: 100% !important;
  }
}
</style>