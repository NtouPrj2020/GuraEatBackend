<template>
  <div>
    <google-map
      :mapStyle="mapStyle"
      :mapOptions="mapOptions"
      :center="center"
    />
    <v-btn
      fab
      dark
      large
      color="primary"
      fixed
      x-large
      :style="{ left: '50%', transform: 'translateX(-50%)', top: '75%' }"
      :loading="onlineloading"
      @click="online"
    >
      上線
    </v-btn>
  </div>
</template>

<script src="https://js.pusher.com/7.0/pusher.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
<script>
import axios from "axios";
import GoogleMap from "./GoogleMap";
import Pusher from "pusher-js";
import { deliveryManChangeStateAPI, deliveryManGetInfoAPI } from "../../api";

export default {
  components: {
    GoogleMap: GoogleMap,
  },
  data: () => ({
    mapStyle:
      "width: " +
      window.innerWidth +
      "px; height: " +
      window.innerHeight +
      "px;",
    mapOptions: { disableDefaultUI: true },
    markers: [],
    center: { lat: 45.508, lng: -73.587 },
    value: "home",
    onlineloading: false,
  }),
  created() {},
  mounted() {
    let that = this;
    this.$emit("changefocus", "home");
    this.addMarker();
    this.geolocate();
    console.log(this.markers);
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
          if (resp.data.status === 1) {
            if (false) {
              this.$router.push("/delivery_man/home");
            }
            online();
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
        }
      })
      .catch((err) => {
        console.log(err);
      });
  },
  methods: {
    addMarker() {
      navigator.geolocation.getCurrentPosition((position) => {
        const marker = {
          lat: position.coords.latitude,
          lng: position.coords.longitude,
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
      });
    },
    onResize() {
      this.windowSize = { x: window.innerWidth, y: window.innerHeight };
    },
    online() {
      this.onlineloading = true;
      let config = {
        headers: {
          Authorization: "Bearer " + this.$store.getters.getAccessToken,
        },
      };
      let data = {
        status: 1,
      };
      deliveryManChangeStateAPI(data, config)
        .then((resp) => {
          if (resp.status === 200) {
            this.onlineloading = false;
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
