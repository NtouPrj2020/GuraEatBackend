<template>
  <v-card class="mx-auto" max-width="500">
    <v-app-bar
      absolute
      color="white"
      elevate-on-scroll
      scroll-target="#scrolling-techniques-7"
    >
      <v-btn icon @click="RollBack">
        <v-icon>mdi-arrow-left</v-icon>
      </v-btn>
      <v-toolbar-title>歷史訂單</v-toolbar-title>
      <v-spacer></v-spacer>
      <v-btn icon>
        <v-icon>mdi-magnify</v-icon>
      </v-btn>
      <v-btn icon>
        <v-icon>mdi-dots-vertical</v-icon>
      </v-btn>
    </v-app-bar>
    <br />
    <br />
    <br />
    <v-sheet
      id="scrolling-techniques-7"
      class="overflow-y-auto"
      :height="windowSize.y - 100"
    >
      <v-list three-line class="mb-16">
        <v-list-item-group active-class="">
          <v-list-item v-for="order in reverseItems" :key="order.id">
            <v-list-item-avatar>
              <v-img :src="order.img"></v-img>
            </v-list-item-avatar>
            <template>
              <v-list-item-content>
                <v-list-item-title>{{ order.name }}</v-list-item-title>
                <v-list-item-subtitle v-if="order.status in [1, 2]"
                  >進行中訂單
                </v-list-item-subtitle>
              </v-list-item-content>
            </template>
          </v-list-item>
        </v-list-item-group>
      </v-list>
    </v-sheet>
  </v-card>
</template>
<script>
import {
  customerGetAllOrdersAPI,
  customerGetRestaurantByIDAPI,
} from "../../api";
import axios from "axios";

export default {
  data: () => ({
    windowSize: {
      x: 0,
      y: 0,
    },
    config: {},
    orders: [],
  }),
  mounted() {
    this.onResize();
    this.config = {
      headers: {
        Authorization: "Bearer " + this.$store.getters.getAccessToken,
      },
    };
    this.getAllorder();
    console.log(this.orders);
  },
  methods: {
    RollBack() {
      this.$router.push("/customer/home");
    },
    onResize() {
      this.windowSize = { x: window.innerWidth, y: window.innerHeight };
    },
    getAllorder() {
      let order;
      customerGetAllOrdersAPI(this.config)
        .then((resp) => {
          if (resp.status === 200) {
            resp.data.data.order.forEach((order) => {
              customerGetRestaurantByIDAPI({
                params: { ID: order.restaurant_id },
                headers: {
                  Authorization: "Bearer " + this.$store.getters.getAccessToken,
                },
              }).then((resp) => {
                let name = resp.data.data.name;
                let img = resp.data.data.img;
                let str = JSON.stringify(order);
                str = str.substring(0, str.length - 1);
                str = str + ',"name":"' + name + '","img":"' + img + '"}';
                console.log(str);
                order = JSON.parse(str);
                this.orders.push(order);
                console.log(order);
              });
            });
          }
        })
        .catch((err) => {
          console.log(err);
          if (err.response.status === 401) {
            this.$emit("showSnackBar", "error 401");
            console.log(err);
          } else if (err.response.status === 404) {
            this.$emit("showSnackBar", "error 404");
            console.log(err);
          }
          console.log(err);
        });
    },
  },
  computed: {
    reverseItems() {
      return this.orders.reverse();
    },
  },
};
</script>

<style>
</style>
