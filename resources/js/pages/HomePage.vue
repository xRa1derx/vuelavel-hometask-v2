<template>
    <div class="main-page">
        <header>
            <h1>AO</h1>
            <nav class="nav">
                <a href="#" class="nav_link">Home</a>
                <a @click="blog()" href="#" class="nav_link">Blog</a>
                <a href="#" class="nav_link">Materials</a>
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
                <button class="btn">Contact</button>
                <div class="social-link">
                    <a href=""
                        ><img src="../../assets/images/vk.png" alt=""
                    /></a>
                </div>
                <img class="book" src="../../assets/images/book.svg" alt="" />
            </section>
            <transition appear name="slide">
                <section v-if="show" class="blog">
                    <post-component></post-component>
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
        };
    },
    mounted() {
        console.log("Home Component mounted.");
    },
    methods: {
        blog() {
            this.show = !this.show;
            if (this.show) {
                this.$refs.image.classList.remove("resize");
                this.$refs.image.classList.add("close");
            } else {
                this.$refs.image.classList.add("resize");
                this.$refs.image.classList.remove("close");
            }
        },
    },
};
</script>

<style scoped>
.main-page {
    overflow-x: hidden;
    margin: 1rem;
}

img {
    max-width: 100%;
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
}

header > h1 {
    font-family: "Arizonia", cursive;
    color: var(--clr-accent);
    line-height: 1;
}

.nav {
    flex-grow: 1;
    display: flex;
    justify-content: space-around;
}

.nav_link {
    color: var(--clr-text);
    text-transform: capitalize;
    text-decoration: none;
    align-self: center;
}

.nav_link:hover,
.nav_link:focus {
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

    .blog {
        position: relative;
        grid-column: 3 / 5;
    }

    header {
        position: relative;
        height: 10vh;
        grid-column: 2 / 5;
        display: grid;
        grid-template-columns: repeat(3, 1fr);
    }

    header::after {
        content: "";
        position: absolute;
        grid-column: 4;
        width: 1px;
        height: 100vh;
        top: 0;
        background-color: var(--clr-accent);
        z-index: -1;
    }

    .nav {
        grid-column: 2 / 4;
        grid-row: 1;
    }

    main {
        grid-column: 2 / -1;
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
        display: grid;
        grid-template-columns:
            minmax(1em, 1fr)
            repeat(2, minmax(15rem, 35rem))
            minmax(5rem, 10rem)
            minmax(1em, 1fr);
        align-content: center;
        column-gap: 1rem;
        padding-bottom: 15vh;
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
        grid-column: 1 / 4;
        grid-row: 1;
        align-self: end;
    }

    .personal,
    .greetings {
        grid-column: 1 / 3;
    }

    .primary-image {
        grid-column: 3 / -2;
        grid-row: 1 / 4;
        z-index: -1;
        align-self: center;
    }
    .btn {
        grid-column: 1 / 3;
        grid-row: 3;
    }
    .social-link {
        position: relative;
        grid-column: 2 / 3;
        grid-row: 3;
        justify-self: end;
    }

    .fullscreen::after {
        content: "";
        position: absolute;
        bottom: 10vh;
        left: 0;
        width: 100%;
        height: 1px;
        background-color: #ff77005e;
        grid-column: 1 / 6;
        z-index: -2;
    }

    .social-link img {
        width: 60%;
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
        grid-column: 4;
        grid-row: 1;
    }

    .primary-image {
        grid-column: 3;
    }

    .resize {
        grid-column: 3 / 5;
        animation: resize 0.5s linear;
    }

    @keyframes resize {
        0% {
            width: 50%;
        }
        100% {
            opacity: 1;
        }
    }

    .close {
        grid-column: 3;
        animation: close 0.5s linear;
        opacity: 1;
    }

    @keyframes close {
        0% {
            max-width: 160%;
        }
    }

    header {
        grid-column: 2 / 5;
        grid-template-columns: repeat(3, 1fr);
    }

    header::after {
        grid-column: 3;
    }
    header > .nav {
        grid-column: 2;
    }

    main::after {
        grid-column: 3 /4;
    }

    .fullscreen::after {
        grid-column: 1 / 6;
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
