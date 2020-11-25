require('./bootstrap');
import Vue from 'vue'
import Vuetify from 'vuetify';
import Vuex from 'vuex';
import VueRouter from 'vue-router'

import router from './routes';
import App from './components/App.vue';
import 'vuetify/dist/vuetify.min.css'
import createPersistedState from "vuex-persistedstate";

Vue.use(Vuex);
Vue.use(Vuetify);
Vue.use(VueRouter)
const vuetify = new Vuetify({
    theme: {
        themes: {
            light: {},
            dark: {},
        },
    },
    icons: {
        iconfont: 'fa4', // 'mdi' || 'mdiSvg' || 'md' || 'fa' || 'fa4' || 'faSvg'
    },
})

const store = new Vuex.Store({
    plugins: [createPersistedState()],
    state: {
        user_email: "",
        mode: 2, //0admin 1user 2guest
        access_token: null,
        device_name: "",
        cstfToken: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    },
    actions: {},
    mutations: {
        USER_EMAIL(state, user_email) {
            state.user_email = user_email;
        },
        MODE(state, mode) {
            state.mode = mode;
        },
        ACCESS_TOKEN(state, token) {
            state.access_token = token;
        },
        DEVICE_NAME(state, device_name) {
            state.device_name = device_name;
        },
    },
    getters: {
        getMode: state => {
            return state.mode;
        },
        getAccessToken: state => {
            return state.access_token;
        },
        getUserEmail: state => {
            return state.user_email;
        },
        getDeviceName: state => {
            return state.device_name;
        },
        getcsrfToken: state => {
            return state.cstfToken;
        }
    }
})


router.mode = 'html5';

window.Vue = require('vue');

const app = new Vue({
    el: '#app',
    store: store,
    vuetify: vuetify,
    router: router,
    render: h => h(App),
});
