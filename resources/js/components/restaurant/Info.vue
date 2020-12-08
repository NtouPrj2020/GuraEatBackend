<template>
  <div>
    <div class="panel-heading text-center">餐廳資訊</div>
    <v-card>
      <v-card-text>餐廳名稱: {{ resName }}</v-card-text>
      <v-card-text>地址: {{ resAddress }}</v-card-text>
      <v-card-text>電話: {{ resPhone }}</v-card-text>
      <v-card-text>電子郵件: {{ resMail }}</v-card-text>
    </v-card>
    <v-row justify="center" class="pa-2">
      <v-btn min-width="200" @click="switch_user">切換使用者身分</v-btn>
    </v-row>
    <v-row justify="center" class="pa-2">
      <v-btn min-width="200" @click="history_order">歷史訂單紀錄</v-btn>
    </v-row>
    <v-row justify="center" class="pa-2">
      <v-btn min-width="200" @click="modify_info">更改餐廳資料</v-btn>
    </v-row>
    <v-row justify="center" class="pa-2">
      <v-btn min-width="200" @click="signout">登出</v-btn>
    </v-row>
  </div>
</template>

<script>
import { customerLoginAPI } from "../../api";
export default {
  props: {},
  mounted() {
    let config = {
      params: { page: this.page },
      headers: {
        Authorization: "Bearer " + this.$store.getters.getAccessToken(),
      },
    };
  },
  data: () => ({
    info: [],
    resName: "",
    resAddress: "",
    resPhone: "",
    resMail: "",
  }),
  methods: {
    switch_user() {},
    history_order() {},
    modify_info() {},
    signout() {
      if (this.$store.getters.getAccessToken != "") {
        this.$store.commit("ACCESS_TOKEN", "");
        this.$store.commit("USER_NAME", "");
        this.$store.commit("MODE", 0);
        this.$router.push("/guest/login");
      } else {
        this.$router.push("/guest/login");
      }
    },
  },
};
</script>
