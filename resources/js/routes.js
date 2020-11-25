import VueRouter from 'vue-router';

let routes = [
    {
        path: '/',
        component: require('./components/AppHome.vue').default,
        props: true,
    },
    {
        path: '/login',
        component: require('./components/guest/Login.vue').default,
        props: true,
    },
    {
        path: '/forgotpasswd',
        component: require('./components/guest/ForgotPassword.vue').default,
        props: true,
    },
    {
        path: '/resetpasswd',
        component: require('./components/guest/ResetPassword.vue').default,
        props: true,
    },
    {
        path: '/customer',
        components: {
            default: require('./components/customer/CustomerApp.vue').default,
        },
        children: [
            {
                path: 'home',
                components: {
                    customerView: require('./components/customer/Home.vue').default,
                }
            },
            {
                path: 'search',
                components: {
                    customerView: require('./components/customer/Search.vue').default,
                }
            },
            {
                path: 'cart',
                components: {
                    customerView: require('./components/customer/Cart.vue').default,
                }
            },
            {
                path: 'info',
                components: {
                    customerView: require('./components/customer/Info.vue').default,
                }
            },
        ]
    },
    {
        path: '*',
        component: require('./components/404.vue').default
    }
];

export default new VueRouter({
    mode: 'history',//因為Vue router 會自動產生hashtag(#)，俗果你覺得礙事可以加入這行。
    linkActiveClass: 'active',
    base: '/',
    routes //ES6語法，當key和value一樣時可省略key
})
