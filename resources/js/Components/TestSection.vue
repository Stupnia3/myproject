<template>
    <section class="test-section">
        <h2 class="section-title">тест</h2>
        <p class="section-subtitle">Какое искусство поможет вам выразить свои эмоции?</p>
        <div class="test-questions">
            <div class="question-block">
                <p class="question">Вопрос 1: Как вы себя чувствуете в данный момент?</p>
                <div class="options">
                    <label>
                        <input type="radio" name="q1" v-model="answers.q1" value="calm" />
                        Спокойно и уравновешенно
                    </label>
                    <label>
                        <input type="radio" name="q1" v-model="answers.q1" value="inspired" />
                        Немного тревожно
                    </label>
                    <label>
                        <input type="radio" name="q1" v-model="answers.q1" value="tired" />
                        Усталость и подавленность
                    </label>
                    <label>
                        <input type="radio" name="q1" v-model="answers.q1" value="anxious" />
                        Вдохновлено и энергично
                    </label>
                </div>
            </div>
            <div class="question-block">
                <p class="question">Вопрос 2: Как вы обычно справляетесь со стрессом?</p>
                <div class="options">
                    <label>
                        <input type="radio" name="q2" v-model="answers.q2" value="active" />
                        Занимаюсь спортом или физической активностью
                    </label>
                    <label>
                        <input type="radio" name="q2" v-model="answers.q2" value="friends" />
                        Общаюсь с друзьями или близкими
                    </label>
                    <label>
                        <input type="radio" name="q2" v-model="answers.q2" value="diary" />
                        Общаюсь с дневником или пишу заметки
                    </label>
                    <label>
                        <input type="radio" name="q2" v-model="answers.q2" value="better" />
                        Предпочитаю уединиться и разобраться в своих чувствах
                    </label>
                </div>
            </div>
            <div class="question-block">
                <p class="question">Вопрос 3: Какой вид искусства вам ближе?</p>
                <div class="options">
                    <label>
                        <input type="radio" name="q3" v-model="answers.q3" value="music" />
                        Музыка и песни
                    </label>
                    <label>
                        <input type="radio" name="q3" v-model="answers.q3" value="dance" />
                        Танцы и движение
                    </label>
                    <label>
                        <input type="radio" name="q3" v-model="answers.q3" value="literature" />
                        Литература и поэзия
                    </label>
                    <label>
                        <input type="radio" name="q3" v-model="answers.q3" value="visual" />
                        Декоративно-прикладное искусство
                    </label>
                </div>
            </div>
            <div class="question-block">
                <p class="question">Вопрос 4: Что для вас важнее в арт-терапии?</p>
                <div class="options">
                    <label>
                        <input type="radio" name="q4" v-model="answers.q4" value="expression" />
                        Возможность самовыражения
                    </label>
                    <label>
                        <input type="radio" name="q4" v-model="answers.q4" value="social" />
                        Социальное взаимодействие
                    </label>
                    <label>
                        <input type="radio" name="q4" v-model="answers.q4" value="understanding" />
                        Исцеление и понимание себя
                    </label>
                    <label>
                        <input type="radio" name="q4" v-model="answers.q4" value="creativity" />
                        Развитие креативности
                    </label>
                </div>
            </div>
            <button class="submit-button" @click="submitTest">к результатам</button>
        </div>
        <div class="results-section" v-if="result">
            <p class="results-text">{{ result }}</p>
        </div>
    </section>
</template>

<script>
export default {
    data() {
        return {
            answers: {
                q1: null, // Настроение
                q2: null, // Способ справляться со стрессом
                q3: null, // Предпочитаемый вид искусства
                q4: null, // Цель арт-терапии
            },
            result: null,
        };
    },
    methods: {
        submitTest() {
            // Проверяем, что все вопросы заполнены
            if (!this.answers.q1 || !this.answers.q2 || !this.answers.q3 || !this.answers.q4) {
                alert('Пожалуйста, ответьте на все вопросы!');
                return;
            }

            let resultText = 'Результаты: ';
            const { q1, q2, q3, q4 } = this.answers;

            // Логика определения результата
            if (q3 === 'music') {
                // Рекомендация для музыки
                if (q1 === 'calm' || q1 === 'inspired') {
                    resultText += 'Вам подойдет музыкальная терапия. Она поможет вам выразить свои эмоции через мелодии и ритмы, особенно если вы чувствуете себя спокойно или вдохновленно. ';
                } else if (q1 === 'tired' || q1 === 'inspired') {
                    resultText += 'Музыкальная терапия поможет вам расслабиться и снять стресс через успокаивающие мелодии, особенно если вы чувствуете усталость или тревогу. ';
                }
                if (q4 === 'expression' || q4 === 'creativity') {
                    resultText += 'Вы сможете использовать музыку для самовыражения и развития креативности.';
                } else if (q4 === 'understanding') {
                    resultText += 'Музыка поможет вам глубже понять свои эмоции.';
                } else if (q4 === 'social') {
                    resultText += 'Попробуйте групповые музыкальные занятия для взаимодействия с другими.';
                }
            } else if (q3 === 'dance') {
                // Рекомендация для танцев
                if (q1 === 'anxious' || q1 === 'calm') {
                    resultText += 'Танцевальная терапия идеально подходит для вас. Она поможет вам выразить энергию и снять напряжение через движение. ';
                } else if (q1 === 'tired' || q1 === 'inspired') {
                    resultText += 'Танцы помогут вам зарядиться энергией и снять усталость через активное движение. ';
                }
                if (q2 === 'active') {
                    resultText += 'Ваша склонность к физической активности делает танцы особенно подходящими. ';
                }
                if (q4 === 'social') {
                    resultText += 'Групповые танцевальные занятия помогут вам наладить социальные связи.';
                } else if (q4 === 'expression') {
                    resultText += 'Танцы станут отличным способом самовыражения.';
                }
            } else if (q3 === 'literature') {
                // Рекомендация для литературы
                if (q1 === 'tired' || q1 === 'inspired') {
                    resultText += 'Литература и поэзия помогут вам глубже понять свои чувства и найти утешение. ';
                } else if (q1 === 'calm' || q1 === 'anxious') {
                    resultText += 'Погружение в литературу позволит вам отвлечься и снять тревогу. ';
                }
                if (q2 === 'diary' || q2 === 'better') {
                    resultText += 'Ваша склонность к самоанализу и ведению записей делает литературную терапию идеальной для вас. ';
                }
                if (q4 === 'understanding') {
                    resultText += 'Писательство поможет вам лучше понять свои эмоции.';
                }
            } else if (q3 === 'visual') {
                // Рекомендация для декоративно-прикладного искусства
                if (q1 === 'inspired' || q1 === 'tired') {
                    resultText += 'Декоративно-прикладное искусство позволит вам расслабиться и сосредоточиться на творчестве. ';
                } else if (q1 === 'calm' || q1 === 'anxious') {
                    resultText += 'Работа с руками поможет вам снять тревогу и обрести внутреннее спокойствие. ';
                }
                if (q2 === 'better') {
                    resultText += 'Ваша склонность к уединению делает этот вид искусства особенно подходящим. ';
                }
                if (q4 === 'creativity') {
                    resultText += 'Этот вид искусства поможет вам развить креативность.';
                }
            } else {
                resultText += 'Попробуйте разные виды искусства, чтобы найти то, что лучше всего подходит для вас! ';
            }

            this.result = resultText;
            console.log(this.answers);
        },
    },
};
</script>

<style scoped>
.test-section {
    padding: 60px 20px;
    background-color: #f5f5f5;
    text-align: center;
}

.section-title {
    font-size: 24px;
    color: #1e40af;
    text-transform: uppercase;
    margin-bottom: 10px;
}

.section-subtitle {
    font-size: 20px;
    color: #333;
    margin-bottom: 40px;
}

.test-questions {
    max-width: 1577px;
    margin: 0 auto;
}

.question-block {
    background-color: transparent;
    border-radius: 25px;
    padding: 20px 0;
    margin-bottom: 20px;
    text-align: left;
}

.question {
    font-size: 18px;
    color: #333;
    margin-bottom: 20px;
}

.options {
    display: flex;
    flex-direction: row;
    flex-wrap: wrap;
    gap: 20px;
    justify-content: space-between;
}

.options label {
    display: flex;
    align-items: center;
    font-size: 16px;
    color: #333;
    flex: 1 1 20%;
    min-width: 200px;
}

.options input[type="radio"] {
    appearance: none;
    width: 20px;
    height: 20px;
    border: 2px solid #1e40af;
    border-radius: 50%;
    margin-right: 10px;
    position: relative;
    cursor: pointer;
}

.options input[type="radio"]:checked {
    background-color: #1e40af;
    border-color: #1e40af;
}

.options input[type="radio"]:checked::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 10px;
    height: 10px;
    background-color: #fff;
    border-radius: 50%;
}

.submit-button {
    background-color: transparent;
    border: 2px solid #1e40af;
    border-radius: 25px;
    padding: 10px 40px;
    font-size: 16px;
    color: #333;
    cursor: pointer;
    transition: all 0.3s ease;
    text-transform: uppercase;
}

.submit-button:hover {
    background-color: #1e40af;
    color: #fff;
}

.results-section {
    width: 100%;
    max-width: 1577px;
    margin-top: 40px;
    background-color: #1e40af;
    color: #fff;
    padding: 20px;
    border-radius: 25px;
    text-align: left;
}

.results-text {
    font-size: 16px;
}

@media (max-width: 768px) {
    .options {
        flex-direction: column;
        gap: 15px;
    }

    .options label {
        flex: 1 1 100%;
        min-width: auto;
    }
}
</style>
