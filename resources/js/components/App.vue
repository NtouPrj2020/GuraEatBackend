<template>
    <v-app id="app">
        <router-view v-on:showSnackBar="showSnackBar"></router-view>
    </v-app>
</template>


<script>
export default {
    props: {
    },
    mounted() {
        let that = this;
    },
    data: () => ({
        snackbar: false,
        snackbarText: "Hello, I'm a snackbar",
        snackBarTimeout: 2000,
    }),
    methods: {
        showSnackBar(text) {
            this.snackbar = true;
            this.snackbarText = text;
        },
    }
};
</script>

<style>

.panel-heading {
    font-size: 30px;
    font-weight:bold;
}

#app {
    font-family: 'Noto Sans TC',serif;
    background: #30d6f5;
    color: #fff;
}

</style>
