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
            @change="changeType"
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
            type="password"
            outlined
            class="px-14"
            :rules="passwordRules"
          ></v-text-field>
          <v-text-field
            label="重新輸入password"
            v-model="repassword"
            type="password"
            outlined
            class="px-14"
            :rules="repasswordRules.concat(passwordConfirmationRule)"
          ></v-text-field>
          <v-text-field
            label="姓名"
            v-model="name"
            outlined
            class="px-14"
            :rules="basicRules"
          ></v-text-field>
          <v-text-field
            label="電話"
            v-model="phone"
            outlined
            class="px-14"
            :rules="basicRules"
          ></v-text-field>
          <v-text-field
            v-bind:label="addressOrLicenseLabel"
            v-model="addressOrlicense"
            outlined
            class="px-14"
            :rules="basicRules"
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
import { customerSignUpAPI, deliveryManSignUpAPI } from "../../api";
export default {
  props: {},
  mounted() {},
  data: () => ({
    valid: true,
    type: null,
    email: "",
    password: "",
    name: "",
    phone: "",
    addressOrlicense: "",
    mode: 0,
    addressOrLicenseLabel: "外送接收地址",
    emailRules: [
      (v) => !!v || "請填入信箱!",
      (v) => /.+@.+\..+/.test(v) || "請填入正確的信箱!",
    ],
    passwordRules: [(v) => !!v || "請輸入密碼!"],
    repassword: "",
    repasswordRules: [(v) => !!v || "請重新輸入密碼!"],
    loading: false,
    typeRules: [(v) => !!v || "請選擇身分!"],
    items: ["顧客", "外送員"],
    basicRules: [(v) => !!v || "請填入資料!"],
  }),
  methods: {
    changeType() {
      if (this.type == "顧客") {
        this.addressOrLicenseLabel = "外送接收地址";
      } else if (this.type == "外送員") {
        this.addressOrLicenseLabel = "車牌";
      }
    },
    register() {
      if (!this.$refs.form.validate()) {
        return;
      }
      this.loading = true;
      console.log(this.email);
      console.log(this.password);
      console.log(this.type);
      if (this.type === "顧客") {
        this.mode = 1;
        customerSignUpAPI({
          name: this.name,
          email: this.email,
          phone: this.phone,
          address: this.addressOrlicense,
          password: this.password,
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
            if (err.response.status === 401) {
              this.$emit("showSnackBar", "顧客帳號已註冊");
              console.log(err);
            } else if (err.response.status === 400) {
              this.$emit("showSnackBar", "未知的錯誤");
              console.log(err);
            }
            console.log(err);
          });
      } else if (this.type === "外送員") {
        this.mode = 1;
        deliveryManSignUpAPI({
          name: this.name,
          email: this.email,
          phone: this.phone,
          license_id: this.addressOrlicense,
          password: this.password,
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
            if (err.response.status === 401) {
              this.$emit("showSnackBar", "外送員帳號已註冊");
              console.log(err);
            } else if (err.response.status === 400) {
              this.$emit("showSnackBar", "未知的錯誤");
              console.log(err);
            }
            console.log(err);
          });
      }
    },
  },
  computed: {
    passwordConfirmationRule() {
      return () => this.password === this.repassword || "密碼不一致!";
    },
  },
};
</script>