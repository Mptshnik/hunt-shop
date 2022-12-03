<template>

    <main>
        <div class="form-container">
            <div>
                <div>
                    <p class="h1 custom-label">
                        Авторизация
                    </p>
                </div>
                <div v-if="error" class="text-danger">Не правильно введён логин или пароль</div>

                <form @submit.prevent="authorize">
                    <div class="form-group mt-1">
                        <label class="form-label custom-label">Логин</label>
                        <input v-model="form.login" type="text" class="form-control" id="login" placeholder="логин">
                    </div>
                    <div class="form-group mt-1">
                        <label class="form-label custom-label">Пароль</label>
                        <input v-model="form.password" type="password" class="form-control" id="password"
                            placeholder="пароль">
                    </div>
                    <button type="submit" class="btn-dark btn form-control mt-5">Войти</button>
                </form>
            </div>
        </div>

    </main>
</template>

<script>
import router from "../../router";
import { reactive } from 'vue';
import axios from "axios";

export default {
    data() {
        let error = '';
        const form = reactive({
            'login': '',
            'password': ''
        });

        return {
            form,
            error
        }
    },
    methods: {
        authorize() {
            axios.post('/login', this.form).then(res => {
                if (res.data.hasOwnProperty('message')) {
                    this.error = res.data.message;
                }
                else {
                    localStorage.setItem('JWT', res.data.token);
                    //axios.defaults.headers.common['Authorization'] = 'Bearer ' + localStorage.getItem('JWT');

                    router.push('/');
                }
            });
        }
    },
    beforeRouteEnter(to, from, next)
    {
        const token = axios.defaults.headers['Authorization'];

        if(token)
        {
            next('/');
        }
        else next();
    }
}
</script>

<style scoped>
.form-container {
    width: 30%;
    margin: 110px auto;
    background-color: #313335;
    padding: 1%;
    border-radius: 15px;
}

.custom-label {
    color: #e2e8f0;
}
</style>
