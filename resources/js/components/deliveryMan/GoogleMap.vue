<template>
  <div>
    <gmap-map
      v-model="map"
      :center="center"
      :zoom="14"
      :style="style"
      :options="options"
    >
      <gmap-marker
        :position="position"
        @click="center = position"
      ></gmap-marker>
    </gmap-map>
  </div>
</template>

<script>
export default {
  name: "GoogleMap",
  data: () => ({
    style: "",
    map: true,
    windowSize: {
      x: 0,
      y: 0,
    },
    center: { lat: 45.508, lng: -73.587 },
    position: { lat: 45.508, lng: -73.587 },
    options: { disableDefaultUI: true, clickableIcons: false },
  }),
  mounted() {
    this.geolocate();
    this.onResize();
  },

  methods: {
    onResize() {
      this.style =
        "width:" +
        window.innerWidth +
        "px;  height: " +
        (window.innerHeight - 200) +
        "px;";
    },
    geolocate: function () {
      navigator.geolocation.getCurrentPosition((position) => {
        this.center = {
          lat: position.coords.latitude,
          lng: position.coords.longitude,
        };
        this.position = {
          lat: position.coords.latitude,
          lng: position.coords.longitude,
        };
      });
    },
  },
};
</script>
