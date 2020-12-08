require("./bootstrap");
import Vue from "vue";
import Vuetify from "vuetify";
import Vuex from "vuex";
import VueRouter from "vue-router";

import GuraEatRouter from "./GuraEatRouter"
import App from "./components/App.vue";
import "vuetify/dist/vuetify.min.css";
import createPersistedState from "vuex-persistedstate";

Vue.use(Vuex);
Vue.use(Vuetify);
Vue.use(VueRouter);
const vuetify = new Vuetify({
    theme: {
        themes: {
            light: {
                primary: '#3e6d97',
            },
            dark: {}
        }
    },
    icons: {
        iconfont: "fa4" // 'mdi' || 'mdiSvg' || 'md' || 'fa' || 'fa4' || 'faSvg'
    }
});

const store = new Vuex.Store({
    plugins: [createPersistedState()],
    state: {
        user_email: "",
        user_name: "",
        mode: 0, //0guest 1customer 2delivery 3restaurant
        access_token: null,
        device_name: "",
        cstfToken: document
            .querySelector('meta[name="csrf-token"]')
            .getAttribute("content")
    },
    actions: {},
    mutations: {
        USER_EMAIL(state, user_email) {
            state.user_email = user_email;
        },
        USER_NAME(state, user_name) {
            state.user_name = user_name;
        },
        MODE(state, mode) {
            state.mode = mode;
        },
        ACCESS_TOKEN(state, token) {
            state.access_token = token;
        },
        DEVICE_NAME(state, device_name) {
            state.device_name = device_name;
        }
    },
    getters: {
        getMode: state => {
            return state.mode;
        },
        getUserName: state => {
            return state.user_name;
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
});

let route = new GuraEatRouter(store)

let router = new VueRouter({
    mode: "history", //因為Vue router 會自動產生hashtag(#)，俗果你覺得礙事可以加入這行。
    linkActiveClass: "active",
    base: "/",
    routes:route.builds() //ES6語法，當key和value一樣時可省略key
});


router.mode = "html5";

window.Vue = require("vue");

const app = new Vue({
    el: "#app",
    store: store,
    vuetify: vuetify,
    router: router,
    render: h => h(App)
});
