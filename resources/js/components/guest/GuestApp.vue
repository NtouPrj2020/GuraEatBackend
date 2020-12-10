<template>
  <div id="login-background">
    <div class="panel panel-default">
      <router-view
        v-on:showSnackBar="showSnackBar"
        name="guestView"
      ></router-view>
      <v-snackbar v-model="snackbar" :timeout="snackBarTimeout">
        {{ snackbarText }}
        <template v-slot:action="{ attrs }">
          <v-btn color="pink" text v-bind="attrs" @click="snackbar = false"
            >Close
          </v-btn>
        </template>
      </v-snackbar>
    </div>
  </div>
</template>
<script>
export default {
  props: {},
  mounted() {},
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
#login-background {
  background-image: url("https://i.imgur.com/BeNcYMc.jpg");
  width: 100%;
  height: 100%;
  background-position: top;
  background-repeat: no-repeat;
  background-size: cover;
}
</style>
