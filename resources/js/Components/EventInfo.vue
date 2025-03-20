<!-- resources/js/components/EventInfo.vue -->
<template>
    <div class="info-section">
        <button class="register-btn" @click="$emit('register')">записаться</button>
        <div class="event-info">
            <p class="date">Дата: {{ formatDate(event.start_date, event.end_date) }}</p>
            <p class="seats">Свободно мест: {{ event.total_seats - event.occupied_seats }}</p>
        </div>
    </div>
</template>

<script>
import { defineComponent } from 'vue';

export default defineComponent({
    props: {
        event: Object,
        auth: Object,
    },
    emits: ['register'],
    methods: {
        formatDate(start, end) {
            const formatDatePart = (date) => {
                return new Date(date).toLocaleDateString('ru-RU', {
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric',
                });
            };
            if (!end) return formatDatePart(start);
            return `${formatDatePart(start)} - ${formatDatePart(end)}`;
        },
    },
});
</script>

<style scoped>
.info-section {
    text-align: center;
    margin-top: 10px;
}

.register-btn {
    display: inline-block;
    background: #000000;
    color: #ffffff;
    padding: 12px 25px;
    border-radius: 25px;
    text-decoration: none;
    font-size: 16px;
    font-weight: 600;
    transition: background 0.3s ease, transform 0.2s ease;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
    border: none;
    cursor: pointer;
}

.register-btn:hover {
    background: #333333;
    transform: translateY(-2px);
}

.event-info {
    margin-top: 10px;
    color: #263b6a;
    font-size: 16px;
    font-weight: 500;
}

.event-info .date {
    margin: 5px 0;
    color: #1e40af;
    font-style: italic;
}

.event-info .seats {
    margin: 5px 0;
    color: #2d6a4f;
}

@media (max-width: 768px) {
    .register-btn {
        padding: 8px 15px;
    }

    .event-info {
        font-size: 14px;
    }
}
</style>
