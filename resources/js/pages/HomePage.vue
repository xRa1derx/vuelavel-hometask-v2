<template>
    <div class="main-page">
        <header>
            <h2>AO</h2>
            <nav class="nav">
                <a
                    @click="blogOpen('blog')"
                    href="#"
                    class="nav_link blog-btn"
                    :class="{ active: navActive == 'blog' }"
                    >Блог</a
                >
                <a
                    @click="materialsOpen('materials')"
                    href="#"
                    class="nav_link materials-btn"
                    :class="{ active: navActive == 'materials' }"
                    ><del>Материалы</del>
                    <span class="text-muted"
                        ><small>в разработке</small></span
                    ></a
                >
            </nav>
            <div
                v-if="!this.$store.state.auth.authenticated"
                class="user-login"
            >
                <button
                    class="user-login-btn nav-icon fas fa-door-open"
                    @click="loginOpen()"
                ></button>
            </div>
            <div v-else class="user-dashboard">
                <router-link
                    v-if="this.$store.state.auth.isAdmin"
                    :to="{ name: 'admin' }"
                    class="messanger"
                    ><i class="user-dashboard-btn nav-icon fas fa-user"></i
                ></router-link>
                <router-link
                    v-else
                    :to="{ name: 'dashboard' }"
                    class="messanger"
                    ><i class="user-dashboard-btn nav-icon fas fa-user"></i
                ></router-link>
                <div class="logout">
                    <button title="logout" @click="logout()" class="logout-btn">
                        <i class="fas fa-sign-out-alt"></i>
                    </button>
                </div>
            </div>
        </header>
        <main>
            <section class="greetings-page">
                <div class="title">
                    <h1 class="title__text">MY HOMETASK</h1>
                    <!-- <p class="title__personal">personal website</p> -->
                </div>
                <img
                    class="primary-image"
                    src="../../assets/images/main-image.jpg"
                    alt=""
                />
                <div class="about-me greetings-page__teacher">
                    <p class="about-me__teacher-title">
                        Немного о
                        <span class="about-me__teacher--accent-color"
                            >себе</span
                        >
                    </p>
                    <p class="greetings">
                        Здравствуйте, гости моего сайта, меня зовут Ожеред Алёна
                        Сергеевна. Я преподаю английский язык с 2008 года.
                    </p>
                    <div class="buttons">
                        <button title="в разработке" disabled class="btn">
                            Написать мне
                        </button>
                        <div class="social-link">
                            <a target="_blank" href="https://vk.com/alyonamilka"
                                ><img src="../../assets/images/vk.png" alt=""
                            /></a>
                        </div>
                    </div>
                </div>
                <img class="book" src="../../assets/images/book.svg" alt="" />
            </section>
            <transition name="slide">
                <section v-if="!show" class="blog">
                    <base-spinner v-if="isLoading"></base-spinner>
                    <post-component
                        v-if="postSelected == null"
                        @loading="loading"
                        @commentLink="commentLink"
                        :current_page_url="current_page"
                    ></post-component>
                    <post-selected
                        v-else
                        :postSelected="postSelected"
                        @loading="loading"
                        @backToBlog="postSelected = null"
                    ></post-selected>
                </section>
            </transition>
            <about-me></about-me>
            <service-page></service-page>
            <transition name="modal">
                <login-component
                    v-if="this.$store.state.isLoginOpen"
                ></login-component>
            </transition>
        </main>
    </div>
</template>

<script>
import PostComponent from "../components/Post/PostComponent.vue";
import LoginComponent from "../components/LoginComponent.vue";
import PostSelected from "../components/Post/PostSelected.vue";
import ServicePage from "../pages/ServicePage.vue";
import axios from "axios";
import { mapActions } from "vuex";
import AboutMe from "./AboutMe.vue";
export default {
    components: {
        PostComponent,
        LoginComponent,
        PostSelected,
        ServicePage,
        AboutMe,
    },
    data() {
        return {
            show: true,
            isActive: null,
            isLoading: false,
            postSelected: null,
            current_page: null,
            viewPortHeight: null,
        };
    },
    mounted() {
        const image = document.querySelector(".primary-image");
        if (image.classList.contains("blogClosed")) {
            image.classList.remove("blogClosed");
        }
        // if (document.body.classList.contains("overflow")) {
        //     document.body.classList.remove("overflow");
        // }
    },
    methods: {
        commentLink(id, current_page) {
            this.postSelected = id;
            this.current_page = current_page;
        },
        ...mapActions({
            signOut: "auth/logout",
            getRole: "auth/role",
            isLoginOpen: "loginOpen",
        }),
        async logout() {
            await axios.post("/logout").then((res) => {
                localStorage.removeItem(
                    "x_xsrf_token",
                    res.config.headers["X-XSRF-TOKEN"]
                );
                this.signOut();
            });
        },
        loginOpen() {
            this.isLoginOpen();
        },
        blogOpen(navSection) {
            if (localStorage.getItem("x_xsrf_token")) {
                this.getRole();
            }
            this.navSelectStatus(navSection);
            this.show = !this.show;
            this.blogMainImageResize();
            // document.body.classList.toggle("overflow");
        },
        materialsOpen(navSection) {
            if (this.show == false) {
                this.show = true;
                this.blogMainImageResize();
                // document.body.classList.toggle("overflow");
            }
            this.navSelectStatus(navSection);
        },
        navSelectStatus(navSection) {
            this.isActive == navSection
                ? (this.isActive = null)
                : (this.isActive = navSection);
        },
        blogMainImageResize() {
            const image = document.querySelector(".primary-image");
            if (this.show) {
                image.classList.add("resize");
                image.classList.remove("blogClosed");
            } else {
                image.classList.remove("resize");
                image.classList.add("blogClosed");
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
    box-shadow: inset rgba(0, 0, 0, 0.63) 0px 0px 15px 1px;
}

.blog-btn,
.materials-btn {
    border-radius: 10px;
    padding: 0 1rem;
}

@media (min-width: 800px) {
    .blog-btn:hover,
    .materials-btn:hover {
        color: var(--clr-touch);
        box-shadow: inset rgba(0, 0, 0, 0.205) 0px 0px 15px 1px;
    }
}

nav > a {
    color: #fff;
}

.user-dashboard {
    position: relative;
}

.user-login-btn,
.user-dashboard-btn {
    font-size: 1.7rem;
    border: none;
    background-color: inherit;
    color: var(--clr-accent);
}

.user-login-btn:hover,
.user-dashboard-btn:hover {
    color: var(--clr-touch);
}

img {
    max-width: 100%;
}

main {
    position: relative;
    height: 100%;
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

.title__text {
    color: var(--clr-accent);
    margin: 0;
    line-height: 1;
}
header {
    display: flex;
    align-items: center;
    padding: 1rem 1rem 1.5rem 1rem;
    gap: 5px;
    position: relative;
    z-index: 2;
    background-color: var(--clr-bg-dark);
}

header > h2 {
    font-family: "Arizonia", cursive;
    color: var(--clr-accent);
    line-height: 1;
    margin: 0;
}

.title {
    margin: 0 1rem 1.5rem 1rem;
}

.greetings {
    margin: 0 1rem;
}

.greetings-page {
    margin-bottom: 3rem;
}

.nav {
    flex-grow: 1;
    display: flex;
    justify-content: space-around;
}

.logout {
    width: 100%;
    position: absolute;
    z-index: 1;
    right: 0;
}

.logout-btn {
    width: 100%;
    margin: auto;
    padding: 0;
    background-color: inherit;
    color: #eee;
    border: none;
}

.nav_link {
    text-decoration: none;
    align-self: center;
}

.blog {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    width: 100%;
    overflow: auto;
    background-color: #242424;
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    box-shadow: 0px 30px 50px 30px #00000096;
    z-index: 1;
    height: 100vh;
    margin-top: -72px;
    padding: 72px 0;
}

.social-link {
    height: 30px;
    margin: auto 0;
}

.social-link > a > img {
    height: 100%;
}

.social-link:hover,
.social-link:focus {
    opacity: 0.5;
}

.book {
    display: none;
}

.messanger {
    color: inherit;
}

.user-dashboard > p {
    margin: 0;
}

.buttons {
    height: 45px;
    position: relative;
    display: flex;
    /* justify-content: space-between; */
    margin: 0 1rem;
}

.buttons > .btn {
    position: absolute;
    left: 0;
    top: 0;
    animation: contact 1s ease;
}

.social-link {
    position: absolute;
    right: 0;
    top: 8px;
    animation: socialLink 1s ease;
}

.about-me__teacher-title {
    margin: 1.5rem 1rem 0 1rem;
    font-size: 1.5rem;
}

.about-me__teacher--accent-color {
    color: var(--clr-accent);
}

@keyframes socialLink {
    from {
        opacity: 0;
        right: 50%;
    }
    to {
        right: 0;
    }
}

@keyframes contact {
    from {
        opacity: 0;
        left: 50%;
    }
    to {
        left: 0;
    }
}

.blog::-webkit-scrollbar {
    width: 0.5rem;
    background-color: #242424f6;
    /* border-radius: 10px; */
}
.blog::-webkit-scrollbar:horizontal {
    height: 12px;
    margin-right: 20px;
}
.blog::-webkit-scrollbar-thumb {
    background-color: #efe4e4;
    border: 1px solid #000000;
    /* border-radius: 16px; */
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
    /* 
    .title__personal {
        margin: 0;
    } */

    header {
        /* margin: 0 1rem 1.5rem 1rem; */
        padding: 0;
        padding-top: 1rem;
        background-color: inherit;
    }

    header > h2 {
        grid-column: 1;
        grid-row: 1;
    }

    .user-login,
    .user-dashboard {
        grid-column: 1;
        grid-row: 1;
        justify-self: end;
        padding-right: 1rem;
    }

    .logout {
        padding-right: 1rem;
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
        z-index: 0;
    }

    header {
        position: relative;
        height: 10vh;
        grid-column: 2 / 5;
        display: grid;
        grid-template-columns: repeat(3, 1fr);
    }

    .main-page::after {
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

    .main-page::after {
        grid-column: 3;
    }

    .nav {
        grid-column: 2 / 4;
        grid-row: 1;
    }

    main {
        position: static;
        grid-column: 1 / -1;
        display: grid;
        grid-template-columns:
            minmax(1em, 1fr)
            repeat(3, minmax(15rem, 35rem))
            minmax(1em, 1fr);
    }

    .greetings-page {
        position: relative;
        height: 90vh;
        grid-column: 2 / -2;
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
        grid-column: 3 / 5;
        grid-row: 1;
        overflow: auto;
        height: 79.9vh;
        z-index: 5;
        background-color: #242424f6;
        padding: 0;
        border-radius: 10px;
    }

    .primary-image {
        grid-column: 2 / -1;
        grid-row: 1 / 4;
        align-self: center;
        z-index: -1;
    }

    .resize {
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

    .blogClosed {
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

    .greetings-page__teacher {
        grid-row: 2/4;
        margin-top: auto;
    }

    .title {
        margin: 0;
        grid-column: 1 / 2;
        grid-row: 1;
        align-self: end;
    }

    .title__text {
        font-size: clamp(2rem, 5vw, 6rem);
    }

    /* .title__personal, */
    .greetings,
    .about-me__teacher-title {
        margin: 0;
    }

    .buttons {
        position: relative;
        margin: 0;
    }

    .greetings-page::after {
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

    @media (max-height: 550px) {
        .primary-image {
            /* width: 100vh; */
            object-fit: cover;
        }
    }
}

@media (min-width: 1200px) {
    .main-page {
        /* max-width: 1600px; */
    }

    .greetings-page {
        max-width: 1280px;
        margin: auto;
        margin-bottom: 3rem;
        grid-row: 1;
        grid-template-columns:
            minmax(1em, 1fr)
            repeat(3, minmax(15rem, 35rem))
            minmax(1em, 1fr);
    }

    .blog {
        max-width: 850px;
        grid-row: 1;
    }

    .greetings-page__teacher {
        grid-column: 1 / 3;
        grid-row: 2 / 4;
        margin: auto;
    }

    .user-login,
    .user-dashboard {
        grid-column: 3;
        justify-self: end;
        padding-right: 0;
    }

    .logout {
        padding-right: 0;
    }

    .primary-image {
        grid-column: 3 / -1;
    }

    .title {
        grid-column: 1 / 3;
    }

    .title__text {
        text-shadow: 1px 1px #00000063;
        font-size: clamp(4rem, 6vw, 5.5rem);
    }

    /* .title__personal, */
    .greetings {
        grid-column: 1 / 3;
    }

    .buttons {
        grid-column: 1 / 3;
        grid-row: 3;
    }

    .social-link {
        margin-right: 1rem;
    }

    .resize {
        grid-column: 3 / 6;
        animation: resize 0.5s linear;
    }

    @keyframes resize {
        0% {
            max-width: 50%;
        }
    }

    .blogClosed {
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
        max-width: 1280px;
        width: 100%;
        margin: auto;
        grid-column: 2 / 5;
        grid-template-columns: repeat(3, 1fr);
    }

    header > .nav {
        grid-column: 2;
    }

    main::after {
        grid-column: 3 /4;
    }

    .greetings-page::after {
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

    .greetings-page::before {
        grid-row: 1 / 3;
        grid-column: 1 / 3;
    }

    @media (max-height: 850px) {
        .primary-image {
            /* width: 100vh; */
            object-fit: cover;
        }
    }
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

.modal-enter-from {
    opacity: 0;
    transform: scale(1.5);
}

.modal-enter-active {
    transition: all 0.2s ease-in;
}
.modal-leave-active {
    transition: all 0.5s ease-in-out;
}

.modal-leave-to {
    opacity: 0;
    transform: scale(0);
}
</style>
