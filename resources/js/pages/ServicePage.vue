<template>
    <section class="services services__grid">
        <h1>Виды <span class="accent-color">подготовок</span></h1>
        <div class="services__cards">
            <div class="services__cards_card">
                <div class="services__cards_card_content">
                    <font-awesome-icon
                        :icon="['fas', 'graduation-cap']"
                        style="color: #343b43"
                    />
                    <ul class="services__list">
                        <h5>Экзамены</h5>
                        <li>ОГЭ</li>
                        <li>ЕГЭ</li>
                        <li>ВПР</li>
                        <li>Кэмбриджский экзамен</li>
                    </ul>
                </div>
            </div>
            <div class="services__cards_card">
                <div class="services__cards_card_content">
                    <font-awesome-icon
                        :icon="['fas', 'chalkboard-user']"
                        style="color: #343b43"
                    />
                    <ul class="services__list">
                        <h5>Формат обучения</h5>
                        <p>
                            Оффлайн и онлайн форматы обучения. Индивидуальные
                            занятия и занятия в группе (до 2-х обучаемых)
                        </p>
                    </ul>
                </div>
            </div>
            <div class="services__cards_card">
                <div class="services__cards_card_content">
                    <font-awesome-icon
                        :icon="['fas', 'briefcase']"
                        style="color: #343b43"
                    />
                    <ul class="services__list">
                        <h5>Деловой английский язык</h5>
                        <p>
                            Изучение общей используемой бизнес-лексики. Общение
                            по телефону и по мессенджерам, организация
                            переговоров, презентаций и интервью, прохождение
                            собеседования, подготовка резюме.
                        </p>
                    </ul>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
export default {};
</script>

<style scoped>
.services__grid {
    grid-column: 1 / -1;
    position: relative;
    width: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 3rem 1em;
    gap: 3rem;
}

.services__cards {
    display: flex;
    max-width: 1280px;
    width: 100%;
    height: 100%;
    justify-content: space-between;
    align-items: center;
    gap: 40px;
}

.services__cards_card {
    position: relative;
    background: #fff;
    flex: 1 0 auto;
    height: auto;
    border-radius: 20px;
    border-top: 5px solid #ff7600;
    border-bottom: 5px solid #ff7600;
}

.services__cards_card::before {
    content: "";
    display: block;
    padding-top: 100%;
}

.services__cards_card_content {
    position: absolute;
    left: 0;
    bottom: 0;
    top: 0;
    right: 0;
    display: flex;
    flex-direction: column;
    padding: 3rem;
    gap: clamp(1rem, 10%, 3rem);
}

.services__cards_card_content > svg {
    /* width: 100px;
    height: 90px; */
    width: clamp(60px, 10%, 100px);
    height: clamp(60px, 10%, 100px);
    align-self: center;
    color: rgb(52, 59, 67);
}

h5 {
    text-align: center;
    text-decoration: underline;
}

.services__list {
    color: #000;
    margin: 0 auto;
    padding: 0;
}

.services__list > li {
    /* font-size: calc(100% + 10px); */
}

@media (max-width: 1185px) {
    .services__cards_card {
        height: 500px;
    }
}

@media (max-width: 970px) {
    .services__cards_card {
        height: 600px;
    }
}

@media (max-width: 800px) {
    .services__cards {
        flex-direction: column;
    }
    .services__cards_card {
        width: calc(100% - 1rem);
        height: 450px;
    }
    .services__grid {
        padding: 3rem 0;
    }
}
</style>
