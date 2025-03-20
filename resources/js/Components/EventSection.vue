<!-- resources/js/components/EventSection.vue -->
<template>
    <section class="event-section">
        <div class="block">
            <EventText :event="event" />
            <EventImage :event="event" :auth="auth" />
        </div>
    </section>
</template>

<script>
import EventText from './EventText.vue';
import EventImage from './EventImage.vue';

export default {
    components: {
        EventText,
        EventImage,
    },
    props: {
        event: Object,
        auth: Object,
    },
};
</script>

<style scoped>
.event-section {
    position: relative;
    padding: 60px 0;
    overflow: visible;
}

.block {
    display: flex;
    align-items: center;
    justify-content: space-between;
    max-width: 1577px;
    margin: 0 auto;
    gap: 40px;
    position: relative;
    z-index: 1;
    border-radius: 30px;
    overflow: hidden;
}

@media (max-width: 768px) {
    .block {
        flex-direction: column;
        text-align: center;
    }
}
</style>
