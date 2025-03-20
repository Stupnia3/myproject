<template>
    <div>
        <AboutSection data-aos="fade-up" />
        <ReviewsSection data-aos="fade-up" data-aos-delay="100" />
        <ThoughtsSection data-aos="fade-up" data-aos-delay="200" />
        <TestSection data-aos="fade-up" data-aos-delay="300" />
    </div>
</template>

<script>
import AboutSection from '../components/AboutSection.vue';
import ReviewsSection from '../components/ReviewsSection.vue';
import ThoughtsSection from '../components/ThoughtsSection.vue';
import TestSection from '../components/TestSection.vue';

export default {
    components: {
        AboutSection,
        ReviewsSection,
        ThoughtsSection,
        TestSection,
    },
};
</script>

