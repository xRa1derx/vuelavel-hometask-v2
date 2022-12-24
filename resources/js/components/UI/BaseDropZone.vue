<template>
    <div ref="dropzone" class="p-5 text-center">
        Upload images <br />
        Drag or click
    </div>
</template>

<script>
import Dropzone from "dropzone";
export default {
    emits: ["dropzone"],
    data() {
        return {
            dropzone: null,
        };
    },
    mounted() {
        this.dropzone = new Dropzone(this.$refs.dropzone, {
            url: "asf",
            autoProcessQueue: false,
            addRemoveLinks: true,
        });
    },
    watch: {
        dropzone(val) {
            this.$emit("dropzone", val);
        },
    },
};
</script>

<style></style>
