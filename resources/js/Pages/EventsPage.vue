<!-- resources/js/Pages/EventsPage.vue -->
<template>
    <div class="events-page">
        <HeaderSection />
        <div class="view-toggle ">
            <button
                @click="toggleView"
                class="toggle-btn"
                :class="{ active: viewMode === 'grid' }"
            >
                {{ viewMode === 'linear' ? 'Сетка' : 'Линейно' }}
            </button>
        </div>
        <div :class="['events-container', { 'grid-mode': viewMode === 'grid' }]">
            <EventSection
                v-for="event in events"
                :key="event.id"
                :event="event"
                :auth="auth"
                :view-mode="viewMode"
            />
        </div>
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
    data() {
        return {
            viewMode: 'linear', // По умолчанию линейное отображение
        };
    },
    methods: {
        toggleView() {
            this.viewMode = this.viewMode === 'linear' ? 'grid' : 'linear';
        },
    },
};
</script>
<style scoped>
.events-page {
    max-width: 1577px;
    width: 100%;
    margin: 0 auto;
    font-family: 'Montserrat Alternates', sans-serif;
}

.view-toggle {
    text-align: right;
    margin: 20px 0;
}

.toggle-btn {
    background: #1e40af;
    color: #ffffff;
    padding: 10px 20px;
    border-radius: 25px;
    border: none;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.3s ease, transform 0.2s ease;
}

.toggle-btn:hover {
    background: #1e3a8a;
    transform: translateY(-2px);
}

.toggle-btn.active {
    background: #2d6a4f;
}

.events-container {
    display: flex;
    flex-direction: column;
    gap: 40px;
}

.events-container.grid-mode {
    flex-direction: row; /* Горизонтальное расположение */
    flex-wrap: wrap; /* Перенос на следующую строку */
    gap: 20px; /* Уменьшенный отступ между карточками */
    justify-content: center; /* Центрирование карточек */
}

@media (max-width: 768px) {
    .events-page {
        padding: 40px 10px;
    }

    .events-container.grid-mode {
        flex-direction: column; /* На маленьких экранах всё в столбец */
        gap: 20px;
    }

    .view-toggle {
        text-align: center;
    }
}
</style>
