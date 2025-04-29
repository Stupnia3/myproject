<!-- resources/js/Pages/AdminPanel.vue -->
<template>
    <div class="admin-panel">
        <h1>Панель администратора</h1>
        <p>Добро пожаловать, {{ user.name }}!</p>

        <!-- Форма добавления мероприятия -->
        <h2>Добавить мероприятие</h2>
        <form @submit.prevent="submitEvent" class="event-form">
            <div class="form-group">
                <label>Заголовок</label>
                <input v-model="eventForm.title" type="text" class="input" :class="{ 'error': eventForm.errors.title }" />
                <span v-if="eventForm.errors.title" class="error-text">{{ eventForm.errors.title }}</span>
            </div>
            <div class="form-group">
                <label>Описание</label>
                <textarea
                    v-model="eventForm.description"
                    class="input"
                    :class="{ 'error': eventForm.errors.description }"
                ></textarea>
                <span v-if="eventForm.errors.description" class="error-text">{{ eventForm.errors.description }}</span>
            </div>
            <div class="form-group">
                <label>Практическая часть</label>
                <div v-for="(part, index) in eventForm.practical_parts" :key="index" class="list-item">
                    <input v-model="eventForm.practical_parts[index]" class="input" />
                    <button type="button" @click="removeItem('practical_parts', index, eventForm)" class="remove-btn">Удалить</button>
                </div>
                <button type="button" @click="addItem('practical_parts', eventForm)" class="add-btn">Добавить пункт</button>
                <span v-if="eventForm.errors.practical_parts" class="error-text">{{ eventForm.errors.practical_parts }}</span>
            </div>
            <div class="form-group">
                <label>Методики</label>
                <div v-for="(method, index) in eventForm.methodologies" :key="index" class="list-item">
                    <input v-model="eventForm.methodologies[index]" class="input" />
                    <button type="button" @click="removeItem('methodologies', index, eventForm)" class="remove-btn">Удалить</button>
                </div>
                <button type="button" @click="addItem('methodologies', eventForm)" class="add-btn">Добавить методику</button>
                <span v-if="eventForm.errors.methodologies" class="error-text">{{ eventForm.errors.methodologies }}</span>
            </div>
            <div class="form-group">
                <label>Тип мероприятия (можно выбрать несколько)</label>
                <div class="tags-group">
                    <label v-for="tag in availableTags" :key="tag.value" class="tag-label">
                        <input type="checkbox" :value="tag.value" v-model="eventForm.tags" />
                        {{ tag.label }}
                    </label>
                </div>
                <span v-if="eventForm.errors.tags" class="error-text">{{ eventForm.errors.tags }}</span>
            </div>
            <div class="form-group">
                <label>Преподаватели</label>
                <select v-model="eventForm.teachers" multiple class="input">
                    <option v-for="teacher in teachers" :key="teacher.id" :value="teacher.id">
                        {{ teacher.name }}
                    </option>
                </select>
                <span v-if="eventForm.errors.teachers" class="error-text">{{ eventForm.errors.teachers }}</span>
            </div>
            <div class="form-group">
                <label>Место проведения</label>
                <input v-model="eventForm.location" type="text" class="input" :class="{ 'error': eventForm.errors.location }" />
                <span v-if="eventForm.errors.location" class="error-text">{{ eventForm.errors.location }}</span>
            </div>
            <div class="form-group">
                <label>Длительность (в часах)</label>
                <input v-model="eventForm.duration" type="number" min="0" class="input" :class="{ 'error': eventForm.errors.duration }" />
                <span v-if="eventForm.errors.duration" class="error-text">{{ eventForm.errors.duration }}</span>
            </div>
            <div class="form-group">
                <label>Фото</label>
                <input type="file" @change="handleFileUpload" accept="image/*" class="input-file" />
                <span v-if="eventForm.errors.photo" class="error-text">{{ eventForm.errors.photo }}</span>
            </div>
            <div class="form-group">
                <label>Дата начала</label>
                <input v-model="eventForm.start_date" type="date" class="input" :class="{ 'error': eventForm.errors.start_date }" />
                <span v-if="eventForm.errors.start_date" class="error-text">{{ eventForm.errors.start_date }}</span>
            </div>
            <div class="form-group">
                <label>Дата окончания (опционально)</label>
                <input v-model="eventForm.end_date" type="date" class="input" :class="{ 'error': eventForm.errors.end_date }" />
                <span v-if="eventForm.errors.end_date" class="error-text">{{ eventForm.errors.end_date }}</span>
            </div>
            <div class="form-group">
                <label>Число мест</label>
                <input v-model="eventForm.total_seats" type="number" min="1" class="input" :class="{ 'error': eventForm.errors.total_seats }" />
                <span v-if="eventForm.errors.total_seats" class="error-text">{{ eventForm.errors.total_seats }}</span>
            </div>
            <button type="submit" class="btn submit-btn" :disabled="eventForm.processing">Добавить мероприятие</button>
        </form>

        <!-- Форма добавления преподавателя -->
        <h2>Управление преподавателями</h2>
        <form @submit.prevent="submitTeacher" class="teacher-form">
            <div class="form-group">
                <label>Имя преподавателя</label>
                <input v-model="teacherForm.name" type="text" class="input" :class="{ 'error': teacherForm.errors.name }" />
                <span v-if="teacherForm.errors.name" class="error-text">{{ teacherForm.errors.name }}</span>
            </div>
            <div class="form-group">
                <label>Биография</label>
                <textarea
                    v-model="teacherForm.bio"
                    class="input"
                    :class="{ 'error': teacherForm.errors.bio }"
                ></textarea>
                <span v-if="teacherForm.errors.bio" class="error-text">{{ teacherForm.errors.bio }}</span>
            </div>
            <div class="form-group">
                <label>Фото</label>
                <input type="file" @change="handleTeacherFileUpload" accept="image/*" class="input-file" />
                <span v-if="teacherForm.errors.photo" class="error-text">{{ teacherForm.errors.photo }}</span>
            </div>
            <button type="submit" class="btn submit-btn" :disabled="teacherForm.processing">Добавить преподавателя</button>
        </form>

        <!-- Список преподавателей -->
        <div class="teachers-list">
            <h3>Список преподавателей</h3>
            <ul>
                <li v-for="teacher in teachers" :key="teacher.id" class="teacher-item">
                    {{ teacher.name }}
                    <button @click="deleteTeacher(teacher.id)" class="remove-btn">Удалить</button>
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
import { useForm } from '@inertiajs/vue3';

export default {
    props: {
        user: Object,
        errors: Object,
        teachers: Array,
    },
    setup() {
        const eventForm = useForm({
            title: '',
            description: '',
            practical_parts: [''],
            methodologies: [''],
            tags: [],
            teachers: [],
            location: '',
            duration: null,
            photo: null,
            start_date: '',
            end_date: null,
            total_seats: 1,
        });

        const teacherForm = useForm({
            name: '',
            bio: '',
            photo: null,
        });

        return { eventForm, teacherForm };
    },
    data() {
        return {
            availableTags: [
                { label: 'Арт-терапия', value: 'art-therapy' },
                { label: 'Мастер-класс', value: 'master-class' },
                { label: 'Ретрит', value: 'retreat' },
            ],
        };
    },
    methods: {
        submitEvent() {
            this.eventForm.post('/admin/events', {
                onSuccess: () => {
                    this.eventForm.reset();
                    this.eventForm.practical_parts = [''];
                    this.eventForm.methodologies = [''];
                    this.eventForm.tags = [];
                    this.eventForm.teachers = [];
                },
                preserveState: true,
            });
        },
        addItem(field, form) {
            form[field].push('');
        },
        removeItem(field, index, form) {
            if (form[field].length > 1) {
                form[field].splice(index, 1);
            }
        },
        handleFileUpload(event) {
            this.eventForm.photo = event.target.files[0];
        },
        submitTeacher() {
            this.teacherForm.post('/admin/teachers', {
                onSuccess: () => {
                    this.teacherForm.reset();
                },
                preserveState: true,
            });
        },
        handleTeacherFileUpload(event) {
            this.teacherForm.photo = event.target.files[0];
        },
        deleteTeacher(teacherId) {
            if (confirm('Вы уверены, что хотите удалить этого преподавателя?')) {
                this.$inertia.delete(`/admin/teachers/${teacherId}`, {
                    onSuccess: () => {
                        alert('Преподаватель удален!');
                    },
                });
            }
        },
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

h1, h2, h3 {
    color: #1e40af;
    font-weight: 400;
    text-align: center;
}

h1 {
    font-size: 32px;
    margin-bottom: 20px;
}

h2 {
    font-size: 28px;
    margin: 40px 0 20px;
}

h3 {
    font-size: 24px;
    margin-bottom: 15px;
}

p {
    color: #6b7280;
    font-size: 18px;
    text-align: center;
    margin-bottom: 40px;
}

.event-form, .teacher-form {
    display: flex;
    flex-direction: column;
    gap: 20px;
    background: #ffffff;
    padding: 30px;
    border-radius: 20px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    margin-bottom: 40px;
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

.input, .input-file, textarea, select {
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

select[multiple] {
    height: 120px;
}

.input:focus, textarea:focus, select:focus {
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

.tags-group {
    display: flex;
    gap: 15px;
    flex-wrap: wrap;
}

.tag-label {
    display: flex;
    align-items: center;
    gap: 5px;
    font-size: 16px;
    color: #333;
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

.teachers-list {
    background: #ffffff;
    padding: 20px;
    border-radius: 20px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.teachers-list ul {
    list-style: none;
    padding: 0;
}

.teacher-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 0;
    border-bottom: 1px solid #d1d5db;
}

.teacher-item:last-child {
    border-bottom: none;
}

@media (max-width: 768px) {
    .admin-panel {
        padding: 20px;
    }

    h1 {
        font-size: 24px;
    }

    h2 {
        font-size: 20px;
    }

    p {
        font-size: 16px;
    }

    .event-form, .teacher-form {
        padding: 20px;
    }

    label {
        font-size: 14px;
    }

    .input, .input-file, textarea, select {
        font-size: 14px;
    }

    .submit-btn {
        font-size: 14px;
        padding: 10px 15px;
    }
}
</style>
