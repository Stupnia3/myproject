<template>
    <footer class="footer" data-aos="fade-up" data-aos-delay="400">
        <div class="footer-content container">
            <div class="footer-links">
                <div class="footer-links-left">
                    <Link href="/about" class="footer-link">О нас</Link>
                    <Link href="/therapy" class="footer-link">Терапия</Link>
                    <Link href="/events" class="footer-link">Мероприятия</Link>
                    <Link href="/test" class="footer-link">Тест</Link>
                </div>
                <div class="footer-links-right">
                    <Link href="/contacts" class="footer-link">Контакты</Link>
                    <div class="social-icons">
                        <a href="https://vk.com" target="_blank" class="social-icon vk"></a>
                        <a href="https://youtube.com" target="_blank" class="social-icon youtube"></a>
                        <a href="https://telegram.org" target="_blank" class="social-icon telegram"></a>
                    </div>
                </div>
            </div>
            <p class="copyright">© “Название компании” Все права защищены. Работа является выпускным проектом</p>
        </div>
    </footer>
</template>

<script>
import { Link } from '@inertiajs/vue3';

export default {
    components: { Link },
};
</script>

<style scoped>
.footer {
    position: relative;
    width: 100%;
    padding: 40px 0;
    margin: 0;
    background: linear-gradient(to top, #8AB7FB 65%, rgba(138, 183, 251, 0) 100%);
    z-index: 1;
}

.footer-content {
    display: flex;
    padding-top: 30px;
    flex-direction: column;
    justify-content: space-between;
    box-sizing: border-box;
    max-width: 1200px; /* Добавлено для ограничения ширины */
    margin: 0 auto; /* Центрирование */
}

.footer-links {
    display: flex;
    justify-content: space-between;
    width: 100%;
}

.footer-links-left {
    display: flex;
    gap: 60px;
}

.footer-links-right {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 15px;
}

.footer-link {
    color: #EDECEA;
    font-size: 16px;
    text-decoration: none;
    transition: color 0.2s ease;
    font-family: 'Montserrat Alternates', sans-serif; /* Добавлен шрифт */
}

.footer-link:hover {
    color: #FFFFFF;
}

.social-icons {
    display: flex;
    gap: 15px;
}

.social-icon {
    width: 30px;
    height: 30px;
    background: #ffffff;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: transform 0.2s ease;
}

.social-icon:hover {
    transform: scale(1.1);
}

.vk {
    background: url('/storage/images/вк.svg') no-repeat center center;
    background-size: 27px;
}

.youtube {
    background: url('/storage/images/ютуб.svg') no-repeat center center;
    background-size: 27px;
}

.telegram {
    background: url('/storage/images/тг.svg') no-repeat center center;
    background-size: 27px;
}

.copyright {
    color: #6E83AF;
    font-size: 14px;
    text-align: start;
    margin-top: 20px; /* Добавлено для отступа */
    font-family: 'Montserrat Alternates', sans-serif; /* Добавлен шрифт */
}

/* Адаптивность */
@media (max-width: 768px) {
    .footer-content {
        padding: 0 20px;
    }

    .footer-links {
        flex-direction: column;
        gap: 20px;
        text-align: center;
    }

    .footer-links-left {
        flex-direction: column;
        gap: 15px;
    }

    .footer-links-right {
        gap: 10px;
    }

    .copyright {
        text-align: center;
    }
}
</style>
