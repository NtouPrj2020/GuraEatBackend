<template>
  <div>
    <router-view
      name="deliveryManView"
      v-on:showSnackBar="showSnackBar"
    ></router-view>

    <v-bottom-navigation fixed v-model="value">
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
  data: () => ({}),
  methods: {
    toHome() {
      this.$router.push("/delivery_Man/home");
    },
    toInfo() {
      this.$router.push("/delivery_Man/info");
    },
  },
};
</script>
