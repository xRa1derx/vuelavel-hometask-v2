<template>
  <div
    class="modal fade bd-example-modal-sm"
    id="confirmToEdit"
    ref="confirmToEdit"
    tabindex="-1"
    role="dialog"
    aria-labelledby="mySmallModalLabel"
    aria-hidden="true"
  >
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-body">
          <h5 class="text-center mb-0">Сохранить изменения?</h5>
        </div>
        <div class="modal-footer mx-auto">
          <button class="btn btn-secondary" @click.prevent="$refs.confirmToEdit.click()">Отмена</button>
          <slot></slot>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {};
</script>

<style scoped>
.modal-content {
  background-color: var(--clr-dark-grey-strip);
  font-size: clamp(0.6rem, 1.3vw, 5rem);
  margin: 1rem;
}
.modal-body {
  border-bottom: none;
}
.modal-footer {
  border-top: 1px solid var(--clr-dark-grey-strip);
}
</style>