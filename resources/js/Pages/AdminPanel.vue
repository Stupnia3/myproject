<!-- resources/js/Pages/AdminPanel.vue -->
<template>
    <div class="admin-panel">
        <h1>Панель администратора</h1>
        <p>Добро пожаловать, {{ user.name }}!</p>

        <form @submit.prevent="submit" class="event-form">
            <div class="form-group">
                <label>Заголовок</label>
                <input v-model="form.title" type="text" class="input" :class="{ 'error': errors.title }" />
                <span v-if="errors.title" class="error-text">{{ errors.title }}</span>
            </div>

            <div class="form-group">
                <label>Описание</label>
                <textarea v-model="form.description" class="input" :class="{ 'error': errors.description }"></textarea>
                <span v-if="errors.description" class="error-text">{{ errors.description }}</span>
            </div>

            <div class="form-group">
                <label>Практическая часть</label>
                <div v-for="(part, index) in form.practical_parts" :key="index" class="list-item">
                    <input v-model="form.practical_parts[index]" class="input" />
                    <button type="button" @click="removeItem('practical_parts', index)" class="remove-btn">Удалить</button>
                </div>
                <button type="button" @click="addItem('practical_parts')" class="add-btn">Добавить пункт</button>
                <span v-if="errors.practical_parts" class="error-text">{{ errors.practical_parts }}</span>
            </div>

            <div class="form-group">
                <label>Методики</label>
                <div v-for="(method, index) in form.methodologies" :key="index" class="list-item">
                    <input v-model="form.methodologies[index]" class="input" />
                    <button type="button" @click="removeItem('methodologies', index)" class="remove-btn">Удалить</button>
                </div>
                <button type="button" @click="addItem('methodologies')" class="add-btn">Добавить методику</button>
                <span v-if="errors.methodologies" class="error-text">{{ errors.methodologies }}</span>
            </div>

            <div class="form-group">
                <label>Фото</label>
                <input type="file" @change="handleFileUpload" accept="image/*" class="input-file" />
                <span v-if="errors.photo" class="error-text">{{ errors.photo }}</span>
            </div>

            <div class="form-group">
                <label>Дата начала</label>
                <input v-model="form.start_date" type="date" class="input" :class="{ 'error': errors.start_date }" />
                <span v-if="errors.start_date" class="error-text">{{ errors.start_date }}</span>
            </div>

            <div class="form-group">
                <label>Дата окончания (опционально)</label>
                <input v-model="form.end_date" type="date" class="input" :class="{ 'error': errors.end_date }" />
                <span v-if="errors.end_date" class="error-text">{{ errors.end_date }}</span>
            </div>

            <div class="form-group">
                <label>Число мест</label>
                <input v-model="form.total_seats" type="number" min="1" class="input" :class="{ 'error': errors.total_seats }" />
                <span v-if="errors.total_seats" class="error-text">{{ errors.total_seats }}</span>
            </div>

            <button type="submit" class="btn submit-btn" :disabled="form.processing">Добавить мероприятие</button>
        </form>
    </div>
</template>

<script>
import { useForm } from '@inertiajs/vue3';

export default {
    props: {
        user: Object,
        errors: Object,
    },
    setup() {
        const form = useForm({
            title: '',
            description: '',
            practical_parts: [''],
            methodologies: [''],
            photo: null,
            start_date: '',
            end_date: null,
            total_seats: 1,
        });

        function submit() {
            form.post('/admin/events', {
                onSuccess: () => {
                    form.reset();
                    form.practical_parts = [''];
                    form.methodologies = [''];
                },
                preserveState: true,
            });
        }

        function addItem(field) {
            form[field].push('');
        }

        function removeItem(field, index) {
            if (form[field].length > 1) {
                form[field].splice(index, 1);
            }
        }

        function handleFileUpload(event) {
            form.photo = event.target.files[0];
        }

        return { form, submit, addItem, removeItem, handleFileUpload };
    },
};
</script>

<style scoped>
.admin-panel {
    padding: 40px;
    margin: 0 auto;
    max-width: 1577px;
    width: 100%;
    font-family: 'Montserrat Alternates', sans-serif;
}

h1 {
    color: #1e40af;
    font-size: 32px;
    font-weight: 400;
    margin-bottom: 20px;
    text-align: center;
}

p {
    color: #6b7280;
    font-size: 18px;
    text-align: center;
    margin-bottom: 40px;
}

.event-form {
    display: flex;
    flex-direction: column;
    gap: 20px;
    background: #ffffff;
    padding: 30px;
    border-radius: 20px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

label {
    color: #1e40af;
    font-size: 16px;
    font-weight: 600;
}

.input, .input-file, textarea {
    padding: 10px 15px;
    border: 1px solid #d1d5db;
    border-radius: 10px;
    font-size: 16px;
    font-family: 'Montserrat Alternates', sans-serif;
    transition: border-color 0.3s ease;
}

textarea {
    min-height: 100px;
    resize: vertical;
}

.input:focus, textarea:focus {
    outline: none;
    border-color: #1e40af;
}

.error {
    border-color: #dc2626;
}

.error-text {
    color: #dc2626;
    font-size: 14px;
}

.list-item {
    display: flex;
    gap: 10px;
    align-items: center;
}

.remove-btn {
    background: #dc2626;
    color: #ffffff;
    border: none;
    padding: 5px 10px;
    border-radius: 5px;
    cursor: pointer;
    transition: background 0.3s ease;
}

.remove-btn:hover {
    background: #b91c1c;
}

.add-btn {
    background: #1e40af;
    color: #ffffff;
    border: none;
    padding: 8px 15px;
    border-radius: 10px;
    cursor: pointer;
    transition: background 0.3s ease;
}

.add-btn:hover {
    background: #1e3a8a;
}

.submit-btn {
    background: #1e40af;
    color: #ffffff;
    border: none;
    padding: 12px 20px;
    border-radius: 25px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.3s ease;
}

.submit-btn:hover {
    background: #1e3a8a;
}

.submit-btn:disabled {
    background: #6b7280;
    cursor: not-allowed;
}
</style>
