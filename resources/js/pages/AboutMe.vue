<template>
    <div class="about-me">
        <div class="about-me__container">
            <div class="person">
                <div class="circle"></div>
                <img
                    class="person__img"
                    src="../../assets/images/teacher.png"
                    alt=""
                />
            </div>
            <div class="about-me__content">
                <ul class="about-me-content-text">
                    <li>
                        Мои ученики успешно сдают международные экзамены:
                        <span>YLE</span>, <span>KET</span>, <span>PET</span>,
                        <span>FCE</span>
                    </li>
                    <li>
                        Успешно проходят школьную аттестацию: <span>ВПР</span>,
                        <span>ОГЭ</span>, <span>ЕГЭ</span>.
                    </li>

                    <li>
                        Имею опыт преподавания по
                        <a
                            target="_blank"
                            href="https://ru.wikipedia.org/wiki/%D0%A3%D1%87%D0%B5%D0%B1%D0%BD%D0%BE-%D0%BC%D0%B5%D1%82%D0%BE%D0%B4%D0%B8%D1%87%D0%B5%D1%81%D0%BA%D0%B8%D0%B9_%D0%BA%D0%BE%D0%BC%D0%BF%D0%BB%D0%B5%D0%BA%D1%81"
                            >УМК</a
                        >: <span>Millie</span>, <span>Spotlight</span>,
                        <span>Stars</span>, <span>Family and Friends</span>,
                        <span>Solution</span>, <span>New English File</span>,
                        <span>Intelligent Business</span>,
                        <span>Business Result</span>.
                    </li>
                    <li>
                        Имею опыт преподавания ученикам из других стран, детям с
                        ОВЗ (дети с нарушением зрения).
                    </li>
                    <li>
                        Веду индивидуальные и групповые занятия через Skype,
                        Discord, Zoom.
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>

<script>
export default {};
</script>

<style scoped>
.about-me {
    width: 100%;
    grid-column: 1 / -1;
    background-color: var(--clr-bg-light);
}

.about-me__container {
    max-width: 1280px;
    display: flex;
    margin: auto;
    padding: 0 1rem 50px 1rem;
}

.person {
    position: relative;
    background-color: var(--clr-bg-light);
    border-radius: 0 0 100vw 100vw;
    overflow: hidden;
    aspect-ratio: 1 / 1.25;
    inset: auto 0 0;
    margin-inline: auto;
}

.circle {
    width: 100%;
    aspect-ratio: 1 / 1;
    inset: auto 0 0;
    margin-inline: auto;
    position: absolute;
    background-color: #ff760066;
    border-radius: 50%;
}

.person__img {
    display: block;
    max-width: 100%;
    position: relative;
    z-index: 2;
    transform: scale(1.1);
    top: 50px;
}

.about-me__content {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 60%;
    padding-top: 50px;
}

.about-me-content-text {
    display: flex;
    flex-direction: column;
    gap: 5px;
    margin: 0;
}

.about-me-content-text > li > span {
    color: #ffbe85;
}

.about-me-content-text > li > a {
    color: rgb(139, 139, 185);
}

@media (max-width: 730px) {
    .about-me__container {
        flex-direction: column;
    }
    .about-me__content {
        width: 100%;
    }
}
</style>
