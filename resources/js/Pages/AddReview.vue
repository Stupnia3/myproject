<!-- resources/js/Pages/AddReview.vue -->
<template>
    <div class="add-review-page">
        <h2 class="page-title">Оставить отзыв</h2>
        <form @submit.prevent="submitReview" class="review-form">
            <div class="form-group">
                <label for="rating">Оценка:</label>
                <div class="star-rating">
                    <span
                        v-for="star in 5"
                        :key="star"
                        class="star"
                        :class="{ filled: star <= form.rating }"
                        @click="form.rating = star"
                    >
                        ★
                    </span>
                </div>
            </div>
            <div class="form-group">
                <label for="text">Ваш отзыв:</label>
                <textarea
                    id="text"
                    v-model="form.text"
                    rows="5"
                    placeholder="Напишите ваш отзыв..."
                    required
                ></textarea>
            </div>
            <button type="submit" class="submit-btn">Отправить отзыв</button>
        </form>
    </div>
</template>

<script>
import { useForm } from '@inertiajs/vue3';
import eventBus from '../eventBus';

export default {
    props: {
        auth: Object,
        csrf_token: String,
    },
    data() {
        return {
            debugAuth: true, // Включаем отладочный вывод
        };
    },
    setup(props) {
        const form = useForm({
            rating: 5,
            text: '',
        });

        const submitReview = () => {
            if (!props.auth.user) {
                eventBus.emit('openModal', {
                    title: 'Требуется авторизация',
                    message: 'Пожалуйста, войдите в систему, чтобы оставить отзыв.',
                    buttons: [
                        { label: 'Войти', class: 'register-btn', action: () => window.location.href = '/login' },
                        { label: 'Отмена', class: 'cancel-btn', action: () => eventBus.emit('closeModal') },
                    ],
                });
                return;
            }

            form.post('/reviews', {
                onSuccess: () => {
                    eventBus.emit('openModal', {
                        title: 'Успех',
                        message: 'Ваш отзыв успешно отправлен!',
                        buttons: [
                            { label: '', class: 'close-btn', action: () => eventBus.emit('closeModal') },
                        ],
                    });
                    form.reset();
                },
                onError: (errors) => {
                    eventBus.emit('openModal', {
                        title: 'Ошибка',
                        message: errors.text || 'Произошла ошибка при отправке отзыва.',
                        buttons: [
                            { label: 'Закрыть', class: 'close-btn', action: () => eventBus.emit('closeModal') },
                        ],
                    });
                },
            });
        };

        return { form, submitReview };
    },
};
</script>

<style scoped>
.add-review-page {
    max-width: 800px;
    margin: 0 auto;
    padding: 60px 20px;
    font-family: 'Montserrat Alternates', sans-serif;
}

.page-title {
    font-size: 24px;
    color: #1e40af;
    text-transform: uppercase;
    margin-bottom: 40px;
    text-align: center;
}

.review-form {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.form-group label {
    font-size: 18px;
    color: #333;
}

.star-rating {
    display: flex;
    gap: 5px;
    font-size: 24px;
    cursor: pointer;
}

.star {
    color: #ccc;
    transition: color 0.3s ease;
}

.star.filled {
    color: #f1c40f;
}

textarea {
    width: 100%;
    padding: 10px;
    border: 2px solid #1e40af;
    border-radius: 10px;
    font-size: 16px;
    resize: vertical;
}

.submit-btn {
    background: #1e40af;
    color: #fff;
    padding: 10px 20px;
    border: none;
    border-radius: 25px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.3s ease;
}

.submit-btn:hover {
    background: #1e3a8a;
}

.debug-auth {
    background: #f0f0f0;
    padding: 10px;
    margin-bottom: 20px;
    border-radius: 5px;
}

@media (max-width: 768px) {
    .add-review-page {
        padding: 40px 10px;
    }

    .page-title {
        font-size: 20px;
    }
}
</style>
