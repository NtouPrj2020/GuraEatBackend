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
        :clickable="true"
        :draggable="true"
        @click="center = m.position"
      />
    </gmap-map>
  </div>
</template>

<script>
export default {
  name: "GoogleMap",
  props: ["mapOptions", "mapStyle", "markers", "center"],
  data: () => ({}),
  mounted() {
    console.log(this.mapOptions);
    console.log(this.mapStyle);
  },

  methods: {},
};
</script>
