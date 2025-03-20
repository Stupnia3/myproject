<!-- resources/js/Pages/EventsPage.vue -->
<template>
    <div class="events-page">
        <HeaderSection data-aos="fade-up" data-aos-delay="100" />
        <EventSection v-for="event in events" :key="event.id" :event="event" :auth="auth" data-aos="fade-up" :data-aos-delay="(event.id * 100) + 100" />
    </div>
</template>

<script>
import EventSection from '../components/EventSection.vue';
import HeaderSection from '@/Components/HeaderSection.vue';
import { Link } from '@inertiajs/vue3';

export default {
    components: {
        EventSection,
        HeaderSection,
        Link,
    },
    props: {
        events: Array,
        auth: Object,
    },
};
</script>

<style scoped>
.events-page {
    max-width: 1577px;
    margin: 0 auto;
    font-family: 'Montserrat Alternates', sans-serif;
}

@media (max-width: 768px) {
    .events-page {
        padding: 40px 10px;
    }
}
</style>
