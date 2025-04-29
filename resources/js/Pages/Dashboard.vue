<!-- resources/js/Pages/Dashboard.vue -->
<template>
    <div class="page">
        <main class="content">
            <div class="dashboard-container">
                <div class="header-section">
                    <h1 class="dashboard-title">Панель администратора</h1>
                    <p class="welcome-text">Добро пожаловать, {{ user.name }}!</p>
                </div>

                <div class="button-group">
                    <button v-if="user.role === 'admin'" class="btn add-event-btn">
                        <Link href="/admin" class="nav-item">Добавить мероприятие</Link>
                    </button>
                    <button class="btn logout-btn" @click="logout">Выйти</button>
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

                <div v-if="registrations.length > 0" class="registrations-table">
                    <table>
                        <thead>
                        <tr>
                            <th>Имя</th>
                            <th>Email</th>
                            <th>Телефон</th>
                            <th>Дата записи</th>
                            <th>Статус</th>
                            <th>Действия</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="registration in registrations" :key="registration.id">
                            <td>{{ registration.name }}</td>
                            <td>{{ registration.email }}</td>
                            <td>{{ registration.phone }}</td>
                            <td>{{ formatDate(registration.created_at) }}</td>
                            <td :class="statusClass(registration.status)">
                                {{ statusText(registration.status) }}
                            </td>
                            <td>
                                <button
                                    class="action-btn confirm-btn"
                                    :disabled="registration.status === 'confirmed'"
                                    @click="confirmAction(registration.id, 'confirm')"
                                >
                                    Подтверждено
                                </button>
                                <button
                                    class="action-btn reject-btn"
                                    :disabled="registration.status === 'rejected'"
                                    @click="confirmAction(registration.id, 'reject')"
                                >
                                    Отклонено
                                </button>
                                <button
                                    class="action-btn delete-btn"
                                    @click="confirmAction(registration.id, 'delete')"
                                >
                                    Удалить
                                </button>
                            </td>
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
import { Link, useForm } from '@inertiajs/vue3';

export default {
    components: { Link },
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
        const form = useForm({
            status: null,
        });

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
                this.form.get(`/dashboard?event_id=${this.selectedEvent}`, {
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
        confirmAction(registrationId, action) {
            const actions = {
                confirm: {
                    message: 'Вы уверены, что хотите подтвердить эту запись?',
                    onConfirm: () => this.updateStatus(registrationId, 'confirmed'),
                },
                reject: {
                    message: 'Вы уверены, что хотите отклонить эту запись?',
                    onConfirm: () => this.updateStatus(registrationId, 'rejected'),
                },
                delete: {
                    message: 'Вы уверены, что хотите удалить эту запись?',
                    onConfirm: () => this.deleteRegistration(registrationId),
                },
            };

            const { message, onConfirm } = actions[action];

            if (confirm(message)) {
                onConfirm();
            }
        },
        updateStatus(registrationId, status) {
            console.log('Updating status:', { registrationId, status });
            this.form.status = status;
            this.form.post(`/admin/registration/${registrationId}/status`, {}, {
                preserveState: true,
                preserveScroll: true,
                onSuccess: () => {
                    console.log('Status updated successfully');
                    this.fetchRegistrations();
                },
                onError: (errors) => {
                    console.log('Error updating status:', errors);
                    alert(errors.status || 'Не удалось обновить статус записи.');
                },
            });
        },
        deleteRegistration(registrationId) {
            console.log('Deleting registration:', registrationId);
            this.form.delete(`/admin/registration/${registrationId}`, {
                preserveState: true,
                preserveScroll: true,
                onSuccess: () => {
                    console.log('Registration deleted successfully');
                    this.fetchRegistrations();
                },
                onError: (errors) => {
                    console.log('Error deleting registration:', errors);
                    alert(errors.message || 'Не удалось удалить запись.');
                },
            });
        },
        statusText(status) {
            const statuses = {
                pending: 'Ожидает',
                confirmed: 'Подтверждено',
                rejected: 'Отклонено',
            };
            return statuses[status] || 'Неизвестно';
        },
        statusClass(status) {
            return {
                'status-pending': status === 'pending',
                'status-confirmed': status === 'confirmed',
                'status-rejected': status === 'rejected',
            };
        },
    },
};
</script>

<style scoped>
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
    width: 100%;
    max-width: 1577px;
    background: #ffffff;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    padding: 40px;
}

.header-section {
    text-align: center;
    margin-bottom: 40px;
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

.button-group {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    margin-bottom: 20px;
}

.add-event-btn, .logout-btn {
    height: 40px;
    padding: 0 20px;
    border-radius: 20px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.3s ease, transform 0.2s ease;
    border: none;
}

.add-event-btn {
    background: #1e40af;
    color: #ffffff;
}

.add-event-btn:hover {
    background: #1e3a8a;
    transform: translateY(-2px);
}

.logout-btn {
    background: #dc2626;
    color: #ffffff;
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

.action-btn {
    padding: 5px 10px;
    margin: 0 5px;
    border-radius: 5px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.3s ease, transform 0.2s ease;
    border: none;
}

.confirm-btn {
    background: #2d6a4f;
    color: #ffffff;
}

.confirm-btn:hover {
    background: #1a4d34;
    transform: translateY(-2px);
}

.confirm-btn:disabled {
    background: #a3bffa;
    cursor: not-allowed;
}

.reject-btn {
    background: #dc2626;
    color: #ffffff;
}

.reject-btn:hover {
    background: #b91c1c;
    transform: translateY(-2px);
}

.reject-btn:disabled {
    background: #a3bffa;
    cursor: not-allowed;
}

.delete-btn {
    background: #ef4444;
    color: #ffffff;
}

.delete-btn:hover {
    background: #dc2626;
    transform: translateY(-2px);
}

.status-pending {
    color: #6b7280;
}

.status-confirmed {
    color: #2d6a4f;
    font-weight: bold;
}

.status-rejected {
    color: #dc2626;
    font-weight: bold;
}
</style>
