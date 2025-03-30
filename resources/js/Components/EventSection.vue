<!-- resources/js/components/EventSection.vue -->
<template>
    <section class="event-section" :class="{ 'grid-view': viewMode === 'grid' }">
        <div class="block">
            <EventText :event="event" :auth="auth" :view-mode="viewMode" />
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
        viewMode: String,
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

.event-section.grid-view .block {
    flex-direction: column; /* В сеточном режиме текст и изображение в столбец */
    text-align: center;
    max-width: 350px; /* Уменьшенная ширина карточки */
    background: #f5f5f5; /* Лёгкий фон */
    padding: 15px; /* Уменьшенный отступ */
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

@media (max-width: 768px) {
    .block {
        flex-direction: column;
        text-align: center;
    }

    .event-section.grid-view .block {
        max-width: 100%; /* На маленьких экранах карточка занимает всю ширину */
    }
}
</style>
