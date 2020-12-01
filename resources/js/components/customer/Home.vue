<template>
    <div>
        <div class="panel-heading text-center">Home</div>
        <div class="container mb-12" >
            <v-row dense>
                <v-col v-for="(item,i) in list" :key="i"  :cols="12">
                    <v-card class="mx-auto" max-width="400" @click="selectRest(item.id)">
                        <v-img
                            class="white--text align-end"
                            height="120px"
                            :src="item.img"
                        >

                        </v-img>
                        <v-card-title>{{item.name}}</v-card-title>
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
import {getRestaurantall} from "../../api";
import axios from "axios";
export default {
    data: () => ({
        page:1,
        list:[],
    }),
    mounted() {
        let config = {
            params:{"page":this.page,},
            headers: {Authorization: "Bearer " + this.$store.getters.getAccessToken}
        };
        getRestaurantall(
            config
        ).then((res) => {
            this.list = res.data.data.data
            console.log(this.list)
        })
            .catch((error) => { console.error(error) })

    },
    methods:{
        selectRest(RID){
            this.$router.push("/customer/restaurant/"+RID);
        }
    }
};
</script>
