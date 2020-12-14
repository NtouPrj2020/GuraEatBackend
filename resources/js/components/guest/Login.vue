<template>
  <div>
    <div class="container">
      <v-main>
        <v-card class="mx-auto pt-5" max-width="400" id="login-card">
          <h1 class="panel-heading text-center" id="title">Gura eAt</h1>
          <p class="text-center pt-2">全台首創V-tuber合作外送平台</p>
          <v-form ref="form" v-model="valid" lazy-validation>
            <v-select
              v-model="type"
              :items="items"
              label="身分"
              class="px-14 pt-5"
              outlined
              :rules="typeRules"
            ></v-select>
            <v-text-field
              width="400"
              label="email"
              outlined
              class="px-14"
              v-model="email"
              :rules="emailRules"
            ></v-text-field>
            <v-text-field
              label="password"
              v-model="password"
              type="password"
              outlined
              class="px-14"
              :rules="passwordRules"
            ></v-text-field>
            <div class="container mx-auto text-center">
              <v-btn
                class="mb-5"
                @click="login"
                color="0xFFFF"
                elevation="2"
                :loading="loading"
              >
                登入
              </v-btn>
              <v-btn
                class="ml-2 mb-5"
                @click="registration"
                color="0xFFFF"
                elevation="2"
              >
                註冊
              </v-btn>
            </div>
          </v-form>
        </v-card>
      </v-main>
    </div>
  </div>
</template>

<script>
import {
  customerLoginAPI,
  deliveryManLoginAPI,
  restaurantLoginAPI,
} from "../../api";
import DeviceDetector from "../../detectDevice.js";

export default {
  props: {},
  mounted() {},
  data: () => ({
    valid: true,
    fail: false,
    loading: false,
    email: "",
    emailRules: [
      (v) => !!v || "請填入信箱!",
      (v) => /.+@.+\..+/.test(v) || "請填入正確的信箱!",
    ],
    password: "",
    passwordRules: [(v) => !!v || "請填入密碼!"],
    mode: 0,
    type: null,
    typeRules: [(v) => !!v || "請選擇身分!"],
    items: ["顧客", "外送員", "餐廳"],
  }),
  methods: {
    login() {
      if (!this.$refs.form.validate()) {
        return;
      }
      this.loading = true;
      console.log(this.email);
      console.log(this.password);
      console.log(this.mode);
      console.log(this.type);
      let time = new Date().getTime();
      let device_name = DeviceDetector.detect() + " " + time;
      if (this.type === "顧客") {
        this.mode = 1;
        customerLoginAPI({
          email: this.email,
          password: this.password,
          device_name: device_name,
        })
          .then((resp) => {
            if (resp.status === 200) {
              this.loading = false;
              this.$store.commit("ACCESS_TOKEN", resp.data.data.access_token);
              this.$store.commit("USER_NAME", resp.data.data.name);
              this.$store.commit("MODE", this.mode);
              this.$store.commit("DEVICE_NAME", device_name);
              this.$router.push("/");
            }
            console.log(resp.data);
          })
          .catch((err) => {
            if (err.response.status === 401) {
              this.loading = false;
              this.$emit("showSnackBar", "信箱/密碼錯誤");
              console.log(err);
            } else if (err.response.status === 400) {
              this.loading = false;
              this.$emit("showSnackBar", "未知的錯誤");
              console.log(err);
            }
            console.log(err);
          });
      } else if (this.type === "外送員") {
        this.mode = 2;
        deliveryManLoginAPI({
          email: this.email,
          password: this.password,
          device_name: device_name,
        })
          .then((resp) => {
            if (resp.status === 200) {
              this.loading = false;
              this.$store.commit("ACCESS_TOKEN", resp.data.data.access_token);
              this.$store.commit("USER_NAME", resp.data.data.name);
              this.$store.commit("MODE", this.mode);
              this.$store.commit("DEVICE_NAME", device_name);
              this.$router.push("/");
            }
            console.log(resp.data);
          })
          .catch((err) => {
            if (err.response.status === 401) {
              this.loading = false;
              this.$emit("showSnackBar", "信箱/密碼錯誤");
              console.log(err);
            } else if (err.response.status === 404) {
              this.loading = false;
              this.$emit("showSnackBar", "未知的錯誤");
              console.log(err);
            }
            console.log(err);
          });
      }
      if (this.type === "餐廳") {
        this.loading = false;
        this.mode = 3;
        restaurantLoginAPI({
          //need to change later,don't have api yet
          email: this.email,
          password: this.password,
          device_name: device_name,
        })
          .then((resp) => {
            if (resp.status === 200) {
              this.loading = false;
              this.$store.commit("ACCESS_TOKEN", resp.data.data.access_token);
              this.$store.commit("USER_NAME", resp.data.data.name);
              this.$store.commit("MODE", this.mode);
              this.$store.commit("DEVICE_NAME", device_name);
              this.$router.push("/");
            }
            console.log(resp.data);
          })
          .catch((err) => {
            if (err.response.status === 401) {
              this.$emit("showSnackBar", "信箱/密碼錯誤");
              console.log(err);
            } else if (err.response.status === 404) {
              this.$emit("showSnackBar", "未知的錯誤");
              console.log(err);
            }
            console.log(err);
          });
      }
    },
    registration() {
      this.$router.push("/guest/registration");
    },
  },
};
</script>

<style>
#title {
  font-size: 30px;
}

#login-card {
  top: 30%;
  display: block;
  margin-left: auto;
  margin-right: auto;
}
</style>
