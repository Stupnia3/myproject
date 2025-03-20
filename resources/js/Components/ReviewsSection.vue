<template>
    <section class="reviews-section">
        <h2 class="section-title">отзывы</h2>
        <div class="review-carousel">
            <div class="review-card">
                <img src="/storage/images/review1.jpg" alt="Review 1" class="review-image" />
                <div class="review-content">
                    <h3 class="review-author">Алина Складчикова</h3>
                    <p class="review-text">Текст отзыва, который оставил человек, проходивший мастер-класс. Это было невероятное путешествие в мир эмоций!</p>
                </div>
            </div>
        </div>
        <div class="carousel-controls">
            <button class="arrow arrow-left" @click="prevReview">&lt;</button>
            <button class="arrow arrow-right" @click="nextReview">&gt;</button>
        </div>
    </section>
</template>

<script>
export default {
    data() {
        return {
            currentReview: 0,
            reviews: [
                { image: '/storage/images/review1.jpg', author: 'Алина Складчикова', text: 'Текст отзыва, который оставил человек, проходивший мастер-класс. Это было невероятное путешествие в мир эмоций!' },
                { image: '/storage/images/review2.jpg', author: 'Иван Петров', text: 'Арт-терапия помогла мне справиться со стрессом. Спасибо за тёплую атмосферу!' },
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
    background-color: var(--black-haze);
    text-align: center;
}

.section-title {
    font-size: var(--font-size-xl);
    color: var(--navy-blue);
    text-transform: uppercase;
    margin-bottom: 20px;
}

.review-carousel {
    display: flex;
    justify-content: center;
    gap: 20px;
}

.review-card {
    background-color: var(--white);
    border-radius: 25px;
    padding: 20px;
    width: 500px;
    display: flex;
    align-items: center;
    gap: 20px;
}

.review-image {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    object-fit: cover;
}

.review-content {
    text-align: left;
}

.review-author {
    font-size: var(--font-size-l);
    color: var(--astronaut);
    margin-bottom: 10px;
}

.review-text {
    font-size: var(--font-size-m);
    color: #333;
}

.carousel-controls {
    margin-top: 20px;
}

.arrow {
    background: none;
    border: none;
    font-size: 24px;
    cursor: pointer;
    margin: 0 10px;
}

@media (max-width: 768px) {
    .review-card {
        flex-direction: column;
        width: 90%;
    }
}
</style>
