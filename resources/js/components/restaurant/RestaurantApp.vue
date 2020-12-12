<template>
    <div>
        <div id="resView">
            <router-view
                v-on:showSnackBar="showSnackBar"
                name="restaurantView"
            ></router-view>
        </div>

        <v-snackbar v-model="snackbar" :timeout="snackBarTimeout">
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
            this.$router.push("/restaurant/home");
        },
        toInfo() {
            this.$router.push("/restaurant/info");
        },
    },
};
</script>

<style>
#resView {
    background-color: #477EAE;
    max-width: 500px;
    height: 100%;
    margin: 0 auto;
}
</style>
