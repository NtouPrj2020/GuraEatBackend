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
        ><v-card-text>目前沒有接到訂單喔~</v-card-text></v-card
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
import {
  deliveryManGetOrderstatusAPI,
  deliveryManSendLocationAPI,
  deliveryManAddressToLocationAPI,
} from "../../api";

export default {
  props: ["id"],
  data: () => ({
    config: {},
    mapStyle:
      "width: " +
      window.innerWidth +
      "px; height: " +
      window.innerHeight +
      "px;",
    mapOptions: { disableDefaultUI: true, clickableIcons: false },
    markers: [
      { position: { lag: 25, lng: 121 }, icon: require("../house.png") },
      { position: { lag: 25, lng: 121 }, icon: require("../scooter.png") },
    ],
    center: { lat: 45.508, lng: -73.587 },
    orderinfo: {},
    noorder: true,
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
      this.updatelocate();
    }, 10000);
    this.config = {
      headers: {
        Authorization: "Bearer " + this.$store.getters.getAccessToken,
      },
    };
    this.$emit("changefocus", "order");
    this.onResize();
    this.updatelocate();
    this.updateorder();
    this.$refs.mapRef.$mapPromise.then((map) => {
      var bounds = new google.maps.LatLngBounds();
      for (let i = 0; i < markers.length; i++) {
        bounds.extend(markers[i].position);
        console.log("in");
      }

      //now fit the map to the newly inclusive bounds
      if (markers.length > 0) map.fitBounds(bounds);
    });
    console.log(this.markers);
    console.log(this.markers.length);
    console.log(this.markers[2]);
  },
  methods: {
    onResize() {
      this.mapStyle =
        "width:" +
        window.innerWidth +
        "px;  height: " +
        window.innerHeight / 4 +
        "px;";
    },
    updatelocate() {
      navigator.geolocation.getCurrentPosition((position) => {
        this.center = {
          lat: position.coords.latitude,
          lng: position.coords.longitude,
        };
        deliveryManSendLocationAPI(
          {
            latitude: position.coords.latitude,
            longitude: position.coords.longitude,
          },
          this.config
        )
          .then((resp) => {
            console.log(resp.status);
          })
          .catch((error) => {
            console.log(error);
          });
      });
    },
    updateorder() {
      deliveryManGetOrderstatusAPI(this.config)
        .then((resp) => {
          if (resp.status === 200) {
            this.noorder = false;
            console.log(resp.data.data.customer.latitude);
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
            let config = {
              params: { address: this.orderinfo.customer_address },
              headers: {
                Authorization: "Bearer " + this.$store.getters.getAccessToken,
              },
            };
            deliveryManAddressToLocationAPI(config)
              .then((resp1) => {
                console.log(resp1.data.results[0].geometry.location.lat);
                const customerMarker = {
                  lat: resp1.data.results[0].geometry.location.lat,
                  lng: resp1.data.results[0].geometry.location.lng,
                };
                const mapMarkerIcon = require("../house.png");
                this.markers[0] = {
                  position: customerMarker,
                  icon: mapMarkerIcon,
                };
              })
              .catch((err) => {
                console.log(err);
                if (err.response.status === 401) {
                  this.$emit("showSnackBar", "401error");
                  console.log(err);
                } else if (err.response.status === 404) {
                  this.$emit("showSnackBar", "404error");
                  console.log(err);
                }
                console.log(err);
              });
            navigator.geolocation.getCurrentPosition((position) => {
              const dliverManMarker = {
                lat: position.coords.latitude,
                lng: position.coords.longitude,
              };
              const mapMarkerIcon = require("../scooter.png");
              this.markers[1] = {
                position: dliverManMarker,
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