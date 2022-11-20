<template>
    <div class="main-page">
        <header>
            <h1>AO</h1>
            <nav class="nav">
                <a href="#" class="nav_link">Home</a>
                <a href="#" class="nav_link">Blog</a>
                <a href="#" class="nav_link">Materials</a>
                <a href="#" class="nav_link">Messanger</a>
            </nav>
        </header>
        <main>
            <section class="fullscreen">
                <div class="title-wrap">
                    <h1 class="title">MY HOMETASK</h1>
                    <p class="personal">personal website</p>
                </div>
                <img
                    class="primary-image"
                    src="../assets/images/main-image.jpg"
                    alt=""
                />
                <p class="greetings">
                    Lorem ipsum dolor sit amet consectetur adipisicing elit.
                    Ducimus nihil veniam quod vero? Ab praesentium obcaecati
                    molestiae? Recusandae ipsa rem.
                </p>
                <button class="btn">Contact</button>
                <div class="social-link">
                    <a href=""><img src="../assets/images/vk.png" alt="" /></a>
                </div>
                <img class="book" src="../assets/images/book.svg" alt="" />
            </section>
            <section class="next">
                Lorem ipsum dolor sit amet, consectetur adipisicing elit.
                Adipisci earum non possimus mollitia obcaecati maiores, beatae
                voluptatem? Porro, debitis possimus in saepe maxime qui natus,
                incidunt deleniti dolorem nemo culpa.
            </section>
        </main>
    </div>
</template>

<script>
export default {
    mounted() {
        console.log("Home Component mounted.");
    },
};
</script>

<style scoped>
.next {
    width: 100%;
    height: 100%;
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
}

.nav_link:hover,
.nav_link:focus {
    color: var(--clr-accent);
}
.social-link:hover,
.social-link:focus {
    opacity: 0.5;
}

/* @media (min-width: 930px) {
  .fullscreen::before {
    content: url("../assets/images/book.svg");
    position: absolute;
    opacity: 0.9;
    z-index: -100;
    left: 0;
    top: 0;
    grid-column: 1 / 3;
    grid-row: 1 / 3;
  }
} */

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
        position: absolute;
        /* top: 10%; */
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

    .next {
        grid-column: 1/6;
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
        grid-column: 3 / -1;
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
        width: 110%;
        height: 1px;
        background-color: var(--clr-accent);
        grid-column: 1 / 5;
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
        grid-template-columns:
            minmax(1em, 1fr)
            repeat(3, minmax(15rem, 35rem))
            minmax(1em, 1fr);
    }

    header {
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
        grid-column: 1 / 4;
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
</style>
