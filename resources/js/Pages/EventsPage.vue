<!-- resources/js/Pages/EventsPage.vue -->
<template>
    <div class="events-page">
        <HeaderSection />
        <div class="view-toggle">
            <button
                @click="toggleView"
                class="toggle-btn"
                :class="{ active: viewMode === 'grid' }"
                :title="viewMode === 'linear' ? 'Сетка' : 'Линейно'"
            >
                <i :class="viewMode === 'linear' ? 'fas fa-th' : 'fas fa-align-justify'" style="color: black"></i>
            </button>
        </div>
        <div :class="['events-container', { 'grid-mode': viewMode === 'grid' }]">
            <div v-for="event in events" :key="event.id" class="event-card" :class="{ 'linear-card': viewMode === 'linear' }">
                <div class="event-card-content">
                    <img
                        :src="event.photo ? `/storage/${event.photo}` : '/storage/images/default-event.jpg'"
                        alt="Event Image"
                        class="event-card-image"
                    />
                    <div class="event-card-text">
                        <h2 class="event-card-title">{{ event.title }}</h2>
                        <p class="event-card-description">
                            {{ truncateDescription(event.description, viewMode === 'grid' ? 100 : 200) }}
                        </p>
                        <div v-if="viewMode === 'linear'" class="event-card-details">
                            <!-- В режиме "Линейно" показываем только даты и места -->
                            <div class="event-card-info">
                                <p class="event-card-date">Дата: {{ formatDate(event.start_date, event.end_date) }}</p>
                                <p class="event-card-seats">Свободно мест: {{ event.available_seats }}</p>
                            </div>
                        </div>
                        <div v-if="viewMode === 'grid'" class="event-card-info">
                            <p class="event-card-date">Дата: {{ formatDate(event.start_date, event.end_date) }}</p>
                            <p class="event-card-seats">Свободно мест: {{ event.available_seats }}</p>
                        </div>
                        <div class="event-card-actions">
                            <Link :href="`/event/${event.id}`" class="details-btn">Подробнее</Link>
                            <button
                                class="register-btn"
                                style="background: #2d6a4f;"
                                @click="handleRegister(event)"
                                :disabled="event.available_seats <= 0"
                                :class="{ 'disabled': event.available_seats <= 0 }"
                            >
                                {{ event.available_seats > 0 ? 'Записаться' : 'Мест нет' }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import HeaderSection from '@/Components/HeaderSection.vue';
import { Link, useForm } from '@inertiajs/vue3';
import eventBus from '../eventBus';

export default {
    components: {
        HeaderSection,
        Link,
    },
    props: {
        events: Array,
        auth: Object,
    },
    data() {
        return {
            viewMode: 'grid', // По умолчанию сетка
        };
    },
    setup(props) {
        const form = useForm({});

        const handleRegister = (event) => {
            if (event.available_seats <= 0) {
                eventBus.emit('openModal', {
                    title: 'Нет мест',
                    message: 'К сожалению, все места на это мероприятие заняты.',
                    buttons: [
                        { label: 'Закрыть', class: 'close-btn', action: () => eventBus.emit('closeModal') },
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
                                form.post(`/event/${event.id}/register`, {
                                    headers: {
                                        'X-CSRF-TOKEN': window.csrf_token || document.querySelector('meta[name="csrf-token"]')?.content,
                                    },
                                    onSuccess: () => {
                                        eventBus.emit('openModal', {
                                            title: 'Успех',
                                            message: 'Вы успешно записаны!',
                                            buttons: [
                                                { label: 'Закрыть', class: 'close-btn', action: () => eventBus.emit('closeModal') },
                                            ],
                                        });
                                    },
                                    onError: (errors) => {
                                        console.error('Registration error:', errors);
                                        eventBus.emit('openModal', {
                                            title: 'Ошибка',
                                            message: errors.message || 'Произошла ошибка при записи.',
                                            buttons: [
                                                { label: 'Закрыть', class: 'close-btn', action: () => eventBus.emit('closeModal') },
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
                            action: () => window.location.href = `/event/${event.id}/register-form`
                        },
                        { label: 'Отмена', class: 'cancel-btn', action: () => eventBus.emit('closeModal') },
                    ],
                });
            }
        };

        return { handleRegister };
    },
    methods: {
        toggleView() {
            this.viewMode = this.viewMode === 'linear' ? 'grid' : 'linear';
        },
        truncateDescription(text, length) {
            if (text.length <= length) return text;
            return text.substring(0, length) + '...';
        },
        formatDate(start, end) {
            const formatDatePart = (date) => {
                if (!date) return 'Не указано';
                try {
                    return new Date(date).toLocaleDateString('ru-RU', {
                        day: '2-digit',
                        month: '2-digit',
                        year: 'numeric',
                    });
                } catch (e) {
                    return 'Неверная дата';
                }
            };
            const startFormatted = formatDatePart(start);
            const endFormatted = formatDatePart(end);
            if (!end) return startFormatted;
            return `${startFormatted} - ${endFormatted}`;
        },
    },
};
</script>

<style scoped>
.events-page {
    max-width: 1577px;
    width: 100%;
    margin: 0 auto;
    font-family: 'Montserrat Alternates', sans-serif;
    padding: 40px 20px;
}

.view-toggle {
    text-align: right;
    margin: 20px 0;
}

.toggle-btn {
    background: #c7c7c7;
    color: #ffffff;
    padding: 10px;
    border-radius: 25px;
    border: none;
    font-size: 16px;
    cursor: pointer;
    transition: background 0.3s ease, transform 0.2s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
}

.toggle-btn i {
    font-size: 20px;
}

.toggle-btn:hover {
    background: #8d8d8d;
    transform: translateY(-2px);
}

.toggle-btn.active {
    background: #c7c7c7;
}
.toggle-btn.active:hover{
    background: #8d8d8d;
}

.events-container {
    display: flex;
    flex-direction: column;
    gap: 40px;
}

.events-container.grid-mode {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 30px;
}

.event-card {
    background: #ffffff;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease;
}

.event-card:hover {
    transform: translateY(-5px);
}

.event-card-content {
    display: flex;
    flex-direction: column;
}

.event-card-image {
    width: 100%;
    height: 200px;
    object-fit: cover;
    border-bottom: 2px solid #1e40af;
}

.event-card-text {
    padding: 20px;
}

.event-card-title {
    font-size: 24px;
    font-weight: 600;
    color: #1e40af;
    margin-bottom: 10px;
}

.event-card-description {
    font-size: 16px;
    color: #333;
    line-height: 1.6;
    margin-bottom: 15px;
}

.event-card-info {
    margin-bottom: 15px;
}

.event-card-date {
    font-size: 16px;
    color: #1e40af;
    font-style: italic;
    margin-bottom: 5px;
}

.event-card-seats {
    font-size: 16px;
    color: #2d6a4f;
}

.event-card-actions {
    display: flex;
    gap: 10px;
    justify-content: center;
}

.details-btn {
    display: inline-block;
    background: #1e40af;
    color: #ffffff;
    padding: 10px 20px;
    border-radius: 25px;
    text-decoration: none;
    font-size: 16px;
    font-weight: 600;
    transition: background 0.3s ease, transform 0.2s ease;
}

.details-btn:hover {
    background: #1e3a8a;
    transform: translateY(-2px);
}

.register-btn {
    display: inline-block;
    background: #000000;
    color: #ffffff;
    padding: 10px 20px;
    border-radius: 25px;
    font-size: 16px;
    font-weight: 600;
    transition: background 0.3s ease, transform 0.2s ease;
    border: none;
    cursor: pointer;
}

.register-btn:hover {
    background: #333333;
    transform: translateY(-2px);
}

.register-btn.disabled {
    background: #a3bffa;
    cursor: not-allowed;
}

.linear-card .event-card-content {
    flex-direction: row;
    align-items: center;
    gap: 40px;
}

.linear-card .event-card-image {
    width: 300px;
    height: 300px;
    border-radius: 30px;
}

.linear-card .event-card-text {
    flex: 1;
}

@media (max-width: 768px) {
    .events-page {
        padding: 40px 10px;
    }

    .events-container.grid-mode {
        grid-template-columns: 1fr;
    }

    .event-card-image {
        height: 150px;
    }

    .event-card-title {
        font-size: 20px;
    }

    .event-card-description,
    .event-card-date,
    .event-card-seats {
        font-size: 14px;
    }

    .details-btn,
    .register-btn {
        padding: 8px 15px;
        font-size: 14px;
    }

    .linear-card .event-card-content {
        flex-direction: column;
        gap: 20px;
    }

    .linear-card .event-card-image {
        width: 200px;
        height: 200px;
    }

    .toggle-btn {
        width: 35px;
        height: 35px;
    }

    .toggle-btn i {
        font-size: 18px;
    }
}
</style>
