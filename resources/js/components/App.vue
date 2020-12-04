<template>
    <v-app id="app">
        <router-view v-on:showSnackBar="showSnackBar"></router-view>
        <v-snackbar v-model="snackbar" :timeout="snackBarTimeout">
            {{ snackbarText }}
            <template v-slot:action="{ attrs }">
                <v-btn color="pink" text v-bind="attrs" @click="snackbar = false"
                >Close
                </v-btn
                >
            </template>
        </v-snackbar>
    </v-app>
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
    },
};
</script>

<style>
.panel-heading {
    font-size: 30px;
    font-weight: bold;
}

#app {
    font-family: "Noto Sans TC", serif;
    background: #30d6f5;
    color: #fff;
}
</style>
