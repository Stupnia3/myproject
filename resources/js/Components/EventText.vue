<!-- resources/js/components/EventText.vue -->
<template>
    <div class="text-section" data-aos="fade-right">
        <h2 class="title" data-aos-delay="300">{{ event.title }}</h2>
        <div class="description" data-aos-delay="700">
            <p data-aos-delay="700">{{ event.description }}</p>
            <div class="list-section">
                <h3>Практическая часть:</h3>
                <ul class="custom-list">
                    <li v-for="(part, index) in event.practical_parts" :key="index" data-aos-delay="800">
                        {{ part }}
                    </li>
                </ul>
            </div>
            <div class="list-section">
                <h3>Методики:</h3>
                <ul class="custom-list">
                    <li v-for="(method, index) in event.methodologies" :key="index" data-aos-delay="900">
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
</style>
