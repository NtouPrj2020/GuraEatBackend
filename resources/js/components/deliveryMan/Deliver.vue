<template>
  <div>
    <gmap-map
      v-show="map"
      :center="center"
      :zoom="15"
      :options="mapOptions"
      style="width: 100%; height: 1000px"
    >
    </gmap-map>
  </div>
</template>


<script>
import axios from "axios";
import StaticMap from "vue-static-map";

export default {
  name: "StaticMap",
  data: () => ({
    map: false,
  }),
  created() {},
  mounted() {},
  methods: {},
};
</script>
