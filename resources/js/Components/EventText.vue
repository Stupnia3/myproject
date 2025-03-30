<!-- resources/js/components/EventText.vue -->
<template>
    <div class="text-section">
        <h2 class="title" >{{ event.title }}</h2>
        <div class="description" >
            <p>
                {{ viewMode === 'grid' ? truncateDescription(event.description, 100) : event.description }}
            </p>
            <div v-if="viewMode !== 'grid'" class="list-section">
                <h3>Практическая часть:</h3>
                <ul class="custom-list">
                    <li v-for="(part, index) in event.practical_parts" :key="index">
                        {{ part }}
                    </li>
                </ul>
            </div>
            <div v-if="viewMode !== 'grid'" class="list-section">
                <h3>Методики:</h3>
                <ul class="custom-list">
                    <li v-for="(method, index) in event.methodologies" :key="index" >
                        {{ method }}
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        event: Object,
        auth: Object,
        viewMode: String, // Новый проп для режима отображения
    },
    methods: {
        truncateDescription(text, length) {
            if (text.length <= length) return text;
            return text.substring(0, length) + '...';
        },
    },
};
</script>

<style scoped>
.text-section {
    flex: 1;
    color: #263b6a;
    padding: 20px;
}

.title {
    font-size: 36px;
    font-weight: 600;
    margin-bottom: 20px;
    color: #1e40af;
    text-transform: uppercase;
}

.grid-mode .title{
    font-size: 20px;
    height: 48px;
    overflow: hidden;
}

.description {
    color: #333;
    font-size: 18px;
    line-height: 1.6;
    text-align: justify;
}

.description p {
    margin-bottom: 10px;
}

.list-section {
    margin: 15px 0;
}

.list-section h3 {
    color: #1e40af;
    font-size: 20px;
    font-weight: 600;
    margin-bottom: 10px;
}

.custom-list {
    list-style-type: none;
    padding-left: 20px;
}

.custom-list li {
    position: relative;
    padding-left: 15px;
    margin-bottom: 5px;
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
    .title {
        font-size: 24px;
    }

    .description {
        font-size: 16px;
    }
}

/* Для сеточного режима */
.text-section.grid-view .title {
    font-size: 24px; /* Уменьшенный заголовок */
}

.text-section.grid-view .description {
    font-size: 14px; /* Уменьшенный текст */
}
</style>
