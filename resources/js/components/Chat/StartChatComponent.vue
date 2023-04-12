<template>
    <div>
        <div v-if="!$route.params.id">show new messages</div>
        <chat-component v-else></chat-component>
    </div>
</template>

<script>
import ChatComponent from "./ChatComponent.vue";
export default {
    components: {
        ChatComponent,
    },
};
</script>

<style></style>
