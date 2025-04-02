<!-- resources/js/components/ReviewsSection.vue -->
<template>
    <section class="reviews-section">
        <h2 class="section-title">отзывы</h2>
        <div class="review-carousel">
            <div v-if="reviews && reviews.length > 0" class="review-card">
                <img :src="reviews[currentReview].image" :alt="reviews[currentReview].author" class="review-image" />
                <div class="review-content">
                    <h3 class="review-author">{{ reviews[currentReview].author }}</h3>
                    <div class="star-rating">
                        <span
                            v-for="star in 5"
                            :key="star"
                            class="star"
                            :class="{ filled: star <= reviews[currentReview].rating }"
                        >
                            ★
                        </span>
                    </div>
                    <p class="review-text">{{ reviews[currentReview].text }}</p>
                </div>
            </div>
            <div v-else class="no-reviews">
                <p>Пока нет отзывов. Будьте первым!</p>
            </div>
        </div>
        <div v-if="reviews && reviews.length > 1" class="carousel-controls">
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
    props: {
        reviews: Array,
    },
    data() {
        return {
            currentReview: 0,
            autoSlideInterval: null,
        };
    },
    methods: {
        nextReview() {
            if (this.reviews && this.reviews.length > 0) {
                this.currentReview = (this.currentReview + 1) % this.reviews.length;
            }
        },
        prevReview() {
            if (this.reviews && this.reviews.length > 0) {
                this.currentReview = (this.currentReview - 1 + this.reviews.length) % this.reviews.length;
            }
        },
        startAutoSlide() {
            if (this.reviews && this.reviews.length > 1) {
                this.autoSlideInterval = setInterval(() => {
                    this.nextReview();
                }, 5000);
            }
        },
        stopAutoSlide() {
            if (this.autoSlideInterval) {
                clearInterval(this.autoSlideInterval);
                this.autoSlideInterval = null;
            }
        },
    },
    mounted() {
        this.startAutoSlide();
    },
    beforeUnmount() {
        this.stopAutoSlide();
    },
};
</script>

<style scoped>
.reviews-section {
    padding: 60px 20px;
    background-color: #f5f5f5;
    text-align: center;
}

.section-title {
    font-size: 24px;
    color: #1e40af;
    text-transform: uppercase;
    margin-bottom: 40px;
}

.review-carousel {
    display: flex;
    justify-content: center;
    gap: 20px;
}

.review-card {
    background-color: #fff;
    border-radius: 25px;
    padding: 20px;
    width: 800px;
    display: flex;
    align-items: center;
    gap: 20px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    transition: opacity 0.3s ease;
}

.review-image {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    object-fit: cover;
}

.review-content {
    text-align: left;
    flex: 1;
}

.review-author {
    font-size: 18px;
    color: #1e40af;
    margin-bottom: 5px;
}

.star-rating {
    display: flex;
    gap: 5px;
    font-size: 20px;
    margin-bottom: 10px;
}

.star {
    color: #ccc;
}

.star.filled {
    color: #f1c40f;
}

.review-text {
    font-size: 16px;
    color: #333;
    line-height: 1.5;
}

.no-reviews {
    font-size: 16px;
    color: #333;
}

.carousel-controls {
    margin-top: 20px;
    display: flex;
    justify-content: center;
    gap: 20px;
}

.arrow {
    background: none;
    border: none;
    cursor: pointer;
    padding: 10px;
    transition: transform 0.3s ease;
}

.arrow:hover {
    transform: scale(1.2);
}

.debug-reviews {
    background: #f0f0f0;
    padding: 10px;
    margin-bottom: 20px;
    border-radius: 5px;
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
