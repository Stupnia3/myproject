<template>
    <section class="thoughts-section">
        <h2 class="section-title">интересные мысли</h2>
        <p class="section-subtitle">Работы участников творческой сессии</p>
        <div class="thoughts-gallery">
            <img src="/storage/images/art1.jpg" alt="Art 1" class="gallery-image" />
            <img src="/storage/images/art2.jpg" alt="Art 2" class="gallery-image" />
            <img src="/storage/images/art3.jpg" alt="Art 3" class="gallery-image" />
            <img src="/storage/images/art4.jpg" alt="Art 4" class="gallery-image" />
        </div>
    </section>
</template>

<script>
export default {};
</script>

<style scoped>
.thoughts-section {
    padding: 60px 20px;
    background-color: var(--white);
    text-align: center;
}

.section-title {
    font-size: var(--font-size-xl);
    color: var(--astronaut);
    text-transform: uppercase;
    margin-bottom: 20px;
}

.section-subtitle {
    font-size: var(--font-size-m);
    color: #333;
    margin-bottom: 40px;
}

.thoughts-gallery {
    display: flex;
    justify-content: center;
    gap: 20px;
}

.gallery-image {
    width: 344px;
    height: 344px;
    object-fit: cover;
    border-radius: 25px;
}

@media (max-width: 768px) {
    .thoughts-gallery {
        flex-direction: column;
        align-items: center;
    }

    .gallery-image {
        width: 90%;
    }
}
</style>
