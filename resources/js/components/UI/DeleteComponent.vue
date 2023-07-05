<template>
    <div
        class="modal fade"
        id="deleteModal"
        tabindex="-1"
        role="dialog"
        aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true"
    >
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content text-dark">
                <div class="modal-header">
                    <h5
                        class="modal-title text-white"
                        id="exampleModalLongTitle"
                    >
                        Вы пытаетесь удалить <slot></slot>
                        <slot name="type"></slot>.
                    </h5>
                    <button
                        type="button"
                        class="close"
                        data-dismiss="modal"
                        aria-label="Close"
                    >
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-white">Вы уверены?</div>
                <div class="modal-footer">
                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-dismiss="modal"
                    >
                        Закрыть
                    </button>
                    <button
                        @click="$emit('deleteConfirm')"
                        data-dismiss="modal"
                        type="button"
                        class="btn btn-delete"
                    >
                        Удалить
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    emits: ["deleteConfirm"],
};
</script>

<style scoped>
.modal-content {
    background-color: #242424;
    color: #fff;
}

.btn-delete {
    background-color: var(--clr-accent);
    color: #fff;
}

.btn-delete:hover {
    background-color: var(--clr-touch);
}

.close {
    color: var(--clr-accent);
    text-shadow: none;
}
</style>
