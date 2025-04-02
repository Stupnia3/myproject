<template>
    <nav class="header" data-aos="fade-down">
        <div class="logo"><Link href="/" class="nav-item"><img src="/storage/images/art-therapy-logo.svg" alt="Logo" /></Link></div>
        <ul class="nav-links">
            <li><Link href="/about" class="nav-item">о нас</Link></li>
            <li>
                <Link
                    v-if="isAuthenticated"
                    :href="route('reviews.create')"
                    class="nav-item"
                >
                    отзывы
                </Link>
                <Link
                    v-else
                    href="/login"
                    class="nav-item"
                >
                    отзывы
                </Link>
            </li>
            <li><Link href="/events" class="nav-item">мероприятия</Link></li>
            <li v-if="isAuthenticated && isAdmin">
                <Link href="/admin" class="nav-item">панель администратора</Link>
            </li>
            <li>
                <Link v-if="!isAuthenticated" href="/login" class="nav-item login">
                    вход
                </Link>
                <button v-else @click="logout" class="nav-item login logout">
                    выход
                </button>
            </li>
        </ul>
    </nav>
    <div class="vectors">
        <div class="vector_head" data-aos="fade-down" data-aos-delay="200"></div>
    </div>
    <div class="vector_heart" data-aos="fade-left" data-aos-delay="400"></div>
</template>

<script>
import { Link, useForm } from '@inertiajs/vue3';

export default {
    components: { Link },
    props: {
        auth: Object,
        csrf_token: String,
    },
    computed: {
        isAuthenticated() {
            return !!this.auth?.user;
        },
        isAdmin() {
            return this.auth?.user?.role === 'admin';
        },
    },
    setup(props) {
        const form = useForm({});

        function logout() {
            if (!props.csrf_token) {
                console.error('CSRF token not provided in props');
                return;
            }

            form.post('/logout', {
                preserveState: false,
                headers: {
                    'X-CSRF-TOKEN': props.csrf_token,
                },
                onSuccess: (page) => {
                    const newToken = page.props.csrf_token;
                    if (newToken) {
                        document.querySelector('meta[name="csrf-token"]').setAttribute('content', newToken);
                        window.axios.defaults.headers.common['X-CSRF-TOKEN'] = newToken;
                    }
                    console.log('Logout successful, page:', page);
                },
                onError: (errors) => {
                    console.error('Logout failed:', errors);
                },
            });
        }

        return { form, logout };
    },
    mounted() {
        this.$nextTick(() => {
            window.dispatchEvent(new Event('aos-init'));
            window.dispatchEvent(new Event('aos'));
        });
    },
};
</script>

<style scoped>
/* Стили остаются без изменений */
.header {
    width: 1577px;
    height: 100px;
    background: rgba(255, 255, 255, 0.16);
    backdrop-filter: blur(19px);
    border-radius: 25px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 55px;
    margin: 20px auto;
    position: relative;
    z-index: 10;
}

.logo {
    width: 55px;
    height: 75px;
}

.nav-links {
    display: flex;
    align-items: center;
    gap: 50px;
    list-style: none;
}

.nav-item {
    color: #edecea;
    font-family: 'Montserrat Alternates', sans-serif;
    font-size: 20px;
    font-weight: 400;
    text-decoration: none;
    transition: color 0.3s ease;
}

.nav-item:hover {
    color: #89b6fa;
}

.login {
    border: 1.5px solid #ffffff;
    padding: 10px 40px;
    border-radius: 30px;
    transition: all 0.3s ease;
}

.login:hover {
    background: rgba(255, 255, 255, 0.2);
    color: #ffffff;
    box-shadow: 0 0 10px rgba(255, 255, 255, 0.3);
}

.logout {
    background: none;
    cursor: pointer;
}

.logout:hover {
    background: rgba(255, 0, 0, 0.2);
    border-color: #ff0000;
    color: #ffffff;
}

.vectors {
    position: absolute;
    width: 100vw;
    height: 100%;
    filter: blur(1vw);
    z-index: -1;
}

.vector_head {
    position: absolute;
    background: url('/storage/images/Vector_head.svg') no-repeat center center;
    background-size: cover;
    width: 110%;
    left: -5%;
    height: 60vh;
    top: -25px;
    opacity: 0;
    transition: opacity 0.5s ease;
}

.vector_heart {
    position: absolute;
    background: url('/storage/images/Heart.svg') no-repeat center center;
    background-size: cover;
    width: 110%;
    left: -5%;
    height: 60vh;
    top: -25px;
    opacity: 0;
    transition: opacity 0.5s ease;
    z-index: -1;
}

@media (max-width: 768px) {
    .header {
        width: 90%;
        padding: 0 20px;
    }

    .nav-links {
        gap: 20px;
    }

    .nav-item {
        font-size: 16px;
    }

    .login {
        padding: 8px 20px;
    }
}
</style>
