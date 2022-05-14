require('./bootstrap');

import { createApp } from 'vue'
import App from './App.vue'

export const eventBus = createApp(App);

import VueAxios from 'vue-axios';
import * as VueRouter from 'vue-router';
import axios from 'axios';
import { routes } from './routes';

const router = VueRouter.createRouter({
    history: VueRouter.createWebHistory(),
    routes,
});

createApp(App).use(router).use(VueAxios, axios).mount('#app');