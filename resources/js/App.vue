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
  --clr-dark-blue: #212529;
  --clr-dark-grey-strip: #383f45;
}

html,
body {
  width: 100%;
  height: 100%;
}

body {
  margin: 0;
  padding: 0;
  min-width: fit-content;
  background-color: var(--clr-bg-dark);
  color: var(--clr-text);
  line-height: 1.5;
  /* font-family: sans-serif; */
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

.bg-dark-blue {
  background-color: var(--clr-dark-blue);
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
</style>
