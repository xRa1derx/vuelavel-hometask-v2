<template>
    <img
        v-for="(image, index) in images"
        :key="image.id"
        class="post-image"
        :src="`/images/posts/${title}/${image.name}`"
        alt=""
        @click="showMultiple(index)"
    />
    <teleport to="body">
        <vue-easy-lightbox
            scrollDisabled
            escDisabled
            moveDisabled
            :visible="visible"
            :imgs="imgs"
            :index="index"
            @hide="handleHide"
        ></vue-easy-lightbox>
    </teleport>
</template>

<script>
import VueEasyLightbox from "vue-easy-lightbox";
export default {
    components: {
        VueEasyLightbox,
    },
    props: ["images", "title"],
    data() {
        return {
            imgs: "",
            visible: false,
            index: 0,
        };
    },

    methods: {
        showMultiple(index) {
            this.imgs = this.images.reduce((acc, image) => {
                acc.push(`/images/posts/${this.title}/${image.name}`);
                return acc;
            }, []);
            this.index = index;
            this.show();
        },
        show() {
            this.visible = true;
        },
        handleHide() {
            this.visible = false;
        },
    },
};
</script>

<style scoped>
.post-image {
    object-fit: cover;
    width: 100%;
    height: 250px;
    cursor: pointer;
    position: relative;
}

.last-image > img:last-of-type {
    grid-column: 1 / 3;
}
</style>
