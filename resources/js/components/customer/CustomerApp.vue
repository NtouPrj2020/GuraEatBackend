<template>
  <div>
    <router-view
      name="customerView"
      v-on:showSnackBar="showSnackBar"
    ></router-view>

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
      value: 'home',
  }),
  methods: {
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
