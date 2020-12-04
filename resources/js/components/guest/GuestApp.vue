<template>
    <div id="login-background">
        <div class="panel panel-default">
            <router-view
                name="guestView"
                v-on:showSnackBar="showSnackBar"
            ></router-view>
        </div>
    </div>
</template>
<script>
export default {
    props: {},
    mounted() {

    },
    data: () => ({}),
    methods: {},
};
</script>

<style>
#login-background {
    background-image: url("https://i.imgur.com/BeNcYMc.jpg");
    width: 100%;
    height: 100%;
    background-position: top;
    background-repeat: no-repeat;
    background-size: cover;
}
</style>
