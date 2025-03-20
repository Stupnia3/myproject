<template>
    <section class="about-section">
        <div class="block">
            <div class="image-section">
                <div class="image-wrapper" data-aos="fade-right" >
                    <div class="gray-background" data-aos-delay="100"></div>
                    <img src="/storage/images/art-studio.jpg" alt="Art Studio" class="image" loading="eager" data-aos-delay="200"/>
                </div>
            </div>
            <div class="text-section" data-aos="fade-left">
                <h2 class="title" data-aos-delay="300">о нас</h2>
                <div class="description" data-aos-delay="700">
                    <p data-aos-delay="700">Арт-терапия — это метод психотерапии, в котором творческий процесс становится основным инструментом для установления связи с
                        собой и окружающим миром. В ходе арт-терапевтических сеансов клиент может использовать различные формы самовыражения, такие
                        как рисование, лепка, музыка, движение или другие виды творчества, чтобы исследовать свои эмоции, переживания и внутренние конфликты.</p>
                    <br>
                    <p>Арт-терапия помогает справиться с тревогой, стрессом, депрессией, улучшить эмоциональное состояние и восстановить внутреннюю гармонию.</p>
                </div>
            </div>
        </div>

    </section>
</template>

<script>
export default {
    mounted() {
        // Принудительная предзагрузка изображения
        const img = new Image();
        img.src = '/storage/images/art-studio.jpg';
        img.onload = () => {
            // Убедимся, что изображение загружено перед рендерингом
            this.$el.querySelector('.image').src = img.src;
        };
    },
};
</script>

<style scoped>
.about-section {
    position: relative;
    padding: 60px 0;
    overflow: visible;
}


.block {
    display: flex;
    align-items: center;
    justify-content: space-between;
    max-width: 100%;
    margin-bottom: 30px;
    gap: 40px;
    position: relative;
    z-index: 1;
}

.block.reverse {
    flex-direction: row-reverse;
}

.image-section {
    flex: 1;
    display: flex;
    justify-content: center;
}

.image-wrapper {
    position: relative;
    overflow: visible;
}

.gray-background {
    position: absolute;
    top: 50%;
    left: 50%;
    width: 100%;
    height: 100%;
    rotate: 30deg;
    background-color: #d3d3d3; /* Серый фон */
    clip-path: path('M659.864 386.043C655.184 413.852 641.706 441.139 616.248 467.026C549.753 534.41 464.789 519.652 379.927 505.98C343.001 500.016 305.151 500.708 267.769 497.65C237.461 495.191 208.347 491.863 186.232 470.865C161.812 447.642 144.837 417.32 121.655 392.718C76.3936 344.588 13.6942 305.844 2.32754 238.194C-3.43234 204.039 3.02088 165.032 25.7812 136.62C47.0643 109.947 79.0417 91.3638 105.607 69.4997C133.697 46.3978 162.12 22.0012 198.091 10.0331C312.113 -27.7926 386.517 57.9377 473.938 103.707C492.154 113.281 511.71 120.624 529.447 130.916C568.605 153.849 597.616 202.663 620.022 239.381C646.929 283.255 668.499 334.367 659.864 386.043Z'); /* Неровная форма */
    z-index: 1;
    transform: scale(1); /* Увеличиваем серый фон, чтобы он был чуть больше картинки */
    transform: translate(-70%, 0%);
}

.image {
    position: relative;
    top: 50%;
    left: 50%;
    width: 100%;
    height: 100%;
    object-fit: cover;
    clip-path: path('M659.864 386.043C655.184 413.852 641.706 441.139 616.248 467.026C549.753 534.41 464.789 519.652 379.927 505.98C343.001 500.016 305.151 500.708 267.769 497.65C237.461 495.191 208.347 491.863 186.232 470.865C161.812 447.642 144.837 417.32 121.655 392.718C76.3936 344.588 13.6942 305.844 2.32754 238.194C-3.43234 204.039 3.02088 165.032 25.7812 136.62C47.0643 109.947 79.0417 91.3638 105.607 69.4997C133.697 46.3978 162.12 22.0012 198.091 10.0331C312.113 -27.7926 386.517 57.9377 473.938 103.707C492.154 113.281 511.71 120.624 529.447 130.916C568.605 153.849 597.616 202.663 620.022 239.381C646.929 283.255 668.499 334.367 659.864 386.043Z'); /* Такая же форма, как у серого фона */
    border: none; /* Убираем рамку, если она была */
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    z-index: 2;
    transform: translate(-50%, -25%);
}

.text-section {
    flex: 1;
    color: #263b6a; /* --astronaut */
}

.title {
    font-size: 48px;
    font-weight: 600;
    text-transform: uppercase;
    margin-bottom: 20px;
}

.description {
    color: #333;
    font-size: 20px;
    line-height: 1.6;
}

.description p {
    margin-bottom: 15px;
}

@media (max-width: 768px) {
    .block {
        flex-direction: column;
        text-align: center;
    }

    .block.reverse {
        flex-direction: column;
    }

    .image-wrapper {
        width: 250px;
        height: 250px;
    }

    .title {
        font-size: 24px;
    }
}
</style>
