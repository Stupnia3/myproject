<!-- resources/js/Pages/Dashboard.vue -->
<template>
    <div class="page">
        <main class="content">
            <div class="dashboard-container">
                <div class="header-section">
                    <h1 class="dashboard-title">Панель администратора</h1>
                    <p class="welcome-text">Добро пожаловать, {{ user.name }}!</p>
                </div>

                <div class="event-selector">
                    <label for="event-select" class="select-label">Выберите мероприятие:</label>
                    <select
                        id="event-select"
                        v-model="selectedEvent"
                        @change="fetchRegistrations"
                        class="event-dropdown"
                    >
                        <option :value="null" disabled>Выберите мероприятие</option>
                        <option v-for="event in events" :key="event.id" :value="event.id">
                            {{ event.title }}
                        </option>
                    </select>
                </div>
                <button class="btn logout-btn"><Link href="/dashboard" class="nav-item">Добавить мероприятие</Link></button>

                <div v-if="registrations.length > 0" class="registrations-table">
                    <table>
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Имя</th>
                            <th>Email</th>
                            <th>Телефон</th>
                            <th>Дата записи</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="registration in registrations" :key="registration.id">
                            <td>{{ registration.id }}</td>
                            <td>{{ registration.name }}</td>
                            <td>{{ registration.email }}</td>
                            <td>{{ registration.phone }}</td>
                            <td>{{ formatDate(registration.created_at) }}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div v-else-if="selectedEvent" class="no-registrations">
                    Нет записей для этого мероприятия.
                </div>
                <div v-else class="no-selection">
                    Выберите мероприятие, чтобы увидеть записи.
                </div>
            </div>
        </main>
    </div>
</template>

<script>
import {Link, useForm} from '@inertiajs/vue3';

export default {
    components: {Link},
    props: {
        user: Object,
        events: Array,
        registrations: Array,
        selectedEventId: [Number, String, null],
    },
    data() {
        return {
            selectedEvent: this.selectedEventId || null,
        };
    },
    setup() {
        const form = useForm({});

        function logout() {
            form.post('/logout', {
                onSuccess: () => {},
            });
        }

        return { form, logout };
    },
    methods: {
        fetchRegistrations() {
            if (this.selectedEvent) {
                console.log('Fetching registrations for event:', this.selectedEvent);
                this.form.get(`/admin?event_id=${this.selectedEvent}`, {
                    preserveState: true,
                    preserveScroll: true,
                    replace: true,
                    onSuccess: (page) => {
                        console.log('Registrations received:', page.props.registrations);
                    },
                    onError: (errors) => {
                        console.log('Error fetching registrations:', errors);
                    },
                });
            }
        },
        formatDate(date) {
            return new Date(date).toLocaleDateString('ru-RU', {
                year: 'numeric',
                month: 'long',
                day: 'numeric',
            });
        },
    },
};
</script>

<style scoped>
/* Стили остаются без изменений */
.page {
    font-family: 'Montserrat Alternates', sans-serif;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.content {
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 20px;
}

.dashboard-container {
    width: 900px;
    background: #ffffff;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    padding: 40px;
}

.header-section {
    text-align: center;
    margin-bottom: 40px;
    position: relative;
}

.dashboard-title {
    color: #1e40af;
    font-size: 32px;
    font-weight: 400;
    margin-bottom: 10px;
}

.welcome-text {
    color: #6b7280;
    font-size: 18px;
    margin: 0;
}

.logout-btn {
    top: 0;
    right: 0;
    height: 40px;
    padding: 0 20px;
    border-radius: 20px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.3s ease, transform 0.2s ease;
    background: #dc2626;
    color: #ffffff;
    border: none;
}

.logout-btn:hover {
    background: #b91c1c;
    transform: translateY(-2px);
}

.event-selector {
    margin-bottom: 30px;
    text-align: center;
}

.select-label {
    font-size: 18px;
    color: #1e40af;
    margin-right: 10px;
}

.event-dropdown {
    padding: 10px;
    font-size: 16px;
    border-radius: 10px;
    border: 1px solid #d1d5db;
    background: #f5f5f5;
    cursor: pointer;
    width: 300px;
}

.registrations-table {
    width: 100%;
    margin-top: 20px;
}

table {
    width: 100%;
    border-collapse: collapse;
    background: #f5f5f5;
    border-radius: 10px;
    overflow: hidden;
}

th, td {
    padding: 15px;
    text-align: left;
    border-bottom: 1px solid #e5e7eb;
}

th {
    background: #1e40af;
    color: #ffffff;
    font-weight: 600;
}

td {
    color: #111827;
}

tr:hover {
    background: #e5e7eb;
}

.no-registrations, .no-selection {
    text-align: center;
    color: #6b7280;
    font-size: 18px;
    margin-top: 20px;
}
</style>
