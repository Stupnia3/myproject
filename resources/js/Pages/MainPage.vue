<template>
    <div>
        <AboutSection data-aos="fade-up" />
        <ReviewsSection :reviews="reviews" data-aos="fade-up" data-aos-delay="100" />
        <ThoughtsSection data-aos="fade-up" data-aos-delay="200" />
        <TestSection data-aos="fade-up" data-aos-delay="300" />
    </div>
</template>

<script>
import AboutSection from '../Components/AboutSection.vue';
import ReviewsSection from '../Components/ReviewsSection.vue';
import ThoughtsSection from '../Components/ThoughtsSection.vue';
import TestSection from '../Components/TestSection.vue';

export default {
    components: {
        AboutSection,
        ReviewsSection,
        ThoughtsSection,
        TestSection,
    },
    props: {
        reviews: {
            type: Array,
            default: () => [],
        },
        title: {
            type: String,
            default: 'Главная',
        },
    },
};
</script>
