<template>
    <section class="test-section">
        <h2 class="section-title">тест</h2>
        <p class="section-subtitle">Какое искусство поможет вам выразить свои эмоции?</p>
        <div class="test-questions">
            <div class="question-block">
                <p class="question">Вопрос 1: Как вы себя чувствуете в данный момент?</p>
                <div class="options">
                    <label><input type="radio" name="q1" v-model="answers.q1" value="calm" /> Спокойно и уравновешенно</label>
                    <label><input type="radio" name="q1" v-model="answers.q1" value="inspired" /> Вдохновлено и энергично</label>
                    <label><input type="radio" name="q1" v-model="answers.q1" value="tired" /> Усталость и подавленность</label>
                    <label><input type="radio" name="q1" v-model="answers.q1" value="anxious" /> Немного тревожно</label>
                </div>
            </div>
            <button class="submit-button" @click="submitTest">к результатам</button>
        </div>
    </section>
</template>

<script>
export default {
    data() {
        return {
            answers: {
                q1: null,
                q2: null,
                // Добавить другие вопросы
            }
        };
    },
    methods: {
        submitTest() {
            console.log(this.answers);
            // Здесь можно добавить логику для перехода к результатам
        },
    },
};
</script>

<style scoped>
.test-section {
    padding: 60px 20px;
    background-color: var(--black-haze);
    text-align: center;
}

.section-title {
    font-size: var(--font-size-xl);
    color: var(--navy-blue);
    text-transform: uppercase;
    margin-bottom: 20px;
}

.section-subtitle {
    font-size: var(--font-size-m);
    color: #333;
    margin-bottom: 40px;
}

.test-questions {
    max-width: 1200px;
    margin: 0 auto;
}

.question-block {
    background-color: var(--white);
    border-radius: 25px;
    padding: 20px;
    margin-bottom: 20px;
    text-align: left;
}

.question {
    font-size: var(--font-size-l);
    color: var(--astronaut);
    margin-bottom: 20px;
}

.options {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.options label {
    display: flex;
    align-items: center;
    font-size: var(--font-size-m);
    color: #333;
}

.options input {
    margin-right: 10px;
}

.submit-button {
    background-color: transparent;
    border: 1.5px solid var(--navy-blue);
    border-radius: 25px;
    padding: 10px 40px;
    font-size: var(--font-size-m);
    color: #333;
    cursor: pointer;
    transition: all 0.3s ease;
}

.submit-button:hover {
    background-color: var(--navy-blue);
    color: var(--white);
}
</style>
