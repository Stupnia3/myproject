<template>
    <div class="event-details-page">
        <HeaderSection :showSlogan="true" />
        <div class="event-content">
            <h1>{{ event.title }}</h1>
            <div class="event-tags">
                <span v-for="tag in event.tags" :key="tag" class="tag">{{ tagLabel(tag) }}</span>
            </div>
            <img v-if="event.photo" :src="'/storage/' + event.photo" alt="Event Photo" class="event-photo" />
            <p class="event-description">{{ event.description }}</p>
            <div class="event-info">
                <p><strong>Дата начала:</strong> {{ formatDate(event.start_date) }}</p>
                <p v-if="event.end_date"><strong>Дата окончания:</strong> {{ formatDate(event.end_date) }}</p>
                <p><strong>Место:</strong> {{ event.location || 'Не указано' }}</p>
                <p><strong>Длительность:</strong> {{ event.duration ? `${event.duration} ч.` : 'Не указано' }}</p>
                <p><strong>Свободных мест:</strong> {{ event.available_seats }}</p>
                <p v-if="event.teachers.length"><strong>Преподаватели:</strong> {{ event.teachers.map(t => t.name).join(', ') }}</p>
                <div v-if="auth.user" class="register-button">
                    <button
                        v-if="!isRegistered"
                        class="btn primary"
                        @click="registerForEvent"
                        :disabled="event.available_seats <= 0 || reviewForm.processing"
                    >
                        Записаться
                    </button>
                    <button v-else class="btn primary disabled" disabled>
                        Вы уже записаны
                    </button>
                </div>
                <div v-else class="login-prompt">
                    <p><Link :href="route('login')" class="login-link">Войдите</Link>, чтобы записаться.</p>
                </div>
            </div>
            <div class="event-info">
                <h3>Практические части:</h3>
                <ul class="custom-list">
                    <li v-for="(part, index) in event.practical_parts" :key="index">{{ part }}</li>
                    <li v-if="!event.practical_parts || !event.practical_parts.length">Не указаны</li>
                </ul>
            </div>
            <div class="event-info">
                <h3>Методики:</h3>
                <ul class="custom-list">
                    <li v-for="(method, index) in event.methodologies" :key="index">{{ method }}</li>
                    <li v-if="!event.methodologies || !event.methodologies.length">Не указаны</li>
                </ul>
            </div>
            <div class="reviews-section">
                <h2>Отзывы</h2>
                <div v-if="auth.user" class="review-form">
                    <form @submit.prevent="submitReview">
                        <div class="input-group">
                            <textarea
                                v-model="reviewForm.text"
                                class="review-input"
                                placeholder="Ваш отзыв"
                                :class="{ 'error': errors.text || errors.message }"
                                maxlength="1000"
                            ></textarea>
                            <span v-if="errors.text || errors.message" class="error-text">{{ errors.text || errors.message }}</span>
                        </div>
                        <div class="input-group">
                            <label for="rating">Рейтинг:</label>
                            <select
                                v-model="reviewForm.rating"
                                id="rating"
                                class="rating-select"
                                :class="{ 'error': errors.rating }"
                            >
                                <option v-for="n in 5" :key="n" :value="n">{{ n }}</option>
                            </select>
                            <span v-if="errors.rating" class="error-text">{{ errors.rating }}</span>
                        </div>
                        <button type="submit" class="btn primary" :disabled="reviewForm.processing">
                            Отправить отзыв
                        </button>
                    </form>
                </div>
                <div v-else class="login-prompt">
                    <p>Чтобы оставить отзыв, <Link :href="route('login')" class="login-link">войдите</Link> или <Link :href="route('register')" class="login-link">зарегистрируйтесь</Link>.</p>
                </div>
                <div class="reviews-list">
                    <div v-for="review in reviews" :key="review.id" class="review-item">
                        <div class="review-user">
                            <img
                                :src="review.user && review.user.photo ? `/storage/${review.user.photo}` : '/storage/images/avatardefault.png'"
                                alt="User Avatar"
                                class="review-avatar"
                            />
                            <span class="review-user-name">{{ review.user ? review.user.name : 'Неизвестный автор' }}</span>
                            <span class="review-rating">Рейтинг: {{ review.rating }}/5</span>
                        </div>
                        <p class="review-content">{{ review.text }}</p>
                        <span class="review-date">{{ formatDate(review.created_at) }}</span>
                    </div>
                    <p v-if="!reviews.length">Пока нет отзывов.</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import HeaderSection from '@/Components/HeaderSection.vue';
import { Link, useForm } from '@inertiajs/vue3';
import axios from 'axios';

export default {
    components: { HeaderSection, Link },
    props: {
        event: Object,
        auth: Object,
        reviews: Array,
        errors: Object,
        isRegistered: Boolean,
    },
    setup(props) {
        const reviewForm = useForm({
            event_id: props.event.id,
            text: '',
            rating: 5,
        });

        const registerForm = useForm({});

        function submitReview() {
            reviewForm.post(route('reviews.store', { event: props.event.id }), {
                onSuccess: () => {
                    reviewForm.reset('text', 'rating');
                    axios.get(route('reviews.index', props.event.id)).then(response => {
                        props.reviews.splice(0, props.reviews.length, ...response.data);
                    });
                },
                preserveState: true,
            });
        }

        function registerForEvent() {
            registerForm.post(route('event.register', props.event.id), {
                onSuccess: () => {
                    // Обновляем страницу, чтобы отобразить статус регистрации
                    axios.get(route('event.details', props.event.id)).then(response => {
                        props.isRegistered = true;
                    });
                },
                preserveState: true,
            });
        }

        return { reviewForm, submitReview, registerForm, registerForEvent };
    },
    methods: {
        tagLabel(tag) {
            const tags = {
                'art-therapy': 'Арт-терапия',
                'master-class': 'Мастер-класс',
                'retreat': 'Ретрит',
            };
            return tags[tag] || tag;
        },
        formatDate(date) {
            return new Date(date).toLocaleDateString('ru-RU', {
                day: '2-digit',
                month: 'long',
                year: 'numeric',
            });
        },
    },
};
</script>

<style scoped>
.event-details-page {
    max-width: 1200px;
    margin: 0 auto;
    font-family: 'Montserrat Alternates', sans-serif;
    padding: 40px 20px;
}

.event-content {
    margin-top: 40px;
}

h1 {
    font-size: 36px;
    font-weight: 600;
    color: #1e40af;
    text-transform: uppercase;
    margin-bottom: 10px;
}

.event-tags {
    margin-bottom: 20px;
}

.tag {
    display: inline-block;
    background: #1e40af;
    color: #ffffff;
    padding: 5px 10px;
    border-radius: 15px;
    font-size: 14px;
    margin-right: 5px;
    margin-bottom: 5px;
}

.event-photo {
    max-width: 100%;
    border-radius: 8px;
    margin-bottom: 20px;
}

.event-description {
    font-size: 18px;
    color: #333;
    line-height: 1.6;
    margin-bottom: 30px;
}

.event-info {
    margin-bottom: 30px;
}

.event-info h3 {
    font-size: 24px;
    font-weight: 600;
    color: #1e40af;
    margin-bottom: 15px;
}

.event-info p {
    font-size: 16px;
    color: #333;
    margin-bottom: 10px;
}

.custom-list {
    list-style-type: none;
    padding-left: 20px;
}

.custom-list li {
    position: relative;
    padding-left: 15px;
    margin-bottom: 10px;
    font-size: 16px;
    color: #333;
    text-align: justify;
}

.custom-list li::before {
    content: "•";
    color: #1e40af;
    font-weight: bold;
    position: absolute;
    left: 0;
}

.register-button {
    margin-top: 20px;
}

.btn.primary {
    display: inline-block;
    background: #1e40af;
    color: #ffffff;
    padding: 10px 20px;
    border-radius: 25px;
    font-size: 16px;
    font-weight: 600;
    border: none;
    cursor: pointer;
    transition: background 0.3s ease;
}

.btn.primary:hover:not(.disabled) {
    background: #1e3a8a;
}

.btn.primary.disabled {
    background: #6b7280;
    cursor: not-allowed;
}

.login-prompt {
    margin-bottom: 20px;
}

.login-link {
    color: #1e40af;
    text-decoration: underline;
}

.login-link:hover {
    color: #1e3a8a;
}

.reviews-section {
    margin-top: 40px;
}

.reviews-section h2 {
    font-size: 24px;
    font-weight: 600;
    color: #1e40af;
    margin-bottom: 20px;
}

.review-form {
    margin-bottom: 30px;
}

.input-group {
    margin-bottom: 20px;
}

.review-input {
    width: 100%;
    height: 100px;
    padding: 10px;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    font-size: 16px;
    font-family: 'Montserrat Alternates', sans-serif;
    resize: vertical;
}

.review-input:focus {
    outline: none;
    border-color: #1e40af;
}

.review-input.error {
    border-color: #dc2626;
}

.rating-select {
    width: 100%;
    height: 50px;
    padding: 0 15px;
    border: 1px solid #d1d5db;
    border-radius: 25px;
    font-size: 16px;
    font-family: 'Montserrat Alternates', sans-serif;
    background: #ffffff;
}

.rating-select:focus {
    outline: none;
    border-color: #1e40af;
}

.rating-select.error {
    border-color: #dc2626;
}

.error-text {
    color: #dc2626;
    font-size: 14px;
    margin-top: 5px;
    display: block;
}

.reviews-list {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.review-item {
    background: #f8f8f8;
    padding: 15px;
    border-radius: 8px;
}

.review-user {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 10px;
}

.review-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
    border: 1px solid #1e40af;
}

.review-user-name {
    font-size: 16px;
    font-weight: 600;
    color: #1e40af;
}

.review-rating {
    font-size: 14px;
    color: #6b7280;
    margin-left: 10px;
}

.review-content {
    font-size: 16px;
    color: #333;
    margin-bottom: 10px;
}

.review-date {
    font-size: 14px;
    color: #6b7280;
}

@media (max-width: 768px) {
    h1 {
        font-size: 24px;
    }

    .event-description,
    .event-info p,
    .custom-list li,
    .review-content,
    .review-user-name {
        font-size: 14px;
    }

    .event-info h3,
    .reviews-section h2 {
        font-size: 20px;
    }

    .tag {
        font-size: 12px;
        padding: 4px 8px;
    }

    .review-input,
    .rating-select {
        font-size: 14px;
    }

    .btn.primary {
        font-size: 14px;
        padding: 8px 15px;
    }

    .review-avatar {
        width: 32px;
        height: 32px;
    }
}
</style>
