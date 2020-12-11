<template>
    <div>
        <div class="panel-heading mt-3 text-center d-flex justify-start" id="heading-div">
            <v-img class="mt-1 "
                   max-height="40" max-width="40"
                   src="https://truth.bahamut.com.tw/s01/202010/55d91434a85c09cb5bd76131e2aa6589.PNG?w=1000">
            </v-img>
            <div class="ml-5 ">搜尋</div>
        </div>
        <div class="container mb-12">
        <v-text-field 
            v-model="search"
            append-icon="mdi-magnify"
            background-color="white"
            outlined
            dense
            @keydown.enter="search_by_keyword"
            >
            </v-text-field>
            <v-select
              background-color="white"
              v-model="selected_tag"
              :items="tags"
              chips
              label="以餐廳標籤搜尋"
              solo
              @change="search_by_tag"
            ></v-select>
            <v-row dense>
                <v-col v-for="(item,i) in list" :key="i" :cols="12">
                    <v-card class="mx-auto" max-width="400" @click="selectRest(item.id)">
                        <v-img
                            class="white--text align-end"
                            height="120px"
                            :src="item.img"
                        >

                        </v-img>
                        <v-card-title>{{ item.name }}</v-card-title>
                        <!--<v-card-subtitle class="pb-0">
                            <div>地址:{{item.address}}</div>
                            <div>電話:{{item.phone}}</div>
                            <div>email:{{item.email}}</div>
                        </v-card-subtitle>

                        <v-card-text class="text--primary">
                            <div>Whitehaven Beach</div>
                            <div>Whitsunday Island, Whitsunday Islands</div>
                        </v-card-text>-->
                    </v-card>
                </v-col>
            </v-row>




        </div>
    </div>
</template>

<script>
import {customerGetRestaurantByKeywordAPI,
customerGetRestaurantByTagAPI,restaurantGetAllTagAPI} from "../../api";

export default {
    props: {},
    mounted() {
        this.getRestaurant(this.page)
    },
    data: () => ({
        search:"",
        list:[],
        rest_id:"",
        selected_tag:"",
        tags:["中式","西式"],
        id_tags:{"中式":1,"西式":2},
        page:1,
        resp:"",
    }),
    methods: {
        get_all_tags(){
            let config = {
                Headers: {Authorization: "Bearer " + this.$store.getters.getAccessToken}
            }
            restaurantGetAllTagAPI(config).then((resp)=>{
                



            })
        },
        search_by_keyword(){
            console.log(this.search);
            let config = {
                Params: {"Keyword": this.search},
                Headers: {Authorization: "Bearer " + this.$store.getters.getAccessToken}
            }
            customerGetRestaurantByKeywordAPI(config).then((resp) => 
            {
               


            }).catch((error)=>{
                console.log(error);
            });
        },
        search_by_tag(){
            console.log(this.selected_tag)
            let config = {
                Params: {"tag_id": this.id_tags[this.selected_tag]},
                Headers: {Authorization: "Bearer " + this.$store.getters.getAccessToken}
            }
            customerGetRestaurantByTagAPI(config).then((resp)=>{
                


            }).catch((error)=>{
                console.log(error);
            });

        },
        get_all_rest(){

        },
        selectRest(RID) {
            this.$router.push("/customer/restaurant/" + RID);
        },
        
    },
};
</script>


