<template >
<div>
        <div class="d-flex flex-column">
        {{ this.$store.state.auth.user.name }}
        <img
            :src="`/uploads/avatars/${this.$store.state.auth.user.avatar || 'no-user-image.png'}`"
            alt=""
        />
    </div>
</div>
</template>

<script>
export default {};
</script>

<style scoped>
img{
    width: 200px;
    object-fit: cover;
}
</style>
