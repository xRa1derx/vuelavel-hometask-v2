<template>
    <div class="admin-comments-wrapper">
        <comment-component
            v-for="comment in comments"
            :key="comment.id"
            :comment="comment"
            @getComments="getComments"
        ></comment-component>
    </div>
</template>

<script>
import axios from "axios";
import CommentComponent from "./CommentComponent.vue";
export default {
    components: {
        CommentComponent,
    },
    data() {
        return {
            comments: [],
            counterInterval: null,
        };
    },
    mounted() {
        this.getComments();
        // this.counterInterval = setInterval(() => {
        //     this.getComments();
        // }, 10000);
    },
    unmounted() {
        // clearInterval(this.counterInterval);
    },
    methods: {
        getComments() {
            axios
                .get("/api/admin/comments")
                .then((res) => (this.comments = res.data));
        },
    },
};
</script>

<style scoped>
.admin-comments-wrapper {
    max-width: 800px;
    margin: auto;
    background-color: #212529;
}
</style>
