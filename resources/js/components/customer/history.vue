<template>
  <v-card class="mx-auto" max-width="500">
    <v-app-bar
      absolute
      color="white"
      elevate-on-scroll
      scroll-target="#scrolling-techniques-7"
    >
      <v-icon large>mdi-history</v-icon>
      <v-toolbar-title>　歷史訂單</v-toolbar-title>
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
          <v-list-item
            v-for="order in reverseItems"
            :key="order.id"
            @click="selectOrder(order)"
          >
            <v-list-item-avatar>
              <v-img :src="order.restaurant.img"></v-img>
            </v-list-item-avatar>
            <v-list-item-content>
              <v-list-item-title>{{ order.restaurant.name }}</v-list-item-title>
              <v-list-item-subtitle>
                總計：{{ order.food_price + order.delivery_fee }}
              </v-list-item-subtitle>
              <v-list-item-subtitle v-if="order.status in [1, 2]"
                >進行中訂單...
              </v-list-item-subtitle>
            </v-list-item-content>
            <v-list-item-action>
              <v-row>
                <v-col>
                  {{ order.created_at.substr(0, 10) }}
                </v-col>
              </v-row>
            </v-list-item-action>
          </v-list-item>
        </v-list-item-group>
      </v-list>
    </v-sheet>
    <v-dialog
      v-model="dialog"
      :fullscreen="true"
      :overlay="false"
      style="max-width: 500px"
      scrollable
    >
      <v-card>
        <v-toolbar style="flex: 0 0 auto" dark class="primary elevation-0">
          <v-btn icon @click.native="dialog = false" dark>
            <v-icon>close</v-icon>
          </v-btn>
          <v-toolbar-title>訂單詳情</v-toolbar-title>
        </v-toolbar>
        <v-card-text>
          <div>
            <v-row dense>
              <v-col cols="4"> 訂單編號： </v-col>
              <v-col class="text-right" cols="8">
                {{ selectODD.id }}
              </v-col>
              <v-col cols="4"> 配送來自： </v-col>
              <v-col class="text-right" cols="8">
                {{ selectResInfo.name }}
              </v-col>
              <v-col cols="4"> 送餐地址： </v-col>
              <v-col class="text-right" cols="8">
                {{ selectODD.customer_address }}
              </v-col>
              <v-col cols="5"> 訂單成立時間： </v-col>
              <v-col class="text-right" cols="7">
                {{ date + " " + time }}
              </v-col>
            </v-row>
          </div>
          <v-divider></v-divider>
          <div class="mt-2 mb-16">
            <v-simple-table fixed-header>
              <template v-slot:default>
                <thead>
                  <tr>
                    <th class="text-left">名稱</th>
                    <th class="text-left">數量</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(item, i) in selectODD.items" :key="i">
                    <td>{{ item.name }}</td>
                    <td>{{ item.amount }}</td>
                  </tr>
                </tbody>
              </template>
            </v-simple-table>
          </div>
          <v-divider></v-divider>
          <div>
            <v-row dense>
              <v-col cols="4"> 餐點費用： </v-col>
              <v-col class="text-right" cols="8">
                ${{ selectODD.food_price }}
              </v-col>
              <v-col cols="4"> 外送費： </v-col>
              <v-col class="text-right" cols="8">
                ${{ selectODD.delivery_fee }}
              </v-col>
              <v-col cols="4"> 總計： </v-col>
              <v-col class="text-right" cols="8">
                ${{ selectODD.food_price + selectODD.delivery_fee }}
              </v-col>
            </v-row>
          </div>
        </v-card-text>
      </v-card>
    </v-dialog>
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
    selectODD: "",
    selectResInfo: "",
    dialog: false,
    date: "",
    time: "",
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
    onResize() {
      this.windowSize = { x: window.innerWidth, y: window.innerHeight };
    },
    getAllorder() {
      let order;
      customerGetAllOrdersAPI(this.config)
        .then((resp) => {
          if (resp.status === 200) {
            resp.data.data.order.forEach((order) => {
              this.orders.push(order);
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
    selectOrder(order) {
      this.dialog = true;
      this.selectODD = order;
      this.selectResInfo = this.selectODD.restaurant;
      this.date = this.selectODD.created_at.substr(0, 10);
      this.time = this.selectODD.created_at.substr(11, 5);
      console.log("this.selectODD");
      console.log(this.selectODD);
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
