<!-- resources/js/components/Modal.vue -->
<template>
    <div v-if="isOpen" class="modal-overlay">
        <div class="modal">
            <h3 class="modal-title">{{ title || 'Без заголовка' }}</h3>
            <p class="modal-message">{{ message || 'Без сообщения' }}</p>
            <div v-if="content" v-html="content" class="modal-content"></div>
            <div class="modal-buttons" v-if="buttons.length">
                <button
                    v-for="(button, index) in buttons"
                    :key="index"
                    :class="['btn', button.class || 'default-btn']"
                    @click="button.action"
                >
                    {{ button.label }}
                </button>
            </div>
            <button v-if="showCloseButton" class="close-btn" @click="closeModal">×</button>
        </div>
    </div>
</template>

<script>
import eventBus from '../eventBus';

export default {
    data() {
        return {
            isOpen: false,
            title: '',
            message: '',
            content: '',
            buttons: [],
            showCloseButton: true,
        };
    },
    methods: {
        openModal({ title = '', message = '', content = '', buttons = [], showCloseButton = true }) {
            console.log('openModal called with:', { title, message, buttons });
            this.title = title;
            this.message = message;
            this.content = content;
            this.buttons = buttons;
            this.showCloseButton = showCloseButton;
            this.isOpen = true;
        },
        closeModal() {
            this.isOpen = false;
            this.title = '';
            this.message = '';
            this.content = '';
            this.buttons = [];
            this.showCloseButton = true;
        },
    },
    mounted() {
        eventBus.on('openModal', this.openModal);
        eventBus.on('closeModal', this.closeModal);
    },
    beforeUnmount() {
        eventBus.off('openModal', this.openModal);
        eventBus.off('closeModal', this.closeModal);
    },
};
</script>

<style scoped>
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 1000;
}

.modal {
    display: block;
    background: #ffffff;
    padding: 30px;
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    width: 400px; /* Фиксированная ширина */
    //min-height: 200px; /* Минимальная высота */
    max-height: fit-content;
    text-align: center;
    font-family: 'Montserrat Alternates', sans-serif;
    position: relative;
    z-index: 1001; /* Выше overlay */
}

.modal-title {
    color: #1e40af;
    font-size: 24px;
    font-weight: 600;
    margin-bottom: 15px;
}

.modal-message {
    color: #333;
    font-size: 16px;
    margin-bottom: 20px;
}

.modal-content {
    color: #333;
    font-size: 16px;
    margin-bottom: 20px;
}

.modal-buttons {
    display: flex;
    justify-content: center;
    gap: 15px;
    flex-wrap: wrap;
}

.btn {
    padding: 10px 20px;
    border-radius: 25px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.3s ease, transform 0.2s ease;
}

.default-btn {
    background: #1e40af;
    color: #ffffff;
    border: none;
}

.default-btn:hover {
    background: #1e3a8a;
    transform: translateY(-2px);
}

.register-btn {
    background: #1e40af;
    color: #ffffff;
    border: none;
}

.register-btn:hover {
    background: #1e3a8a;
    transform: translateY(-2px);
}

.cancel-btn {
    background: #dc2626;
    color: #ffffff;
    border: none;
}

.cancel-btn:hover {
    background: #b91c1c;
    transform: translateY(-2px);
}

.close-btn {
    position: absolute;
    top: 10px;
    right: 10px;
    background: none;
    border: none;
    font-size: 24px;
    color: #dc2626;
    cursor: pointer;
}
.confirm-btn, .form-btn{
    background-color: #85ff0c;
}
.confirm-btn:hover, .form-btn:hover {
    background: #5eb00b;
    transform: translateY(-2px);
}

@media (max-width: 768px) {
    .modal {
        padding: 20px;
        width: 90%;
    }

    .modal-title {
        font-size: 20px;
    }

    .modal-message,
    .modal-content {
        font-size: 14px;
    }

    .btn {
        padding: 8px 15px;
        font-size: 14px;
    }
}
</style>
