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
            @click="login"
            color="0xFFFF"
            elevation="2"
            :style="{ left: '50%', transform: 'translateX(-50%)' }"
          >
            登入
          </v-btn>
        </v-form>
      </v-main>
    </div>
  </div>
</template>

<script>
import { customerLoginAPI, deliveryManLoginAPI } from "../../api";
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
    loading: false,
    type: null,
    typeRules: [(v) => !!v || "請選擇身分!"],
    items: ["顧客", "外送員", "餐廳"],
  }),
  methods: {
    login() {
      this.$refs.form.validate();
      this.loading = true;
      console.log(this.email);
      console.log(this.password);
      console.log(this.mode);
      console.log(this.type);
      if (this.type === "顧客") {
        this.loading = false;
        this.mode = 1;
        setTimeout(() => 500); //test
        customerLoginAPI({
          email: this.email,
          password: this.password,
          device_name: this.email,
        })
          .then((resp) => {
            if (resp.status === 200) {
              this.$store.commit("ACCESS_TOKEN", resp.data.data.access_token);
              this.$store.commit("USER_NAME", resp.data.data.access_token);
              this.$store.commit("MODE", this.mode);
            }
            console.log(resp.data);
          })
          .catch((err) => {
            this.$emit("showSnackBar", "信箱/密碼錯誤");
            console.log(err);
          });
      } else if (this.type === "外送員") {
        this.loading = false;
        this.mode = 2;
        deliveryManLoginAPI({
          email: this.email,
          password: this.password,
          device_name: this.email,
        })
          .then((resp) => {
            if (resp.status === 200) {
              this.$store.commit("ACCESS_TOKEN", resp.data.data.access_token);
              this.$store.commit("USER_NAME", resp.data.data.access_token);
              this.$store.commit("MODE", this.mode);
            }
            console.log(resp.data);
          })
          .catch((err) => {
            this.$emit("showSnackBar", "信箱/密碼錯誤");
            console.log(err);
          });
      }
      if (this.type === "餐廳") {
        this.loading = false;
        this.mode = 3;
        customerLoginAPI({
          //need to change later,don't have api yet
          email: this.email,
          password: this.password,
          device_name: this.email,
        })
          .then((resp) => {
            if (resp.status === 200) {
              this.$store.commit("ACCESS_TOKEN", resp.data.data.access_token);
              this.$store.commit("USER_NAME", resp.data.data.access_token);
              this.$store.commit("MODE", this.mode);
            }
            console.log(resp.data);
          })
          .catch((err) => {
            this.$emit("showSnackBar", "信箱/密碼錯誤");
            console.log(err);
          });
      }
    },
  },
};
</script>
