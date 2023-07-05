<template>
    <teleport to="body">
        <transition-group
            class="alert-message"
            tag="div"
            @before-enter="beforeEnter"
            @enter="onEnter"
            @before-leave="beforeLeave"
            @leave="leave"
        >
            <p
                v-show="newCommentNotification"
                v-for="(message, index) of alertMessages"
                :key="index"
                :data-index="index"
                :data-width="message.width"
            >
                {{ message.text }}
            </p>
        </transition-group>
    </teleport>
</template>

<script>
import gsap from "gsap";
export default {
    props: ["newCommentNotification", "alertMessages"],
    methods: {
        beforeEnter(el) {
            el.style.width = 0;
            el.style.opacity = 0.3;
        },
        onEnter(el, done) {
            gsap.to(el, {
                opacity: 1,
                width: el.dataset.width,
                duration: 0.7,
                onComplete: done,
                delay: el.dataset.index * 0.5,
            });
        },
        beforeLeave(el) {
            el.style.width = el.dataset.width;
        },
        leave(el, done) {
            gsap.to(el, {
                opacity: 0,
                width: 0,
                duration: 0.3,
                onComplete: done,
                delay: el.dataset.index * 0.1,
            });
        },
    },
};
</script>

<style scoped>
.alert-message {
    display: flex;
    flex-direction: column;
    gap: 2px;
    position: fixed;
    bottom: 150px;
    z-index: 999;
    width: 270px;
}

.alert-message p {
    background-color: #7ac142;
    margin: 0;
    color: black;
    white-space: nowrap;
    overflow: hidden;
    font-size: 0.8rem;
    padding-left: 0.5rem;
    box-shadow: 0 0 2px 1px rgba(0, 0, 0, 0.582);
}

.alert-leave-active {
    transition: all 0.5s ease-out;
    padding-left: 0.5rem;
}

.alert-leave-from {
    padding-left: 0.5rem;
    width: 270px;
    transition: all 0.5s ease-out;
}

.alert-leave-to {
    width: 0;
    padding-left: 0;
    opacity: 0;
}
</style>
