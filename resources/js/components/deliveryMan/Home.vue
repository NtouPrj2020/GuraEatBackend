<template>
    <div>
        <div class="panel-heading text-center">Home</div>
        <div class="container mb-12">
            <div class="map-container border rounded">
            <ul class="nav justify-content-center border-bottom">
                <!--營運地區 nav-->
            </ul>
            <!--地圖呈現在此-->

            <div class="google-map" id="map"></div>
            </div> 
        </div>
    </div>
</template>


<script async defer
    src = "https://maps.googleapis.com/maps/api/js?key=AIzaSyBDZY9NYVWhECAAg2Xge62uNSEqE3Qv3MQ">

import axios from "axios";

export default {
    data: () => ({
        map_data:{
            map:null,
            lat: 25.0325917,
            lng: 121.5624999,
        }
    }),
    created(){
        
    },
    mounted() {
        this.initMap();
        console.log(this.map_data);

    },
    methods: {
        initMap(){
      // 透過 Map 物件建構子建立新地圖 map 物件實例，並將地圖呈現在 id 為 map 的元素中
      this.map_data.map = new google.maps.Map(document.getElementById("map"), {
        // 設定地圖的中心點經緯度位置
        center: { lat: this.map_data.lat, lng: this.map_data.lng },
        // 設定地圖縮放比例 0-20
        zoom: 15,
        // 限制使用者能縮放地圖的最大比例
        maxZoom: 20,
        // 限制使用者能縮放地圖的最小比例
        minZoom: 3,
        // 設定是否呈現右下角街景小人
        streetViewControl: false,
        // 設定是否讓使用者可以切換地圖樣式：一般、衛星圖等
        mapTypeControl: false
      });
    },
    },

};
</script>
<style scoped>
.google-map {
  width: 100%;
  height: 100%;
}
</style>