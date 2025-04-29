<!-- resources/js/Pages/EventDetails.vue -->
<template>
    <div class="event-details-page">
        <HeaderSection />
        <div class="event-details-content">
            <EventImage :event="event" :auth="auth" />
            <div class="event-details-text">
                <h1 class="event-title">{{ event.title }}</h1>
                <div class="event-tags">
                    <span v-for="tag in event.tags" :key="tag" class="tag">{{ tagLabel(tag) }}</span>
                </div>
                <p class="event-description">{{ event.description }}</p>
                <div class="event-details-section">
                    <h3>Практические части:</h3>
                    <ul class="custom-list">
                        <li v-for="(part, index) in event.practical_parts" :key="index">{{ part }}</li>
                    </ul>
                </div>
                <div class="event-details-section">
                    <h3>Методики:</h3>
                    <ul class="custom-list">
                        <li v-for="(method, index) in event.methodologies" :key="index">{{ method }}</li>
                    </ul>
                </div>
                <div class="event-details-section">
                    <h3>Место проведения:</h3>
                    <p>{{ event.location || 'Не указано' }}</p>
                </div>
                <div class="event-details-section">
                    <h3>Длительность:</h3>
                    <p>{{ event.duration ? `${event.duration} ч.` : 'Не указано' }}</p>
                </div>
                <div class="event-details-section">
                    <h3>Преподаватели:</h3>
                    <ul class="custom-list">
                        <li v-for="teacher in event.teachers" :key="teacher.id">{{ teacher.name }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import HeaderSection from '@/Components/HeaderSection.vue';
import EventImage from '../components/EventImage.vue';

export default {
    components: {
        HeaderSection,
        EventImage,
    },
    props: {
        event: Object,
        auth: Object,
    },
    methods: {
        tagLabel(tag) {
            const tags = {
                'art-therapy': 'Арт-терапия',
                'master-class': 'Мастер-класс',
                'retreat': 'Ретрит',
            };
            return tags[tag] || tag;
        },
    },
};
</script>

<style scoped>
.event-details-page {
    max-width: 1577px;
    margin: 0 auto;
    font-family: 'Montserrat Alternates', sans-serif;
    padding: 40px 20px;
}

.event-details-content {
    display: flex;
    justify-content: space-between;
    gap: 40px;
    margin-top: 40px;
}

.event-details-text {
    flex: 1;
}

.event-title {
    font-size: 36px;
    font-weight: 600;
    color: #1e40af;
    margin-bottom: 10px;
    text-transform: uppercase;
}

.event-tags {
    margin-bottom: 20px;
}

.tag {
    display: inline-block;
    background: #1e40af;
    color: #ffffff;
    padding: 5px 10px;
    border-radius: 15px;
    font-size: 14px;
    margin-right: 5px;
    margin-bottom: 5px;
}

.event-description {
    font-size: 18px;
    color: #333;
    line-height: 1.6;
    margin-bottom: 30px;
}

.event-details-section {
    margin-bottom: 30px;
}

.event-details-section h3 {
    font-size: 24px;
    font-weight: 600;
    color: #1e40af;
    margin-bottom: 15px;
}

.event-details-section p {
    font-size: 16px;
    color: #333;
}

.custom-list {
    list-style-type: none;
    padding-left: 20px;
}

.custom-list li {
    position: relative;
    padding-left: 15px;
    margin-bottom: 10px;
    text-align: justify;
}

.custom-list li::before {
    content: "•";
    color: #1e40af;
    font-weight: bold;
    position: absolute;
    left: 0;
}

@media (max-width: 768px) {
    .event-details-content {
        flex-direction: column;
        gap: 20px;
    }

    .event-title {
        font-size: 24px;
    }

    .event-description {
        font-size: 16px;
    }

    .event-details-section h3 {
        font-size: 20px;
    }

    .event-details-section p,
    .custom-list li {
        font-size: 14px;
    }

    .tag {
        font-size: 12px;
        padding: 4px 8px;
    }
}
</style>
