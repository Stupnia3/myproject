<template>
    <div class="layout">
        <Header :auth="auth" :csrf_token="csrf_token" />
        <main class="content">
            <slot />
        </main>
        <Footer />
        <Modal />
    </div>
</template>

<script>
import { Head } from '@inertiajs/vue3';
import Header from '../Components/Header.vue';
import Footer from '../Components/Footer.vue';
import Modal from '../Components/Modal.vue';

export default {
    components: { Header, Footer, Modal },
    props: {
        auth: Object,
        csrf_token: String,
    },
    mounted() {
        console.log('AppLayout props:', this.$props); // Проверяем, что приходит в пропсах
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
