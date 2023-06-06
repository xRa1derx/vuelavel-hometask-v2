<template>
    <div>
        <!-- <div class="d-flex flex-column">
            {{ this.$store.state.auth.user.name }}
            <img
                :src="`/uploads/avatars/${
                    this.$store.state.auth.user.avatar || 'no-user-image.png'
                }`"
                alt=""
            />
        </div> -->
        <chat-component></chat-component>
    </div>
</template>

<script>
import ChatComponent from "../components/Chat/ChatComponent.vue";
export default {
    components: {
        ChatComponent,
    },
};
</script>

<style scoped>
img {
    width: 200px;
    object-fit: cover;
}
</style>
