<template>
    <div class="page">
        <main class="content">
            <div class="register-container">
                <div class="image-section">
                    <img src="/storage/images/EXPRESS_YOURSELF.jpg" alt="Express Yourself" class="image-placeholder" />
                </div>
                <div class="form-section">
                    <h3 class="form-title">Запись на мероприятие</h3>
                    <form @submit.prevent="submit" class="form">
                        <div v-if="!auth.user" class="input-group">
                            <input v-model="form.name" type="text" class="input" placeholder="Имя" :class="{ 'error': errors.name }"/>
                            <span v-if="errors.name" class="error-text">{{ errors.name }}</span>
                        </div>
                        <div v-if="!auth.user" class="input-group">
                            <input v-model="form.email" type="email" class="input" placeholder="Почта" :class="{ 'error': errors.email }"/>
                            <span v-if="errors.email" class="error-text">{{ errors.email }}</span>
                        </div>
                        <PhoneInput v-model="form.phone" :error="errors.phone" />
                        <div class="input-group">
                            <select v-model="form.event_id" class="input" :class="{ 'error': errors.event_id }">
                                <option value="" disabled>Выберите мероприятие</option>
                                <option v-for="event in events" :key="event.id" :value="event.id">
                                    {{ event.title }}
                                </option>
                            </select>
                            <span v-if="errors.event_id" class="error-text">{{ errors.event_id }}</span>
                        </div>
                        <div class="buttons">
                            <button type="submit" class="btn primary" :disabled="form.processing">
                                Записаться
                            </button>
                            <button type="button" class="btn secondary" @click="$inertia.get('/events')">
                                <Link href="/events">Назад</Link>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</template>

<script>
import { Link, useForm } from '@inertiajs/vue3';
import PhoneInput from '../Components/PhoneInput.vue';

export default {
    components: { Link, PhoneInput },
    props: {
        events: Array,
        eventId: Number,
        errors: Object,
        auth: Object
    },
    setup(props) {
        const form = useForm({
            name: props.auth.user?.name || '',
            email: props.auth.user?.email || '',
            phone: props.auth.user?.phone || '',
            event_id: props.eventId || '',
        });

        function submit() {
            if (!form.event_id) {
                console.error('Event ID is missing');
                return;
            }

            form.post(`/event/${form.event_id}/register`, {
                preserveState: true,
                onSuccess: () => {
                    form.reset('name', 'email', 'phone', 'event_id');
                },
                onError: (errors) => {
                    console.log('Form submission failed:', errors);
                },
            });
        }

        return { form, submit };
    },
};
</script>

<style scoped>
.error {
    border-color: #dc2626;
}

.error-text {
    color: #dc2626;
    font-size: 14px;
    margin-top: 5px;
}

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

.register-container {
    width: 900px;
    background: #f5f5f5;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    display: flex;
}

.image-section {
    flex: 1;
    position: relative;
    min-height: 400px;
    background: #e0e7ff;
}

.image-placeholder {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
    opacity: 0.8;
}

.form-section {
    flex: 1;
    padding: 40px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    background: #ffffff;
}

.form-title {
    color: #1e40af;
    font-size: 24px;
    font-weight: 400;
    margin-bottom: 30px;
    text-align: center;
}

.form {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.input-group {
    display: flex;
    flex-direction: column;
    position: relative;
}

.input {
    height: 50px;
    padding: 0 15px;
    border: 1px solid #d1d5db;
    border-radius: 25px !important;
    font-size: 16px;
    font-family: 'Montserrat Alternates', sans-serif;
    background: #ffffff;
    transition: border-color 0.3s ease;
}

.input:focus {
    outline: none;
    border-color: #1e40af;
}

.buttons {
    display: flex;
    gap: 15px;
    margin-top: 20px;
}

.btn {
    flex: 1;
    height: 50px;
    border-radius: 25px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.3s ease, transform 0.2s ease;
    font-family: 'Montserrat Alternates', sans-serif;
}

.primary {
    background: #1e40af;
    color: #ffffff;
    border: none;
}

.primary:hover {
    background: #1e3a8a;
    transform: translateY(-2px);
}

.secondary {
    background: #ffffff;
    color: #1e40af;
    border: 2px solid #1e40af;
}

.secondary:hover {
    background: #e0e7ff;
    transform: translateY(-2px);
}
</style>
