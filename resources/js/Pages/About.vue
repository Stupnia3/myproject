<template>
    <div class="about-page">
        <!-- Секция "С текстом или с фото" -->
        <HeaderSection data-aos="fade-up" data-aos-delay="100" />

        <!-- Секция "О нас" -->
        <AboutSection data-aos="fade-up" data-aos-delay="200" />

        <!-- Секция "Отзывы" -->
        <ReviewsSection data-aos="fade-up" data-aos-delay="300" />

        <!-- Секция "Интересные мысли" -->
        <ThoughtsSection data-aos="fade-up" data-aos-delay="400" />

        <!-- Секция "Тест" -->
        <TestSection data-aos="fade-up" data-aos-delay="500" />
    </div>
</template>

<script>
import { Link } from '@inertiajs/vue3';
import AboutSection from '../components/AboutSection.vue';
import ReviewsSection from '../components/ReviewsSection.vue';
import ThoughtsSection from '../components/ThoughtsSection.vue';
import TestSection from '../components/TestSection.vue';
import HeaderSection from "@/Components/HeaderSection.vue";

export default {
    components: {HeaderSection, Link, AboutSection, ReviewsSection, ThoughtsSection, TestSection },
    props: {
        title: String, // Принимаем title из маршрута
    },
};
</script>

<style scoped>
.about-page {
    max-width: 1577px; /* Установим максимальную ширину, как в вашем проекте */
    margin: 0 auto;
    font-family: 'Montserrat Alternates', sans-serif;
}

.page-title {
    font-size: 48px;
    color: #263b6a; /* --astronaut */
    text-align: center;
    text-transform: uppercase;
    margin-bottom: 40px;
}

@media (max-width: 768px) {
    .about-page {
        padding: 40px 10px;
    }

    .page-title {
        font-size: 32px;
    }
}
</style>
