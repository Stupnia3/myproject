<!-- resources/js/Components/PhoneInput.vue -->
<template>
    <div class="input-group">
        <input
            :value="formattedPhone"
            type="tel"
            class="input"
            placeholder="+7 (XXX)-XXX-XX-XX"
            :class="{ 'error': error }"
            @input="handleInput"
            @keydown="restrictInput"
            pattern="\+7 \(\d{3}\)-\d{3}-\d{2}-\d{2}"
            maxlength="18"
        />
        <span v-if="error" class="error-text">{{ error }}</span>
    </div>
</template>

<script>
export default {
    props: {
        modelValue: String,
        error: String,
    },
    emits: ['update:modelValue'],
    data() {
        return {
            rawPhone: this.modelValue ? this.modelValue.replace(/\D/g, '') : '', // Храним только цифры
        };
    },
    computed: {
        formattedPhone() {
            return this.formatPhone(this.rawPhone);
        },
    },
    watch: {
        modelValue(newValue) {
            this.rawPhone = newValue ? newValue.replace(/\D/g, '') : '';
        },
    },
    methods: {
        handleInput(event) {
            let value = event.target.value.replace(/\D/g, ''); // Удаляем все не-цифры

            // Если стёрли всё, разрешаем пустую строку
            if (value.length === 0) {
                this.rawPhone = '';
                this.$emit('update:modelValue', '');
                return;
            }

            // Устанавливаем 7 как первую цифру, если её нет
            if (!value.startsWith('7')) {
                value = '7' + value;
            }

            // Ограничиваем до 11 цифр (7 + 10 цифр номера)
            this.rawPhone = value.slice(0, 11);
            this.$emit('update:modelValue', this.formatPhone(this.rawPhone));
        },
        formatPhone(value) {
            if (!value) return '';
            let digits = value.replace(/\D/g, '');
            if (digits.length === 0) return '';

            let formatted = '+7';
            if (digits.length > 1) {
                formatted += ' (' + digits.slice(1, Math.min(4, digits.length));
            }
            if (digits.length >= 4) {
                formatted += ')' + (digits.length > 4 ? '-' : '');
                formatted += digits.slice(4, Math.min(7, digits.length));
            }
            if (digits.length >= 7) {
                formatted += '-' + digits.slice(7, Math.min(9, digits.length));
            }
            if (digits.length >= 9) {
                formatted += '-' + digits.slice(9, Math.min(11, digits.length));
            }
            return formatted;
        },
        restrictInput(event) {
            const allowedKeys = ['Backspace', 'Tab', 'ArrowLeft', 'ArrowRight', 'Delete'];
            if (!allowedKeys.includes(event.key) && !/\d/.test(event.key)) {
                event.preventDefault();
            }
        },
    },
};
</script>

<style scoped>
.input-group {
    display: flex;
    flex-direction: column;
    position: relative;
}

.input {
    height: 50px;
    padding: 0 15px;
    border: 1px solid #d1d5db;
    border-radius: 25px;
    font-size: 16px;
    font-family: 'Montserrat Alternates', sans-serif;
    background: #ffffff;
    transition: border-color 0.3s ease;
}

.input:focus {
    outline: none;
    border-color: #1e40af;
}

.error {
    border-color: #dc2626;
}

.error-text {
    color: #dc2626;
    font-size: 14px;
    margin-top: 5px;
}
</style>
