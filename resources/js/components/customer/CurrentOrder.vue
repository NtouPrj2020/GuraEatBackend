<template>
  <div>
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
        @click="center = m.position"
      />
    </gmap-map>
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
    mapStyle:
      "width: " +
      window.innerWidth +
      "px; height: " +
      window.innerHeight +
      "px;",
    mapOptions: { disableDefaultUI: true, clickableIcons: false },
    markers: [],
    center: { lat: 45.508, lng: -73.587 },
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
        this.markers.push({ position: marker });
      });
      navigator.geolocation.getCurrentPosition((position) => {
        const marker = {
          lat: position.coords.latitude + 0.0005,
          lng: position.coords.longitude + 0.01,
        };
        this.markers.push({ position: marker });
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
  },
};
</script>
