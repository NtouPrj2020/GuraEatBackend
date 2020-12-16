<template>
  <div>
    <div id="cusView">
      <router-view
        v-on:showSnackBar="showSnackBar"
        v-on:changefocus="changefocus"
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
      <v-btn value="order" @click="toOrder()">
        <span>訂單</span>

        <v-icon>mdi-format-list-bulleted-square</v-icon>
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
  mounted() {},
  data: () => ({
    value: "",
    snackbar: false,
    snackbarText: "Hello, I'm a snackbar",
    snackBarTimeout: 3000,
  }),
  methods: {
    showSnackBar(text) {
      this.snackbar = true;
      this.snackbarText = text;
    },
    changefocus(value) {
      this.value = value;
    },
    toHome() {
      for (var i = 1; i < 999; i++) window.clearInterval(i);
      this.$router.push("/customer/home");
    },
    toSearch() {
      for (var i = 1; i < 999; i++) window.clearInterval(i);
      this.$router.push("/customer/search");
    },
    toOrder() {
      for (var i = 1; i < 999; i++) window.clearInterval(i);
      this.$router.push("/customer/order");
    },
    toInfo() {
      for (var i = 1; i < 999; i++) window.clearInterval(i);
      this.$router.push("/customer/info");
    },
  },
};
</script>

<style>
#cusView {
  max-width: 500px;
  margin: 0 auto;
}
</style>
