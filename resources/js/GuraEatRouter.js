import VueRouter from "vue-router";

export default class GuraEatRouter {
    constructor(vuex) {
        this.store = vuex;
    }
    builds() {
        let routes = [
            {
                path: "/",
                components: {
                    default: require("./components/guest/GuestApp.vue").default
                },
                beforeEnter: (to, from, next) => {
                    if (this.store.getters.getMode == 0) {
                        next({ path: "/guest/login" });
                    } else if (this.store.getters.getMode == 1) {
                        next({ path: "/customer/home" });
                    } else if (this.store.getters.getMode == 2) {
                        next({ path: "/delivery_man/home" });
                    } else if (this.store.getters.getMode == 3) {
                        next({ path: "/restaurant/home" });
                    } else {
                        next();
                    }
                }
            },
            {
                path: "/guest",
                components: {
                    default: require("./components/guest/GuestApp.vue").default
                },
                beforeEnter: (to, from, next) => {
                    if (this.store.getters.getMode == 1) {
                        next({ path: "/customer/home" });
                    } else if (this.store.getters.getMode == 2) {
                        next({ path: "/delivery_man/home" });
                    } else if (this.store.getters.getMode == 3) {
                        next({ path: "/restaurant/home" });
                    } else {
                        next();
                    }
                },
                children: [
                    {
                        path: "login",
                        components: {
                            guestView: require("./components/guest/Login.vue")
                                .default
                        },
                        props: true
                    },
                    {
                        path: "registration",
                        components: {
                            guestView: require("./components/guest/Registration.vue")
                                .default
                        },
                        props: true
                    },
                    {
                        path: "forgotpasswd",
                        components: {
                            guestView: require("./components/guest/ForgotPassword.vue")
                                .default
                        },
                        props: true
                    },
                    {
                        path: "resetpasswd",
                        components: {
                            guestView: require("./components/guest/ResetPassword.vue")
                                .default
                        },
                        props: true
                    }
                ]
            },
            {
                path: "/customer",
                components: {
                    default: require("./components/customer/CustomerApp.vue")
                        .default
                },
                beforeEnter: (to, from, next) => {
                    if (this.store.getters.getMode !== 1) {
                        next({ path: "/guest/login" });
                    } else {
                        next();
                    }
                },
                children: [
                    {
                        path: "home",
                        components: {
                            customerView: require("./components/customer/Home.vue")
                                .default
                        }
                    },
                    {
                        path: "search",
                        components: {
                            customerView: require("./components/customer/Search.vue")
                                .default
                        }
                    },
                    {
                        path: "info",
                        components: {
                            customerView: require("./components/customer/Info.vue")
                                .default
                        }
                    },
                    {
                        path: "restaurant/:id",
                        components: {
                            customerView: require("./components/customer/RestaurantInfo.vue")
                                .default
                        },
                        props: {
                            id: 1
                        }
                    }
                ]
            },
            {
                path: "/delivery_man",
                components: {
                    default: require("./components/deliveryMan/DeliveryManApp.vue")
                        .default
                },
                beforeEnter: (to, from, next) => {
                    if (this.store.getters.getMode !== 2) {
                        next({ path: "/guest/login" });
                    } else {
                        next();
                    }
                },
                children: [
                    {
                        path: "home",
                        components: {
                            deliveryManView: require("./components/deliveryMan/Home.vue")
                                .default
                        }
                    },
                    {
                        path: "info",
                        components: {
                            deliveryManView: require("./components/deliveryMan/Info.vue")
                                .default
                        }
                    }
                ]
            },
            {
                path: "/restaurant",
                components: {
                    default: require("./components/restaurant/RestaurantApp.vue")
                        .default
                },
                beforeEnter: (to, from, next) => {
                    if (this.store.getters.getMode !== 3) {
                        next({ path: "/guest/login" });
                    } else {
                        next();
                    }
                },
                children: [
                    {
                        path: "home",
                        components: {
                            customerView: require("./components/restaurant/Home.vue")
                                .default
                        }
                    },
                    {
                        path: "info",
                        components: {
                            customerView: require("./components/restaurant/Info.vue")
                                .default
                        }
                    }
                ]
            },
            {
                path: "*",
                components: {
                    default: require("./components/404.vue").default
                },
                beforeEnter: (to, from, next) => {
                    if (this.store.getters.getMode == 0) {
                        next({ path: "/guest/login" });
                    } else if (this.store.getters.getMode == 1) {
                        next({ path: "/customer/home" });
                    } else if (this.store.getters.getMode == 2) {
                        next({ path: "/delivery_man/home" });
                    } else if (this.store.getters.getMode == 3) {
                        next({ path: "/restaurant/home" });
                    } else {
                        next();
                    }
                }
            }
        ];
        return routes;
    }
}
