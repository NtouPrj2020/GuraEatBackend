<template>
  <div>
    <v-container style="background-color: white; height: 85vh; width: 100vw">
      <google-map :mapOptions="mapOptions" :mapStyle="mapStyle" />
    </v-container>
  </div>
</template>

<script src="https://js.pusher.com/7.0/pusher.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
<script>
import axios from "axios";
import GoogleMap from "./GoogleMap";
import Pusher from "pusher-js";
import {
  deliveryManChangeStateAPI,
  deliveryManGetInfoAPI,
  deliveryManSendLocationAPI,
} from "../../api";

export default {
  components: {
    GoogleMap: GoogleMap,
  },
  props: ["id"],
  data: () => ({
    onlineloading: false,
    mapOptions: { disableDefaultUI: false, clickableIcons: true },
    mapStyle: "",
  }),
  created() {},
  mounted() {
    this.$emit("changefocus", "order");
    this.onResize();
    console.log(this.id);
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
  },
};
</script>
