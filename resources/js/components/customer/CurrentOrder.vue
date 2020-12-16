<template>
  <div>
    <div id="map">
      <gmap-map
        :center="center"
        :zoom="12"
        :options="mapOptions"
        :style="mapStyle"
        ref="mapRef"
      >
        <GmapMarker
          :key="index"
          v-for="(m, index) in markers"
          :position="m.position"
          :clickable="false"
          :draggable="false"
          :icon="m.icon"
        />
      </gmap-map>
    </div>
    <div class="container mb-15">
      <v-card v-if="noorder" elevation="2" class="mx-1"
        ><v-card-text>目前沒有等待中訂單喔~</v-card-text></v-card
      >
      <v-card elevation="2" class="mx-1" v-else>
        <v-card-text>
          <div class="font-weight-bold ml-8 mb-2">訂單狀態</div>

          <v-timeline align-top dense>
            <v-timeline-item
              v-for="(status, index) in orderstatus"
              :key="index"
              :color="status.color"
              small
            >
              <div class="font-weight-normal">
                <strong>{{ status.status }}</strong>
              </div>
            </v-timeline-item>
          </v-timeline>
        </v-card-text>
        <v-card-text> 餐廳: {{ orderinfo.restaurant.name }} </v-card-text>
        <v-card-text> 送達地址: {{ orderinfo.customer_address }} </v-card-text>
        <v-card-text> 餐點內容: </v-card-text>
        <div v-for="dish in orderinfo.items" :key="dish.id" class="ml-7">
          {{ dish.name }} x {{ dish.amount }}
        </div>
        <v-card-text> 餐點費用: {{ orderinfo.food_price }} </v-card-text>
        <v-card-text> 外送費用: {{ orderinfo.delivery_fee }} </v-card-text>
        <v-card-text>
          總共: {{ orderinfo.food_price + orderinfo.delivery_fee }}
        </v-card-text>
        <v-card-text>
          外送員電話: {{ orderinfo.deliveryMan.phone }}
        </v-card-text>
      </v-card>
    </div>
  </div>
</template>

<script src="https://js.pusher.com/7.0/pusher.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
<script>
import axios from "axios";
import Pusher from "pusher-js";
import { customerGetOrderstatusAPI } from "../../api";

export default {
  props: ["id"],
  data: () => ({
    noorder: true,
    config: {},
    mapStyle:
      "width: " +
      window.innerWidth +
      "px; height: " +
      window.innerHeight +
      "px;",
    mapOptions: { disableDefaultUI: true, clickableIcons: false },
    markers: [
      { position: { lat: 25, lng: 121 }, icon: require("../house.png") },
      { position: { lat: 25, lng: 121 }, icon: require("../scooter.png") },
    ],
    center: { lat: 45.508, lng: -73.587 },
    orderinfo: {},
    orderstatus: [
      {
        status: "已送達",
        color: "grey",
      },
      {
        status: "送餐中",
        color: "grey",
      },
      {
        status: "餐點製作中",
        color: "grey",
      },
    ],
  }),
  created() {},
  mounted() {
    window.setInterval(() => {
      this.update();
    }, 10000);
    this.config = {
      headers: {
        Authorization: "Bearer " + this.$store.getters.getAccessToken,
      },
    };
    this.$emit("changefocus", "order");
    this.onResize();
    this.geolocate();
    this.updateorder();
    this.$emit("showSnackBar", "獲取位置中...請稍後");
    console.log(this.markers);
    console.log(this.markers.length);
    console.log(this.markers[2]);
  },
  methods: {
    update() {
      this.geolocate();
      this.updateorder();
      this.updatebound();
    },
    updatebound() {
      this.$refs.mapRef.$mapPromise.then((map) => {
        console.log(this.markers.length);
        var bounds = new google.maps.LatLngBounds();
        for (let i = 0; i < this.markers.length; i++) {
          bounds.extend(this.markers[i].position);
          console.log("in");
        }

        //now fit the map to the newly inclusive bounds
        if (this.markers.length > 0) map.fitBounds(bounds);
      });
    },
    onResize() {
      this.mapStyle =
        "width:" +
        window.innerWidth +
        "px;  height: " +
        window.innerHeight / 4 +
        "px;";
    },
    geolocate: function () {
      navigator.geolocation.getCurrentPosition((position) => {
        this.center = {
          lat: position.coords.latitude,
          lng: position.coords.longitude,
        };
        console.log(this.center.lat);
        console.log(this.center.lng);
      });
    },
    updateorder() {
      customerGetOrderstatusAPI(this.config)
        .then((resp) => {
          if (resp.status === 200) {
            this.noorder = false;
            console.log(resp.data.data.deliveryMan.latitude);
            // update map markers
            this.orderinfo = resp.data.data;
            if (this.orderinfo.status >= 1) {
              this.orderstatus[2].color = "deep-purple";
            }
            if (this.orderinfo.status >= 2) {
              this.orderstatus[1].color = "deep-purple";
            }
            if (this.orderinfo.status >= 3) {
              this.orderstatus[0].color = "deep-purple";
            }
            const dliverManMarker = {
              lat: parseFloat(resp.data.data.deliveryMan.latitude),
              lng: parseFloat(resp.data.data.deliveryMan.longitude),
            };
            const mapMarkerIcon = require("../scooter.png");
            this.markers[0] = {
              position: dliverManMarker,
              icon: mapMarkerIcon,
            };
            console.log(this.markers);
            navigator.geolocation.getCurrentPosition((position) => {
              const customerMarker = {
                lat: position.coords.latitude,
                lng: position.coords.longitude,
              };
              const mapMarkerIcon = require("../house.png");
              this.markers[1] = {
                position: customerMarker,
                icon: mapMarkerIcon,
              };
            });
          }
        })
        .catch((err) => {
          console.log(err);
          if (err.response.status === 401) {
            this.$emit("showSnackBar", "401error");
            console.log(err);
          } else if (err.response.status === 404) {
            this.noorder = true;
            console.log(err);
          }
          console.log(err);
        });
    },
  },
};
</script>

<style>
</style>