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
  font-family: sans-serif;
}


input[type="text"],
input[type="number"],
input[type="password"],
input[type="email"],
textarea {
  font-size: 16px !important;
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