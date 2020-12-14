<template>
  <div>
    <div
      class="panel-heading pt-3 text-center d-flex justify-start pl-8 pr-8"
      id="heading-div"
    >
      <v-img
        class="mt-1"
        max-height="40"
        max-width="40"
        src="https://truth.bahamut.com.tw/s01/202010/55d91434a85c09cb5bd76131e2aa6589.PNG?w=1000"
      >
      </v-img>
      <div class="ml-5">搜尋</div>
    </div>
    <div class="container mb-12">
      <v-text-field
        v-model="search"
        append-icon="mdi-magnify"
        background-color="white"
        outlined
        dense
        @click:append="search_by_keyword"
        @keydown.enter="search_by_keyword"
      >
      </v-text-field>
      <v-select
        background-color="white"
        v-model="selected_tag"
        :items="tag_names"
        chips
        label="以餐廳標籤搜尋"
        solo
        @change="search_by_tag"
      ></v-select>
      
      <v-row dense>
        <v-col v-for="(item, i) in list" :key="i" :cols="12">
          <v-card class="mx-auto ml-3 mr-3" @click="selectRest(item.id)">
            <v-img class="white--text align-end" height="120px" :src="item.img">
            </v-img>
            <v-card-title
              >{{ item.name }}
              <v-spacer></v-spacer>
              {{ item.avg_rate }}<v-icon large>mdi-star</v-icon>
            </v-card-title>
            <v-divider></v-divider>
            <div class="ms-2 mt-2">
              <v-chip
                class="me-2 mb-2"
                v-for="(tag, i) in item.tags"
                :key="i"
                label
              >
                #{{ tag.name }}
              </v-chip>
            </div>
          </v-card>
        </v-col>
      </v-row>
    
    
    </div>
  </div>
</template>

<script>
import {
  customerGetRestaurantByKeywordAPI,
  customerGetRestaurantByTagAPI,
  restaurantGetAllTagAPI,
} from "../../api";

export default {
  props: {},
  mounted() {
    this.$emit("changefocus", "search");
    this.get_all_tags();
  },
  data: () => ({
    search: "",
    rest_id: "",
    selected_tag: "",
    tags: [],
    id_tags: {},
    tag_names : [],
    page: 1,
    resp: "",
    list:[],
    temp:[],
  }),
  methods: {
    forceRerender() {
      //透過更新:key強制重新渲染
      this.temp = this.list[0];
      this.$set(this.list, 0, this.temp);
    },
    get_all_tags() {
      let config = {
        headers: {
          Authorization: "Bearer " + this.$store.getters.getAccessToken,
        },
      };
      restaurantGetAllTagAPI(config)
      .then((resp) => {          
          for (let index = 0; index < resp.data.data.length; index++) {
              const element = resp.data.data[index];
              this.tags[index] = element;
              var name = element.name;
              this.id_tags[name] = element.id;
              this.tag_names.push(element.name);
          }
          console.log(this.id_tags);
      });
    },
    search_by_keyword() {
      if(!this.search){
          return;
      }
      let config = {
        params: { Keyword: this.search },
        headers: {
          Authorization: "Bearer " + this.$store.getters.getAccessToken,
        },
      };
      customerGetRestaurantByKeywordAPI(config)
        .then((resp) => {
            console.log(resp.data.data);
            this.list = resp.data.data;
        })
        .catch((error) => {
          console.log(error);
        });
        this.forceRerender();
    },
    search_by_tag() {
      console.log(this.selected_tag);
      let config = {
        params: { tag_id:this.id_tags[this.selected_tag]},
        headers: {
          Authorization: "Bearer " + this.$store.getters.getAccessToken,
        },
      };
      customerGetRestaurantByTagAPI(config)
        .then((resp) => {
            console.log(resp.data.data);
            this.list = resp.data.data;
        })
        .catch((error) => {
          console.log(error);
        });
        this.forceRerender();
    },
    selectRest(RID) {
      this.$router.push("/customer/restaurant/" + RID);
    },
  },
};
</script>


