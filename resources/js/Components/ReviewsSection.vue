<template>
    <section class="reviews-section">
        <h2 class="section-title">отзывы</h2>
        <div class="review-carousel">
            <div class="review-card">
                <img :src="reviews[currentReview].image" :alt="reviews[currentReview].author" class="review-image" />
                <div class="review-content">
                    <h3 class="review-author">{{ reviews[currentReview].author }}</h3>
                    <p class="review-text">{{ reviews[currentReview].text }}</p>
                </div>
            </div>
        </div>
        <div class="carousel-controls">
            <button class="arrow arrow-left" @click="prevReview">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M15 18L9 12L15 6" stroke="#1e40af" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>
            <button class="arrow arrow-right" @click="nextReview">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M9 18L15 12L9 6" stroke="#1e40af" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>
        </div>
    </section>
</template>

<script>
export default {
    data() {
        return {
            currentReview: 0,
            reviews: [
                {
                    image: '/storage/images/review1.jpg',
                    author: 'Алина Складчикова',
                    text: 'Текст отзыва, который оставил человек, проходивший мастер-класс. Это было невероятное путешествие в мир эмоций!',
                },
                {
                    image: '/storage/images/avatardefault.png',
                    author: 'Иван Петров',
                    text: 'Арт-терапия помогла мне справиться со стрессом. Спасибо за тёплую атмосферу!',
                },
                {
                    image: '/storage/images/avatardefault.png',
                    author: 'Мария Иванова',
                    text: 'Мастер-класс был потрясающим! Я открыла для себя новые способы самовыражения.',
                },
            ],
        };
    },
    methods: {
        nextReview() {
            this.currentReview = (this.currentReview + 1) % this.reviews.length;
        },
        prevReview() {
            this.currentReview = (this.currentReview - 1 + this.reviews.length) % this.reviews.length;
        },
    },
};
</script>

<style scoped>
.reviews-section {
    padding: 60px 20px;
    background-color: #f5f5f5; /* Светлый фон, как в других секциях */
    text-align: center;
}

.section-title {
    font-size: 24px; /* Размер шрифта заголовка */
    color: #1e40af; /* Синий цвет */
    text-transform: uppercase;
    margin-bottom: 40px;
}

.review-carousel {
    display: flex;
    justify-content: center;
    gap: 20px;
}

.review-card {
    background-color: #fff; /* Белый фон карточки */
    border-radius: 25px;
    padding: 20px;
    width: 800px; /* Ширина карточки, как на изображении */
    display: flex;
    align-items: center;
    gap: 20px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Лёгкая тень */
}

.review-image {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    object-fit: cover;
}

.review-content {
    text-align: left;
    flex: 1; /* Занимает оставшееся пространство */
}

.review-author {
    font-size: 18px; /* Размер шрифта имени */
    color: #1e40af; /* Синий цвет */
    margin-bottom: 10px;
}

.review-text {
    font-size: 16px; /* Размер шрифта текста */
    color: #333;
    line-height: 1.5; /* Увеличенный межстрочный интервал */
}

.carousel-controls {
    margin-top: 20px;
    display: flex;
    justify-content: center;
    gap: 20px; /* Отступ между стрелками */
}

.arrow {
    background: none;
    border: none;
    cursor: pointer;
    padding: 10px;
    transition: transform 0.3s ease;
}

.arrow:hover {
    transform: scale(1.2); /* Увеличение при наведении */
}

@media (max-width: 768px) {
    .review-card {
        flex-direction: column;
        width: 90%;
        text-align: center;
    }

    .review-content {
        text-align: center;
    }

    .review-image {
        margin: 0 auto;
    }
}
</style>
