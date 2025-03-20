<template>
    <section class="about-section header_text" data-aos="fade-left" data-aos-delay="100"  >
            <p style="color: #1264a9; font-weight: 700;">Искусство</p>
            <p>позволяет нам увидеть то, что мы</p>
            <p style="text-align: end">не можем выразить словами</p>
    </section>
</template>

<script>
export default {};
</script>

<style scoped>
.about-section {
    position: relative;
    padding: 60px 0;
    overflow: hidden;
}

.header_text{
    display: flex;
    flex-direction: column;
    gap: 1px;
    width: 600px;
    font-size: 24px;
}


.description p {
    margin-bottom: 15px;
}

@media (max-width: 768px) {

}
</style>
