<template>
    <router-view v-slot="slotProps">
        <transition name="route" mode="out-in">
            <component :is="slotProps.Component"></component>
        </transition>
    </router-view>
</template>

<script>
export default {
    mounted() {
        console.log("APP component mounted.");
    },
};
</script>

<style>
*,
*::before,
*::after {
    box-sizing: border-box;
}

:root {
    --clr-icons: #929292;
    --clr-text: #fff;
    --clr-bg-dark: #242424;
    --clr-bg-light: #3b3b3b;
    --clr-accent: #ff7600;
    --clr-touch: #cc5f00;
    --clr-dark-blue: #212529;
    --clr-dark-grey-strip: #383f45;
    --doc-height: 100%;
}

html {
    width: 100%;
    -webkit-overflow-scrolling: touch !important;
    height: 100% !important;
    background-color: #3b3b3b;
}

@font-face {
    font-family: "Arizonia";
    src: url(/fonts/Arizonia-Regular.ttf);
    font-weight: 400;
}

body {
    width: 100%;
    height: 100% !important;
    margin: 0;
    padding: 0;
    background-color: var(--clr-bg-dark);
    min-width: fit-content;

    color: var(--clr-text);
    line-height: 1.5;
    font-family: sans-serif;
}

body::-webkit-scrollbar {
    width: 12px;
    background-color: rgb(108, 108, 108);
}
body::-webkit-scrollbar:horizontal {
    height: 12px;
    margin-right: 20px;
}
body::-webkit-scrollbar-track {
    border: 1px #787878 solid;
    border-radius: 10px;
    -webkit-box-shadow: 0 0 6px #c8c8c8 inset;
}
body::-webkit-scrollbar-thumb {
    background-color: #efe4e4;
    border: 1px solid #000000;
    border-radius: 16px;
}
body::-webkit-scrollbar-thumb:hover {
    background-color: #d3c9c9;
    border: 1px solid #333333;
}
body::-webkit-scrollbar-thumb:active {
    background-color: #9b8a8b;
    border: 1px solid #333333;
}

input[type="text"],
input[type="number"],
input[type="password"],
input[type="email"],
textarea {
    font-size: 16px !important;
}

#app {
    height: 100%;
}

.bg-dark-blue {
    background-color: var(--clr-dark-blue);
}

.overflow {
    overflow: hidden;
}

.accent-color {
    color: var(--clr-accent);
}

/* animations */

.route-enter-from {
    opacity: 0.1;
    transform: translateY(-30px);
}
.route-enter-active {
    transition: all 0.7s ease-out;
}
.route-leave-active {
    transition: all 0.1s ease-in;
}
.route-leave-to {
    opacity: 0;
    transform: translateY(30px);
}

.modal-mask {
    position: absolute;
    z-index: 9998;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    display: table;
    transition: opacity 0.3s ease;
}

.modal-wrapper {
    width: 95%;
    height: fit-content;
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    max-width: 485px;
    margin: auto;
    background-color: #242424;
    /* border: 1px solid rgb(136, 136, 136); */
    border-radius: 10px;
    /* box-shadow: 0 0 20px 1px rgb(136, 136, 136); */
    z-index: 1;
}
</style>
