<template>
    <div class="backdrop" @click="$emit('close')">
        <div
            :style="{ left: clientX + 'px', top: clientY + 'px' }"
            class="contextMenu"
        >
            <button
                @click="$emit('actionWithMessage', message, 'addFiles')"
                v-if="$store.state.auth.user.id === message.sender.id"
            >
                Add files
            </button>
            <button
                @click="$emit('actionWithMessage', message, 'reply')"
                v-if="message.message !== null"
            >
                Reply
            </button>
            <button
                v-if="$store.state.auth.user.id === message.sender.id"
                @click="$emit('actionWithMessage', message, 'edit')"
            >
                Edit
            </button>
            <button
                v-if="$store.state.auth.user.id === message.sender.id"
                @click="$emit('actionWithMessage', message, 'delete')"
            >
                Delete
            </button>
        </div>
    </div>
</template>

<script>
export default {
    props: ["message", "clientX", "clientY", "editBtn", "deleteBtn"],
    emits: ["replyMsg", "actionWithMessage", "close"],
};
</script>

<style scoped>
.contextMenu {
    display: flex;
    flex-direction: column;
    position: absolute;
    background-color: #fff;
    z-index: 3;
    box-shadow: 0 2px 6px 0 rgba(0, 0, 0, 0.3);
}

.backdrop {
    position: fixed;
    top: 0;
    left: 0;
    bottom: 0;
    right: 0;
    z-index: 3;
}

button {
    padding: 5px 15px 5px 15px;
    border: none;
}

button:hover {
    background-color: rgb(133, 133, 133);
}

@media (max-width: 550px) {
    button {
        padding: 10px 20px 10px 20px;
    }
}
</style>
