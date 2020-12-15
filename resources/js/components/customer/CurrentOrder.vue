<template>
  <div>
    <div id="map">
      <gmap-map
        :center="center"
        :zoom="14"
        :options="mapOptions"
        :style="mapStyle"
        ref="mapRef"
      >
        <GmapMarker
          :key="index"
          v-for="(m, index) in markers"
          :position="m.position"
          :clickable="true"
          :draggable="true"
          :icon="m.icon"
          @click="center = m.position"
        />
      </gmap-map>
    </div>
    <div class="container mb-15">
      <v-card elevation="2" class="mx-1">
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
      </v-card>
    </div>
  </div>
</template>

<script src="https://js.pusher.com/7.0/pusher.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
<script>
import axios from "axios";
import Pusher from "pusher-js";
import { deliveryManChangeStateAPI, deliveryManGetInfoAPI } from "../../api";

export default {
  props: ["id"],
  data: () => ({
    config: {
      headers: {
        Authorization: "Bearer " + this.$store.getters.getAccessToken,
      },
    },
    mapStyle:
      "width: " +
      window.innerWidth +
      "px; height: " +
      window.innerHeight +
      "px;",
    mapOptions: { disableDefaultUI: true, clickableIcons: false },
    markers: [],
    center: { lat: 45.508, lng: -73.587 },
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
        color: "deep-purple",
      },
    ],
  }),
  created() {},
  mounted() {
    this.$emit("changefocus", "");
    this.onResize();
    this.addMarker();
    this.geolocate();
    this.$refs.mapRef.$mapPromise.then((map) => {
      var bounds = new google.maps.LatLngBounds();
      for (let i = 0; i < this.markers.length; i++) {
        bounds.extend(this.markers[i].position);
      }

      //now fit the map to the newly inclusive bounds
      map.fitBounds(bounds);
    });
    console.log(this.markers);
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
    addMarker() {
      navigator.geolocation.getCurrentPosition((position) => {
        const marker = {
          lat: position.coords.latitude,
          lng: position.coords.longitude,
        };
        const mapMarker = require("./car.png");
        this.markers.push({ position: marker, icon: mapMarker });
      });
      navigator.geolocation.getCurrentPosition((position) => {
        const marker = {
          lat: position.coords.latitude + 0.0005,
          lng: position.coords.longitude + 0.01,
        };
        const mapMarker = require("./car.png");
        this.markers.push({ position: marker, icon: mapMarker });
      });
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
            this.resName = resp.data.data.name;
            this.Img = resp.data.data.resimg;
            console.log(resp.data);
          }
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
    },
  },
};
</script>

<style>
</style>