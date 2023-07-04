<template>
    <lazy-component
        v-if="index < 5"
        class="message-image"
        :id="`image${index + 1}`"
    >
        <!-- <img
            v-if="!isLoaded"
            lazy="loading"
            :src="`/${image.preview}`"
        /> -->
        <img
            :style="
                index + 1 === 5 && imagesQuantity > 5
                    ? 'opacity: 0.2; z-index: 1;'
                    : 'opacity: 1'
            "
            :src="`/${image.preview}`"
            alt=""
            lazy="loaded"
            @click="showMultiple(index)"
            @load="onImgLoad($event)"
        />
        <div
            v-if="index + 1 === 5 && imagesQuantity > 5 && isLoaded"
            class="images-quantity"
        >
            <h3>+{{ imagesQuantity - 5 }}</h3>
        </div>
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
    </lazy-component>
</template>

<script>
import { ref } from "vue";
import VueEasyLightbox from "vue-easy-lightbox";
export default {
    components: {
        VueEasyLightbox,
    },
    props: ["image", "images", "index", "imagesQuantity"],
    setup(props) {
        const isLoaded = ref(false);
        const imgs = ref("");
        const activeIndex = ref(0);
        const visible = ref(false);
        const showMultiple = (index) => {
            imgs.value = props.images.reduce((acc, image) => {
                acc.push(`/messages/images/${image.name}`);
                return acc;
            }, []);
            activeIndex.value = index;
            show();
        };
        const show = () => {
            visible.value = true;
        };
        const handleHide = () => {
            visible.value = false;
        };
        const onImgLoad = (e) => {
            const res = e.target.attributes.lazy.value;
            const src = e.target.src[0];
            const target = e.target;
            if (res == "loaded" && src != "d") {
                target.style.visibility = "visible";
                target.style.display = "block";
                isLoaded.value = true;
                
            }
        };
        return {
            onImgLoad,
            showMultiple,
            show,
            visible,
            imgs,
            activeIndex,
            handleHide,
            isLoaded,
        };
    },
};
</script>

<style scoped>
.message-image {
    /* flex-basis: 500px; */
    position: relative;
    display: flex;
    justify-content: center;
    align-items: center;
    cursor: pointer;
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
    color: rgb(51, 51, 51);
}

img[lazy="loaded"] {
    display: none;
    /* border-radius: 10px; */
}
</style>
