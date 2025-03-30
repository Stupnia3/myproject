<!-- resources/js/components/EventImage.vue -->
<template>
    <div class="image-section" >
        <div class="image-wrapper" >
            <img
                :src="event.photo ? `/storage/${event.photo}` : '/storage/images/default-event.jpg'"
                alt="Event Image"
                class="image"
                loading="eager"
                data-aos-delay="300"
            />
        </div>
        <EventInfo :event="event" :auth="auth" @register="handleRegister" />
    </div>
</template>

<script>
import EventInfo from './EventInfo.vue';
import { useForm } from '@inertiajs/vue3';
import eventBus from '../eventBus';

export default {
    components: { EventInfo },
    props: { event: Object, auth: Object },
    setup(props) {
        const form = useForm({});

        const handleRegister = () => {
            if (props.event.available_seats <= 0) {
                eventBus.emit('openModal', {
                    title: 'Нет мест',
                    message: 'К сожалению, все места на это мероприятие заняты.',
                    buttons: [
                        { label: '', class: 'close-btn', action: () => eventBus.emit('closeModal') },
                    ],
                });
                return;
            }

            if (props.auth?.user) {
                eventBus.emit('openModal', {
                    title: 'Подтверждение записи',
                    message: 'Вы точно хотите записаться?',
                    buttons: [
                        {
                            label: 'Да',
                            class: 'confirm-btn',
                            action: () => {
                                form.post(`/event/${props.event.id}/register`, {
                                    headers: {
                                        'X-CSRF-TOKEN': window.csrf_token || document.querySelector('meta[name="csrf-token"]')?.content,
                                    },
                                    onSuccess: () => {
                                        eventBus.emit('openModal', {
                                            title: 'Успех',
                                            message: 'Вы успешно записаны!',
                                            buttons: [
                                                { label: '', class: 'close-btn', action: () => eventBus.emit('closeModal') },
                                            ],
                                        });
                                    },
                                    onError: (errors) => {
                                        console.error('Registration error:', errors);
                                        eventBus.emit('openModal', {
                                            title: 'Ошибка',
                                            message: errors.message || 'Произошла ошибка при записи.',
                                            buttons: [
                                                { label: '', class: 'close-btn', action: () => eventBus.emit('closeModal') },
                                            ],
                                        });
                                    },
                                });
                            },
                        },
                        { label: 'Отмена', class: 'cancel-btn', action: () => eventBus.emit('closeModal') },
                    ],
                });
            } else {
                eventBus.emit('openModal', {
                    title: 'Требуется регистрация',
                    message: 'Зарегистрируйтесь или заполните форму для записи!',
                    buttons: [
                        { label: 'Регистрация', class: 'register-btn', action: () => window.location.href = '/register' },
                        {
                            label: 'Заполнить форму',
                            class: 'form-btn',
                            action: () => window.location.href = `/event/${props.event.id}/register-form`
                        },
                        { label: 'Отмена', class: 'cancel-btn', action: () => eventBus.emit('closeModal') },
                    ],
                });
            }
        };

        return { handleRegister };
    },
    mounted() {
        const img = new Image();
        img.src = this.event.photo ? `/storage/${this.event.photo}` : '/storage/images/default-event.jpg';
        img.onload = () => {
            this.$el.querySelector('.image').src = img.src;
        };
    },
};
</script>

<style scoped>
.image-section {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    position: relative;
}

.image-wrapper {
    position: relative;
    width: 500px;
    height: 500px;
    border-radius: 90px;
    overflow: hidden;
    margin-bottom: 20px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
    transition: transform 0.3s ease;
}

.image-wrapper:hover {
    transform: scale(1.02);
}

.image {
    position: absolute;
    top: 50%;
    left: 50%;
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 30px;
    z-index: 2;
    transform: translate(-50%, -50%);
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

@media (max-width: 768px) {
    .image-wrapper {
        width: 250px;
        height: 250px;
    }
}

/* Для сеточного режима */
.event-section.grid-view .image-wrapper {
    width: 100%;
    height: 200px; /* Уменьшенная высота для сетки */
    border-radius: 30px;
}

@media (max-width: 768px) {
    .event-section.grid-view .image-wrapper {
        height: 150px; /* Ещё меньше для маленьких экранов */
    }
}
</style>
