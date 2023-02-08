<template>
    <lazy-component
        v-for="(image, index) in images"
        :key="image.id"
        :class="{
            'last-image': index === images.length - 1 && images.length % 2 != 0,
        }"
    >
        <img v-if="!isLoaded" lazy="loading" />
        <img
            lazy="loaded"
            class="post-image"
            alt=""
            :src="`/images/posts/${image.preview}`"
            @click="showMultiple(index)"
            @load="onImgLoad($event)"
        />
    </lazy-component>
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
    props: ["images"],
    data() {
        return {
            imgs: "",
            visible: false,
            index: 0,
            isLoaded: false,
        };
    },
    methods: {
        showMultiple(index) {
            this.imgs = this.images.reduce((acc, image) => {
                acc.push(`/images/posts/${image.name}`);
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
        onImgLoad(e) {
            const res = e.target.attributes.lazy.value;
            const src = e.target.src[0];
            const target = e.target;
            if (res == "loaded" && src != "d") {
                target.style.visibility = "visible";
                target.style.display = "block";
                this.isLoaded = true;
            }
        },
    },
};
</script>

<style scoped>
.last-image {
    grid-column: 1 / 3;
    width: 70%;
    margin: auto;
}

img[lazy="loading"] {
    background-color: #eee;
    height: 250px;
    width: 100%;
    border-radius: 10px;
    animation: fade 1s ease-in-out infinite alternate;
}

img[lazy="loaded"] {
    display: none;
    border-radius: 10px;
}

.post-image {
    object-fit: cover;
    width: 100%;
    height: 250px;
    cursor: pointer;
    position: relative;
    animation: fade 1s linear;
    visibility: hidden;
}

@keyframes fade {
    0% {
        opacity: 0;
    }
    100% {
        opacity: 1;
    }
}
</style>
