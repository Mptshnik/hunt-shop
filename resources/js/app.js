import './bootstrap';
import { createApp } from 'vue'

import router from '/resources/router/index.js'
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap/dist/css/bootstrap.min.css'

import App from "/resources/views/App.vue";

axios.defaults.baseURL = "https://huntshop-student23.ru/api/";


createApp(App).use(router).mount('#app')


import 'bootstrap/dist/js/bootstrap.js'
import axios from "axios";
