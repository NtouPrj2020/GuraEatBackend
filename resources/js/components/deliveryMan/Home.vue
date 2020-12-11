
<template>
  <div>
    <google-map />
  </div>
</template>

<script src="https://js.pusher.com/7.0/pusher.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
<script>
import axios from "axios";
import GoogleMap from "./GoogleMap";

Pusher.logToConsole = true;

var pusher = new Pusher(process.env.PUSHER_APP_KEY, {
  cluster: "ap3",
});

var channel = pusher.subscribe("deliveryman-channel");
channel.bind("deliveryman.getorder", function (data) {
  console.log(JSON.stringify(data));
  this.$emit("showSnackBar", "收到訂單!!");
});

export default {
  components: {
    GoogleMap: GoogleMap,
  },
  data: () => ({}),
  created() {},
  mounted() {},
  methods: {},
};
</script>
