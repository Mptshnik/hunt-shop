<template>
    <div>
        <header>
            <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
                <div class="container-fluid">
                    <a class="navbar-brand" href="/">HuntShop|Management</a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDarkDropdown" aria-controls="navbarNavDarkDropdown" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div v-if="isLoggedIn" class="collapse navbar-collapse" id="navbarNavDarkDropdown">
                        <ul class="navbar-nav">
                            <li v-for="item in navBarItems" class="nav-item dropdown">
                                <router-link
                                    v-if="item.path && item.roles.includes(userRole) || item.roles.length === 0"
                                    :to="item.path" class="nav-link" aria-current="page">{{ item.title }}
                                </router-link>
                                <a v-if="item.children && item.roles.includes(userRole)"
                                   class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                                   aria-expanded="false">
                                    {{ item.title }}
                                </a>
                                <ul v-if="item.children" class="dropdown-menu dropdown-menu-dark">
                                    <li v-for="child in item.children">
                                        <router-link
                                            v-if="item.roles.includes(userRole) || item.roles.length === 0"
                                            class="dropdown-item" :to="child.path">{{ child.title }}
                                        </router-link>
                                    </li>
                                </ul>
                            </li>
                        </ul>

                        <ul class="navbar-nav ms-auto">
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                                    Аккаунт
                                </a>
                                <ul class="dropdown-menu dropdown-menu-dark">
                                    <li>
                                        <form @submit.prevent="logout" method="get">
                                            <button type="submit" class="dropdown-item">Выйти</button>
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
        </header>
        <div class="mt-5">
            <slot></slot>
        </div>
    </div>
</template>

<script>
import router from "/resources/router";
import axios from "axios";
import navBarItems from "../../../helper/navBarItems";
import {onMounted, reactive, ref} from "vue";

export default {
    setup()
    {
        let role = parseInt(localStorage.getItem('USER_ROLE_ID'));

        const isLoggedIn = ref(false);
        const userRole = ref(role);

        const logout = () =>
        {
            axios.get('/logout');
            localStorage.removeItem('JWT');
            localStorage.removeItem('USER_ROLE_ID');
            isLoggedIn.value = false;

            router.push('/login');
        }

        return{
            isLoggedIn,
            userRole,
            logout,
            navBarItems
        }
    },
    watch:{
        $route: {
            immediate: true,
            handler(to, from) {
                if (this.$route.path === '/login') {
                    this.isLoggedIn = false;
                } else {
                    this.isLoggedIn = true;
                    this.userRole = parseInt(localStorage.getItem('USER_ROLE_ID'));
                }
            }
        }
    }
}
</script>

<style scoped>

.dropdown-menu{
    right: 0;
    left: auto;
}
</style>
