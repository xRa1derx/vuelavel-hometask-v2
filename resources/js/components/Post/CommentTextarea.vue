<template>
    <div>
        <textarea v-model="text" name="" id=""></textarea>
        <button @click="$emit('addComment', text)">add</button>
    </div>
</template>

<script>
export default {
    emits: ["addComment"],
    data() {
        return {
            text: null,
        };
    },
};
</script>

<style></style>
