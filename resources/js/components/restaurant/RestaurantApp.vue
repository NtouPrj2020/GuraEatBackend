<template>
  <div class="container">
    <router-view name="customerView"></router-view>

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
  mounted() {},
  data: () => ({}),
  methods: {
    toHome() {
      this.$router.push("/restaurant/home");
    },
    toInfo() {
      this.$router.push("/restaurant/info");
    },
  },
};
</script>
