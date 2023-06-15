<template>
    <div v-if="index < 5" class="message-image" :id="`image${index + 1}`">
        <img
            :style="
                index + 1 === 5 && imagesQuantity > 5
                    ? 'opacity: 0.2'
                    : 'opacity: 1'
            "
            :src="`/${image.preview}`"
            alt=""
        />
        <div
            v-if="index + 1 === 5 && imagesQuantity > 5"
            class="images-quantity"
        >
            <h2>+{{ imagesQuantity - 5 }}</h2>
        </div>
    </div>
</template>

<script>
export default {
    props: ["image", "index", "imagesQuantity"],
};
</script>

<style scoped>
.message-image {
    /* flex-basis: 500px; */
    position: relative;
    display: flex;
    justify-content: center;
    align-items: center;
}

.message-image > img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 10px;
    /* box-shadow: 0 0 10px 1px #a5a5a5; */
}

.images-quantity {
    position: absolute;
    left: 50%;
    top: 50%;
    transform: translate(-50%, -50%);
}
</style>
