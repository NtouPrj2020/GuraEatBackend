<template>
  <div>
    <gmap-map
      :center="center"
      :zoom="14"
      :options="mapOptions"
      :style="mapStyle"
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
    <v-btn
      fab
      large
      color="primary"
      fixed
      x-large
      :style="{ left: '50%', transform: 'translateX(-50%)', top: '75%' }"
      :loading="onlineloading"
      :disabled="btndisabled"
      @click="online"
    >
      {{ onlinestr }}
    </v-btn>
  </div>
</template>

<script src="https://js.pusher.com/7.0/pusher.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
<script>
import axios from "axios";
import Pusher from "pusher-js";
import {
  deliveryManChangeStateAPI,
  deliveryManGetInfoAPI,
  deliveryManGetOrderstatusAPI,
  deliveryManSendLocationAPI,
} from "../../api";

export default {
  data: () => ({
    mapStyle:
      "width: " +
      window.innerWidth +
      "px; height: " +
      window.innerHeight +
      "px;",
    mapOptions: { disableDefaultUI: true, clickableIcons: false },
    markers: [
      { position: { lag: 25, lng: 121 }, icon: require("../scooter.png") },
    ],
    center: { lat: 45.508, lng: -73.587 },
    value: "home",
    onlineloading: true,
    onlinestr: "下線",
    btndisabled: false,
  }),
  created() {},
  mounted() {
    window.setInterval(() => {
      this.addMarker();
    }, 10000);
    this.markers = [
      { position: { lag: 25, lng: 121 }, icon: require("../scooter.png") },
    ];
    let that = this;
    this.$emit("changefocus", "home");
    this.addMarker();
    this.geolocate();
    deliveryManGetOrderstatusAPI({
      headers: {
        Authorization: "Bearer " + this.$store.getters.getAccessToken,
      },
    })
      .then((resp) => {
        if (resp.status === 200) {
          this.onlinestr = "送餐中";
          this.btndisabled = true;
          this.onlineloading = false;
        }
      })
      .catch((err) => {
        console.log(err);
      });
    var pusher = new Pusher(process.env.MIX_PUSHER_APP_KEY, {
      cluster: "ap3",
    });
    deliveryManGetInfoAPI({
      headers: {
        Authorization: "Bearer " + this.$store.getters.getAccessToken,
      },
    })
      .then((resp) => {
        if (resp.status === 200) {
          console.log(resp.data.status);
          if (resp.data.data.status === 1) {
            if (this.onlinestr != "送餐中") {
              this.onlinestr = "下線";
              this.onlineloading = false;
            }
            Pusher.logToConsole = true;
            var channel = pusher.subscribe(
              "deliveryman-channel" + resp.data.data.id
            );
            channel.bind(".deliveryman.getorder", function (data) {
              console.log(JSON.stringify(data));
              console.log("訂單收到");
              that.$emit("showSnackBar", "收到訂單!!");
              new Notification("收到訂單!");
            });
          } else {
            this.onlinestr = "上線";
            this.onlineloading = false;
          }
        }
      })
      .catch((err) => {
        console.log(err);
      });
  },
  methods: {
    addMarker() {
      this.geolocate();
      navigator.geolocation.getCurrentPosition((position) => {
        deliveryManSendLocationAPI(
          {
            latitude: position.coords.latitude,
            longitude: position.coords.longitude,
          },
          {
            headers: {
              Authorization: "Bearer " + this.$store.getters.getAccessToken,
            },
          }
        )
          .then((resp) => {
            console.log(resp.status);
          })
          .catch((error) => {
            console.log(error);
          });
        const dliverManMarker = {
          lat: position.coords.latitude,
          lng: position.coords.longitude,
        };
        const mapMarkerIcon = require("../scooter.png");
        this.markers[0] = {
          position: dliverManMarker,
          icon: mapMarkerIcon,
        };
        console.log("done update");
        console.log(this.markers);
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
    onResize() {
      this.windowSize = { x: window.innerWidth, y: window.innerHeight };
    },
    online() {
      this.onlineloading = true;
      let status;
      console.log(this.onlinestr);
      console.log("!!");
      if (this.onlinestr === "上線") {
        status = 1;
      } else {
        status = 0;
      }
      console.log(status);
      let config = {
        headers: {
          Authorization: "Bearer " + this.$store.getters.getAccessToken,
        },
      };
      let data = {
        status: status,
      };
      deliveryManChangeStateAPI(data, config)
        .then((resp) => {
          if (resp.status === 200) {
            this.onlineloading = false;
            if (resp.data.data.status) {
              this.onlinestr = "下線";
            } else {
              this.onlinestr = "上線";
            }
            console.log(resp.data);
            console.log("done");
          }
        })
        .catch((err) => {
          console.log(err);
          if (err.response.status === 401) {
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
