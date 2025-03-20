// resources/js/bootstrap.js
import axios from 'axios';

// Устанавливаем CSRF-токен из meta-тега
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
if (csrfToken) {
    axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken;
    axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
} else {
    console.warn('CSRF token not found in meta tag');
}

axios.defaults.xsrfCookieName = 'XSRF-TOKEN';
axios.defaults.xsrfHeaderName = 'X-XSRF-TOKEN';

// Делаем axios доступным глобально
window.axios = axios;
