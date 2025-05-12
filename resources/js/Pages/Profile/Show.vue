<template>
    <div class="profile-page">
        <HeaderSection :showSlogan="false" />
        <div class="profile-content">
            <h1>Личный кабинет</h1>
            <div class="user-info">
                <h2>Личные данные</h2>
                <div class="user-info-content">
                    <div class="user-details">
                        <p><strong>Имя:</strong> {{ auth.user.name }}</p>
                        <p><strong>Email:</strong> {{ auth.user.email }}</p>
                        <p><strong>Телефон:</strong> {{ auth.user.phone || 'Не указан' }}</p>
                        <p><strong>Роль:</strong> {{ auth.user.role === 'admin' ? 'Администратор' : 'Пользователь' }}</p>
                        <Link :href="route('profile.edit')" class="edit-btn">Редактировать профиль</Link>
                    </div>
                    <div class="user-avatar">
                        <img :src="auth.user.photo ? `/storage/${auth.user.photo}` : '/storage/images/avatardefault.png'" alt="User Avatar" class="avatar-image" />
                        <label for="avatar-upload" class="avatar-upload-btn">
                            <i class="fas fa-camera"></i> Сменить фото
                        </label>
                        <input
                            id="avatar-upload"
                            type="file"
                            class="hidden-file-input"
                            accept="image/*"
                            @change="uploadAvatar"
                        />
                    </div>
                </div>
            </div>
            <div class="user-events">
                <h2>Мои арт-терапии</h2>
                <ul class="events-list">
                    <li v-for="registration in registrations" :key="registration.id">
                        <Link :href="route('event.details', registration.event.id)" class="event-link">
                            {{ registration.event.title }}
                        </Link>
                        <span>{{ formatDate(registration.event.start_date) }} - {{ statusLabel(registration.status) }}</span>
                    </li>
                    <li v-if="!registrations.length">Вы пока не записаны на мероприятия.</li>
                </ul>
            </div>
            <div class="user-calendar">
                <h2>Календарь мероприятий</h2>
                <FullCalendar :options="calendarOptions" />
            </div>
        </div>
    </div>
</template>

<script>
import HeaderSection from '@/Components/HeaderSection.vue';
import { Link, useForm } from '@inertiajs/vue3';
import FullCalendar from '@fullcalendar/vue3';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';

export default {
    components: { HeaderSection, Link, FullCalendar },
    props: {
        auth: Object,
        registrations: Array,
        calendarEvents: Array,
    },
    setup() {
        const form = useForm({
            photo: null,
        });

        function uploadAvatar(event) {
            form.photo = event.target.files[0];
            form.post(route('profile.update.avatar'), {
                forceFormData: true,
                onSuccess: () => {
                    form.reset('photo');
                    event.target.value = ''; // Очищаем input
                },
            });
        }

        return { form, uploadAvatar };
    },
    data() {
        return {
            calendarOptions: {
                plugins: [dayGridPlugin, timeGridPlugin],
                initialView: 'dayGridMonth',
                events: this.calendarEvents,
                locale: 'ru',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay',
                },
                buttonText: {
                    today: 'Сегодня',
                    month: 'Месяц',
                    week: 'Неделя',
                    day: 'День',
                },
                eventClick: this.handleEventClick,
                height: 'auto',
                eventBackgroundColor: '#6b7280',
                eventBorderColor: '#4b5563',
                eventTextColor: '#ffffff',
                eventContent: function(info) {
                    return { html: `<span>${info.event.title}</span>` }; // Только название
                },
                eventDidMount: function(info) {
                    info.el.title = info.event.title; // Подсказка при наведении
                },
            },
        };
    },
    methods: {
        formatDate(date) {
            return new Date(date).toLocaleDateString('ru-RU', {
                day: '2-digit',
                month: 'long',
                year: 'numeric',
            });
        },
        statusLabel(status) {
            const labels = {
                pending: 'Ожидает подтверждения',
                confirmed: 'Подтверждено',
                rejected: 'Отклонено',
            };
            return labels[status] || status;
        },
        handleEventClick(info) {
            info.jsEvent.preventDefault();
            if (info.event.url) {
                this.$inertia.visit(info.event.url);
            }
        },
    },
};
</script>

<style scoped>
.profile-page {
    max-width: 1200px;
    margin: 0 auto;
    font-family: 'Montserrat Alternates', sans-serif;
    padding: 40px 20px;
}

.profile-content {
    margin-top: 40px;
}

h1 {
    font-size: 36px;
    font-weight: 600;
    color: #1e40af;
    text-transform: uppercase;
    margin-bottom: 30px;
}

.user-info,
.user-events,
.user-calendar {
    margin-bottom: 40px;
}

.user-info h2,
.user-events h2,
.user-calendar h2 {
    font-size: 24px;
    font-weight: 600;
    color: #1e40af;
    margin-bottom: 15px;
}

.user-info-content {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
}

.user-details {
    flex: 1;
}

.user-info p {
    font-size: 18px;
    color: #333;
    margin-bottom: 10px;
}

.user-avatar {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 10px;
}

.avatar-image {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #1e40af;
}

.avatar-upload-btn {
    display: inline-flex;
    align-items: center;
    padding: 10px 20px;
    background: #1e40af;
    color: #ffffff;
    border-radius: 25px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.3s ease;
}

.avatar-upload-btn:hover {
    background: #1e3a8a;
}

.avatar-upload-btn i {
    margin-right: 8px;
}

.hidden-file-input {
    display: none;
}

.edit-btn {
    display: inline-block;
    background: #1e40af;
    color: #ffffff;
    padding: 10px 20px;
    border-radius: 25px;
    text-decoration: none;
    font-size: 16px;
    font-weight: 600;
    transition: background 0.3s ease;
}

.edit-btn:hover {
    background: #1e3a8a;
}

.events-list {
    list-style: none;
    padding: 0;
}

.events-list li {
    display: flex;
    justify-content: space-between;
    padding: 15px;
    background: #f8f8f8;
    border-radius: 8px;
    margin-bottom: 10px;
}

.event-link {
    font-size: 16px;
    color: #1e40af;
    text-decoration: none;
    transition: color 0.3s ease;
}

.event-link:hover {
    color: #1e3a8a;
    text-decoration: underline;
}

.events-list li span {
    font-size: 16px;
    color: #333;
}

.user-calendar :deep(.fc) {
    font-family: 'Montserrat Alternates', sans-serif;
}

.user-calendar :deep(.fc-toolbar-title) {
    font-size: 24px;
    color: #1e40af;
}

.user-calendar :deep(.fc-button) {
    background: #1e40af;
    border: none;
    color: #ffffff;
    font-size: 14px;
    padding: 8px 12px;
    border-radius: 25px;
    transition: background 0.3s ease;
}

.user-calendar :deep(.fc-button:hover) {
    background: #1e3a8a;
}

.user-calendar :deep(.fc-button.fc-button-active) {
    background: #1e3a8a;
}

.user-calendar :deep(.fc-event) {
    cursor: pointer;
    border-radius: 4px;
    padding: 4px 6px;
    font-size: 14px;
}

.user-calendar :deep(.fc-daygrid-event) {
    margin: 2px;
    height: calc(100% - 4px);
    width: calc(100% - 4px);
    display: flex;
    align-items: center;
    justify-content: center;
}

.user-calendar :deep(.fc-daygrid-day-number) {
    position: relative;
    z-index: 2;
    color: #000000;
    font-weight: 600;
}

.user-calendar :deep(.fc-daygrid-day-frame) {
    position: relative;
}

.user-calendar :deep(.fc-daygrid-day-bg) {
    z-index: 0;
}

@media (max-width: 768px) {
    h1 {
        font-size: 24px;
    }

    .user-info h2,
    .user-events h2,
    .user-calendar h2 {
        font-size: 20px;
    }

    .user-info p,
    .events-list li span,
    .event-link {
        font-size: 14px;
    }

    .edit-btn {
        font-size: 14px;
        padding: 8px 15px;
    }

    .user-calendar :deep(.fc-toolbar-title) {
        font-size: 18px;
    }

    .user-calendar :deep(.fc-button) {
        font-size: 12px;
        padding: 6px 10px;
    }

    .user-calendar :deep(.fc-event) {
        font-size: 12px;
        padding: 2px 4px;
    }

    .user-info-content {
        flex-direction: column;
        align-items: center;
    }

    .user-avatar {
        margin-top: 20px;
    }

    .avatar-image {
        width: 120px;
        height: 120px;
    }
}
</style>
