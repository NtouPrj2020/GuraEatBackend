<template>
    <div>
        <div id="cusView" >
            <router-view
                v-on:showSnackBar="showSnackBar"
                name="customerView"
            ></router-view>
        </div>

        <v-snackbar v-model="snackbar" :timeout="snackBarTimeout" class="">
            {{ snackbarText }}
            <template v-slot:action="{ attrs }">
                <v-btn color="pink" text v-bind="attrs" @click="snackbar = false"
                >Close
                </v-btn>
            </template>
        </v-snackbar>

        <v-bottom-navigation grow fixed v-model="value">
            <v-btn value="home" @click="toHome()">
                <span>首頁</span>

                <v-icon>mdi-home</v-icon>
            </v-btn>
            <v-btn value="search" @click="toSearch()">
                <span>搜尋</span>

                <v-icon>mdi-magnify</v-icon>
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
    props: {},
    mounted() {
    },
    data: () => ({
        value: "home",
        snackbar: false,
        snackbarText: "Hello, I'm a snackbar",
        snackBarTimeout: 3000,
    }),
    methods: {
        showSnackBar(text) {
            this.snackbar = true;
            this.snackbarText = text;
        },
        toHome() {
            this.$router.push("/customer/home");
        },
        toSearch() {
            this.$router.push("/customer/search");
        },

        toInfo() {
            this.$router.push("/customer/info");
        },
    },
};
</script>

<style>
#cusView {
    background-color: #477EAE;
    max-width: 500px;
    height: 100vh;
    margin: 0 auto;
}
</style>
