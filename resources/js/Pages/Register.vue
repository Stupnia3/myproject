<!-- resources/js/Pages/Register.vue -->
<template>
    <div class="page">
        <main class="content">
            <div class="register-container">
                <div class="image-section">
                    <img src="/storage/images/EXPRESS_YOURSELF.jpg" alt="Express Yourself" class="image-placeholder" />
                </div>
                <div class="form-section">
                    <h3 class="form-title">Регистрация</h3>
                    <form @submit.prevent="submit" class="form">
                        <div class="input-group">
                            <input
                                v-model="form.name"
                                type="text"
                                class="input"
                                placeholder="Имя"
                                :class="{ 'error': errors.name }"
                            />
                            <span v-if="errors.name" class="error-text">{{ errors.name }}</span>
                        </div>
                        <div class="input-group">
                            <input
                                v-model="form.email"
                                type="email"
                                class="input"
                                placeholder="Почта"
                                :class="{ 'error': errors.email }"
                            />
                            <span v-if="errors.email" class="error-text">{{ errors.email }}</span>
                        </div>
                        <div class="input-group password-group">
                            <input
                                v-model="form.password"
                                :type="showPassword ? 'text' : 'password'"
                                class="input"
                                placeholder="Пароль"
                                :class="{ 'error': errors.password }"
                            />
                            <span class="eye-icon" @click="showPassword = !showPassword">
                                <i :class="showPassword ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                            </span>
                            <span v-if="errors.password" class="error-text">{{ errors.password }}</span>
                        </div>
                        <div class="input-group password-group">
                            <input
                                v-model="form.password_confirmation"
                                :type="showConfirmPassword ? 'text' : 'password'"
                                class="input"
                                placeholder="Подтвердите пароль"
                                :class="{ 'error': errors.password_confirmation }"
                            />
                            <span class="eye-icon" @click="showConfirmPassword = !showConfirmPassword">
                                <i :class="showConfirmPassword ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                            </span>
                            <span v-if="errors.password_confirmation" class="error-text">{{ errors.password_confirmation }}</span>
                        </div>
                        <div class="buttons">
                            <button type="submit" class="btn primary" :disabled="form.processing">
                                Зарегистрироваться
                            </button>
                            <button type="button" class="btn secondary" @click="$inertia.get('/login')">
                                <Link href="/login">Есть аккаунт</Link>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</template>

<script>
import { Link, useForm } from "@inertiajs/vue3";

export default {
    components: { Link },
    setup() {
        const form = useForm({
            name: '',
            email: '',
            password: '',
            password_confirmation: '',
        });

        function submit() {
            form.post('/register', {
                onSuccess: () => form.reset('password', 'password_confirmation'),
            });
        }

        return { form, submit };
    },
    data() {
        return {
            showPassword: false,
            showConfirmPassword: false,
        };
    },
    props: {
        errors: Object
    }
}
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

.password-group {
    position: relative;
}

.eye-icon {
    position: absolute;
    right: 15px;
    top: 50%;
    transform: translateY(-50%);
    cursor: pointer;
    color: #6b7280;
    font-size: 18px;
}

.eye-icon:hover {
    color: #1e40af;
}
</style>
