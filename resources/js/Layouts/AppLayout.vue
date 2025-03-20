<!-- resources/js/Layouts/AppLayout.vue -->
<template>
    <div class="layout">
        <Header :auth="auth" />
        <main class="content">
            <slot />
        </main>
        <Footer />
        <Modal />
    </div>
</template>

<script>
import Header from '../Components/Header.vue';
import Footer from '../Components/Footer.vue';
import Modal from '../Components/Modal.vue';

export default {
    components: { Header, Footer, Modal },
    props: {
        auth: Object,
    },
};
</script>

<style scoped>
.layout {
    min-height: 100vh;
    font-family: 'Montserrat Alternates', sans-serif;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    position: relative;
    overflow: hidden;
}

.content {
    flex: 1;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 20px;
    position: relative;
    z-index: 1;
}
</style>
