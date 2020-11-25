<template>
    <div class="container">
            <router-view name="customerView"></router-view>

            <v-bottom-navigation fixed v-model="value">
                <v-btn value="home" @click="toHome()">
                    <span>首頁</span>

                    <v-icon>mdi-home</v-icon>
                </v-btn>
                <v-btn value="search" @click="toSearch()">
                    <span>搜尋</span>

                    <v-icon>mdi-magnify</v-icon>
                </v-btn>

                <v-btn value="cart" @click="toCart()">
                    <span>購物車</span>

                    <v-icon>mdi-cart</v-icon>
                </v-btn>

                <v-btn value="info" @click="toInfo()">
                    <span>資訊</span>

                    <v-icon>mdi-account-circle-outline</v-icon>
                </v-btn>
            </v-bottom-navigation>
    </div>
</template>

<script>
export default {
    props: {
    },
    mounted() {

    },
    data: () => ({
    }),
    methods: {
        toHome() {
            this.$router.push("/customer/home");
        },
        toSearch() {
            this.$router.push("/customer/search");
        },
        toCart() {
            this.$router.push("/customer/cart");
        },
        toInfo() {
            this.$router.push("/customer/info");
        },
    }
};
</script>
