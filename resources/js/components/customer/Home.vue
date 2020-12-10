<template>
    <div>
        <div class="panel-heading mt-3 text-center d-flex justify-start" id="heading-div">
            <v-img class="mt-1 "
                   max-height="40" max-width="40"
                   src="https://truth.bahamut.com.tw/s01/202010/55d91434a85c09cb5bd76131e2aa6589.PNG?w=1000">
            </v-img>
            <div class="ml-5 ">Gura eAt</div>
        </div>
        <div class="container mb-12">
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
import {customerGetAllRestaurantAPI} from "../../api";
import axios from "axios";

export default {
    data: () => ({
        page: 1,
        list: [],
    }),
    mounted() {
        this.getRestaurant(this.page)
    },
    methods: {
        selectRest(RID) {
            this.$emit("showSnackBar","test")
            this.$router.push("/customer/restaurant/" + RID);
        },
        getRestaurant(page){
            let config = {
                params: {"page": page,},
                headers: {Authorization: "Bearer " + this.$store.getters.getAccessToken}
            };
            customerGetAllRestaurantAPI(
                config
            ).then((res) => {
                if(page === 1){
                    this.loop(res.data.data.last_page)
                }
                for(let i =0;i<res.data.data.data.length;i++){
                    this.list.push(res.data.data.data[i])
                }
                console.log(this.list)
            })
                .catch((error) => {
                    console.error(error)
                })
        },
        loop(last_page){
            if(last_page !== 1){
                for(let i = 2;i<=last_page;i++){
                    this.getRestaurant(i);
                }
            }
        },

    },

};
</script>

<style>
#heading-div{
    max-width: 400px;
    margin: 0 auto;
}
</style>
