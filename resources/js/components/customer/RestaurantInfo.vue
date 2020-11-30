<template>
    <div>
        <div class="panel-heading text-center">Home</div>
        <div class="container mb-12" >
          info
        </div>
    </div>
</template>
<script>
import {getRestaurantall} from "../../api";
import axios from "axios";
export default {
    data: () => ({
        menu:[],
    }),
    props:['id'],
    mounted() {
        console.log(this.id)
        let data= {}
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
            this.$router.push("/customer/home");
        }
    }
};
</script>
