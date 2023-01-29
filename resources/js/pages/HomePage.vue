<template>
    <div class="main-page">
        <header>
            <h1>AO</h1>
            <nav class="nav">
                <a
                    @click="blogOpen('blog')"
                    href="#"
                    class="nav_link"
                    :class="{ active: navActive == 'blog' }"
                    >Blog</a
                >
                <a
                    @click="materialsOpen('materials')"
                    href="#"
                    class="nav_link"
                    :class="{ active: navActive == 'materials' }"
                    >Materials</a
                >
                <router-link :to="{ name: 'admin' }" class="login-btn"
                    >Messanger</router-link
                >
            </nav>
        </header>
        <main>
            <section class="fullscreen">
                <div class="title-wrap">
                    <h1 class="title">MY HOMETASK</h1>
                    <p class="personal">personal website</p>
                </div>
                <img
                    ref="image"
                    class="primary-image"
                    src="../../assets/images/main-image.jpg"
                    alt=""
                />
                <p class="greetings">
                    Lorem ipsum dolor sit amet consectetur adipisicing elit.
                    Ducimus nihil veniam quod vero? Ab praesentium obcaecati
                    molestiae? Recusandae ipsa rem.
                </p>
                <div class="buttons">
                    <button class="btn">Contact</button>
                    <div class="social-link">
                        <a href=""
                            ><img src="../../assets/images/vk.png" alt=""
                        /></a>
                    </div>
                </div>
                <img class="book" src="../../assets/images/book.svg" alt="" />
            </section>
            <transition name="slide">
                <section v-if="!show" class="blog">
                    <base-spinner v-if="isLoading"></base-spinner>
                    <post-component @loading="loading"></post-component>
                </section>
            </transition>
            <section class="second-page">
                Lorem ipsum dolor sit amet consectetur adipisicing elit. Officia
                earum veritatis aspernatur, voluptatum debitis nam eaque, quas
                deleniti laboriosam a nihil molestiae, atque voluptate quis
                excepturi magnam laudantium libero reiciendis.
            </section>
        </main>
    </div>
</template>

<script>
import PostComponent from "../components/Post/PostComponent.vue";
export default {
    components: { PostComponent },
    data() {
        return {
            show: true,
            isActive: null,
            isLoading: false,
        };
    },
    mounted() {
        console.log("Home Component mounted.");
        // $('[data-widget = "treeview"]').Treeview("init");
    },
    methods: {
        blogOpen(navSection) {
            this.navSelectStatus(navSection);
            this.show = !this.show;
            this.blogMainImageResize();
        },
        materialsOpen(navSection) {
            if (this.show == false) {
                this.show = true;
                this.blogMainImageResize();
            }
            this.navSelectStatus(navSection);
        },
        navSelectStatus(navSection) {
            this.isActive == navSection
                ? (this.isActive = null)
                : (this.isActive = navSection);
        },
        blogMainImageResize() {
            if (this.show) {
                this.$refs.image.classList.add("resize");
                this.$refs.image.classList.remove("close");
            } else {
                this.$refs.image.classList.remove("resize");
                this.$refs.image.classList.add("close");
            }
        },
        loading(val) {
            this.isLoading = val;
        },
    },
    computed: {
        navActive() {
            return this.isActive;
        },
    },
};
</script>

<style scoped>
.active {
    color: var(--clr-accent);
    border-bottom: 1px solid var(--clr-accent);
}

nav > a {
    color: #fff;
}

.main-page {
    /* overflow-x: hidden; */
    /* margin: 1rem 0; */
}

img {
    max-width: 100%;
}

main {
    position: relative;
    height: 100vh;
}

.btn {
    cursor: pointer;
    display: inline-block;
    border: 0;
    background: transparent;
    color: var(--clr-text);
    font-size: 1.125rem;
    padding: 0.5em;
    position: relative;
    align-self: start;
    justify-self: start;
}

.btn::after {
    content: "";
    position: absolute;
    background: var(--clr-accent);
    height: 0.85em;
    width: 75%;
    left: 0;
    top: 50%;
    z-index: -1;
    transition: transform 175ms cubic-bezier(0.91, 0, 0.55, 1.64);
    transform-origin: bottom left;
}

.btn:hover::after,
.btn:focus::after {
    transform: scale(1.35, 1.85);
}

.title {
    color: var(--clr-accent);
    margin: 0;
    line-height: 1;
    font-size: clamp(3rem, 5vw, 7rem);
}

.personal {
    color: var(--clr-icons);
    margin: 0;
    font-size: 1.5rem;
    margin-bottom: 1.5em;
    font-style: italic;
}

header {
    display: flex;
    align-items: center;
    margin: 0 1rem;
    padding-top: 1rem;
}

header > h1 {
    font-family: "Arizonia", cursive;
    color: var(--clr-accent);
    line-height: 1;
}

.title-wrap {
    margin: 0 1rem;
}

.greetings {
    margin: 0 1rem;
}

.nav {
    flex-grow: 1;
    display: flex;
    justify-content: space-around;
}

.nav_link {
    text-decoration: none;
    align-self: center;
}

.blog {
    width: 95%;
    margin: 0 auto;
    height: 90vh;
    overflow: auto;
    background-color: #242424;
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    border: 1px solid rgb(108, 108, 108);
    border-radius: 10px;
    box-shadow: 0 0 20px 1px rgb(108 108 108);
    z-index: 0;
}

.nav_link:hover {
    color: var(--clr-accent);
}
.social-link:hover,
.social-link:focus {
    opacity: 0.5;
}

.book {
    display: none;
}

.login-btn {
    color: inherit;
}

.buttons {
    display: flex;
    justify-content: space-between;
    margin: 0 1rem;
}

.blog::-webkit-scrollbar {
    width: 0.8rem;
    background-color: #242424f6;
}
.blog::-webkit-scrollbar:horizontal {
    height: 12px;
    margin-right: 20px;
}
.blog::-webkit-scrollbar-thumb {
    background-color: #efe4e4;
    border: 1px solid #000000;
    border-radius: 16px;
}
.blog::-webkit-scrollbar-thumb:hover {
    background-color: #d3c9c9;
    border: 1px solid #333333;
}
.blog::-webkit-scrollbar-thumb:active {
    background-color: #9b8a8b;
    border: 1px solid #333333;
}

@media (min-width: 800px) {
    .personal {
        margin: 0;
    }
    .main-page {
        width: 100%;
        height: 100%;
        display: grid;
        margin: auto;
        grid-template-columns:
            minmax(1em, 1fr)
            repeat(3, minmax(15rem, 35rem))
            minmax(1em, 1fr);
        position: relative;
    }

    header {
        margin: 0;
        position: relative;
        height: 10vh;
        grid-column: 2 / 5;
        display: grid;
        grid-template-columns: repeat(3, 1fr);
    }

    header::after {
        content: "";
        position: absolute;
        width: 1px;
        height: 100vh;
        top: 0;
        background-color: var(--clr-accent);
        background: linear-gradient(
            rgba(255, 118, 0, 1) 90%,
            rgba(255, 255, 255, 0) 100%
        );
        z-index: -1;
    }

    header::after {
        grid-column: 2;
    }

    .nav {
        grid-column: 2 / 4;
        grid-row: 1;
    }

    main {
        position: static;
        grid-column: 2 / -2;
        display: grid;
        grid-template-columns:
            minmax(1em, 1fr)
            repeat(3, minmax(15rem, 35rem))
            minmax(1em, 1fr);
    }

    .fullscreen {
        position: relative;
        height: 90vh;
        grid-column: 1 / -1;
        grid-row: 1;
        display: grid;
        grid-template-columns: repeat(3, minmax(15rem, 35rem));
        align-content: center;
        gap: 1rem;
        padding-bottom: 15vh;
    }

    .blog {
        width: 100%;
        margin: 0;
        position: relative;
        grid-column: 3 / 6;
        grid-row: 1;
        overflow: auto;
        height: 79.9vh;
        z-index: 5;
        background-color: #242424f6;
        border-radius: 10px;
        -webkit-box-shadow: 0 0 15px 3px rgb(108, 108, 108);
        -moz-box-shadow: 0 0 15px 3px rgb(108, 108, 108);
        box-shadow: 0 0 20px 1px rgb(108, 108, 108);
    }

    .primary-image {
        grid-column: 2 / -1;
        grid-row: 1 / 4;
        align-self: center;
        z-index: -1;
    }

    .resize {
        /* grid-column: 3 / -2; */
        animation: resize 1s linear;
    }

    @keyframes resize {
        0% {
            opacity: 0;
        }
        100% {
            opacity: 1;
        }
    }

    .close {
        animation: close 0.5s linear;
        opacity: 0;
    }

    @keyframes close {
        0% {
            opacity: 1;
        }
    }

    .book {
        display: block;
        position: absolute;
        left: 10%;
        grid-column: 1 / 3;
        grid-row: 1 / 4;
        align-self: end;
        z-index: -10;
        opacity: 0.4;
    }

    main::after {
        content: "";
        position: absolute;
        background-color: var(--clr-bg-light);
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        grid-column: 3 / 5;
        z-index: -10;
        height: 90vh;
    }

    .title-wrap {
        margin: 0;
        grid-column: 1 / 2;
        grid-row: 1;
        align-self: end;
    }

    .personal,
    .greetings {
        margin: 0;
        grid-column: 1 / 2;
    }

    .buttons {
        position: relative;
        margin: 0;
    }

    .fullscreen::after {
        content: "";
        position: absolute;
        bottom: 10vh;
        left: 0;
        width: 100%;
        height: 1px;
        background: var(--clr-accent);
        background: linear-gradient(
            90deg,
            rgba(255, 255, 255, 0) 0%,
            rgba(255, 118, 0, 1) 25%,
            rgba(255, 118, 0, 1) 100%
        );
        grid-column: 1 / 6;
        z-index: 1;
    }

    .social-link {
        height: 27px;
        margin: 9px;
        margin-right: 1rem;
    }

    .social-link img {
        object-fit: cover;
        height: 100%;
    }

    @media (max-height: 550px) {
        .primary-image {
            width: 100vh;
            object-fit: cover;
        }
    }
}

@media (min-width: 1200px) {
    .main-page {
        max-width: 1600px;
    }

    .fullscreen {
        grid-row: 1;

        grid-template-columns:
            minmax(1em, 1fr)
            repeat(3, minmax(15rem, 35rem))
            minmax(1em, 1fr);
    }

    .blog {
        grid-column: 3 / 4;
        grid-row: 1;
    }

    .primary-image {
        grid-column: 3 / 5;
    }

    .title-wrap {
        grid-column: 1 / 4;
    }

    .personal,
    .greetings {
        grid-column: 1 / 3;
    }

    .buttons {
        grid-column: 1 / 3;
        grid-row: 3;
    }

    /* .social-link {
        grid-column: 2 / 3;
    } */

    .resize {
        grid-column: 3 / 5;
        animation: resize 0.5s linear;
    }

    @keyframes resize {
        0% {
            max-width: 50%;
        }
    }

    .close {
        grid-column: 3;
        animation: close 0.5s linear;
        opacity: 0;
    }

    @keyframes close {
        0% {
            max-width: 200%;
            opacity: 1;
        }
    }

    header {
        grid-column: 2 / 5;
        grid-template-columns: repeat(3, 1fr);
    }

    header > .nav {
        grid-column: 2;
    }

    main::after {
        grid-column: 3 /4;
    }

    .fullscreen::after {
        grid-column: 1 / 6;
        background: linear-gradient(
            90deg,
            rgba(255, 255, 255, 0) 0%,
            rgba(255, 118, 0, 1) 25%,
            rgba(255, 118, 0, 1) 50%,
            rgba(255, 118, 0, 1) 75%,
            rgba(255, 255, 255, 0) 100%
        );
    }

    .fullscreen::before {
        grid-row: 1 / 3;
        grid-column: 1 / 3;
    }

    @media (max-height: 850px) {
        .primary-image {
            width: 100vh;
            object-fit: cover;
        }
    }
}

.second-page {
    margin: 0 1rem;
    grid-column: 1 / -1;
}

.slide-enter-from {
    transform: translateX(250px);
    opacity: 0;
}

.slide-enter-active {
    transition: all 0.5s ease-in;
}
.slide-leave-active {
    transition: all 0.5s ease-in-out;
}

.slide-leave-to {
    transform: translateX(250px);
    opacity: 0;
}
</style>
