<template>
  <div>
    <div class="panel-heading text-center">Gura eAt</div>
    <div class="container">
      <v-main>
          </br>
          </br>
        <v-form ref="form" v-model="valid" lazy-validation>
          <v-select
          v-model="type"
            :items="items"
            label="身分"
            class="px-14"
            outlined
            :rules="typeRules"
          ></v-select>
          <v-text-field
            label="email"
            outlined
            class="px-14"
            v-model="email"
            :rules="emailRules"
          ></v-text-field>
          <v-text-field
            label="password"
            v-model="password"
            outlined
            class="px-14"
            :rules="passwordRules"
          ></v-text-field>
          <v-btn
            @click="register"
            color="0xFFFF"
            elevation="2"
            :style="{ left: '50%', transform: 'translateX(-50%)' }"
          >
            註冊
          </v-btn>
        </v-form>
      </v-main>
    </div>
  </div>
</template>

<script>
import { customerLoginAPI } from "../../api";
export default {
  props: {},
  mounted() {},
  data: () => ({
    valid: true,
    email: "",
    emailRules: [
      (v) => !!v || "請填入信箱!",
      (v) => /.+@.+\..+/.test(v) || "請填入正確的信箱!",
    ],
    password: "",
    passwordRules: [(v) => !!v || "請填入密碼!"],
    loading: false,
    type: null,
    typeRules: [(v) => !!v || "請選擇身分!"],
    items: ["顧客", "外送員", "餐廳"],
  }),
  methods: {
    login() {
      this.$refs.form.validate();
      console.log(this.email);
      console.log(this.password);
      customerLoginAPI({
        email: this.email,
        password: this.password,
        device_name: this.email,
      }).then((resp) => {
        if (resp.status === 200) {
          this.$store.commit("ACCESS_TOKEN", resp.data.data.access_token);
        }
        console.log(resp.data);
      });
    },
  },
};
</script>