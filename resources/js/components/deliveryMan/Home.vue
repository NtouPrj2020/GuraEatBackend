<template>
    <div>
        <google-map/>
        <v-btn @click="online">上線</v-btn>
    </div>
</template>

<script src="https://js.pusher.com/7.0/pusher.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
<script>
import axios from "axios";
import GoogleMap from "./GoogleMap";
import Pusher from "pusher-js";
import {deliveryManChangeStateAPI, deliveryManGetInfoAPI} from "../../api";

export default {
    components: {
        GoogleMap: GoogleMap,
    },
    data: () => ({}),
    created() {
    },
    mounted() {
        var pusher = new Pusher(process.env.MIX_PUSHER_APP_KEY, {
            cluster: "ap3",
        });
        deliveryManGetInfoAPI({
            headers: {
                Authorization: "Bearer " + this.$store.getters.getAccessToken,
            },
        })
            .then((resp) => {
                if (resp.status === 200) {
                    Pusher.logToConsole = true;
                    var channel = pusher.subscribe("deliveryman-channel"+resp.data.data.id);
                    channel.bind("deliveryman.getorder", function (data) {
                        console.log(JSON.stringify(data));
                        console.log("訂單收到")
                        this.$emit("showSnackBar", "收到訂單!!");
                    });
                }
            })
            .catch((err) => {
                console.log(err);
            });


    },
    methods: {
        online() {
            let config = {
                headers: {
                    Authorization: "Bearer " + this.$store.getters.getAccessToken,
                },
            };
            let data = {
                status: 1,
            };
            deliveryManChangeStateAPI(data, config)
                .then((resp) => {
                    if (resp.status === 200) {
                        console.log(resp.data);
                        console.log("done");
                    }
                })
                .catch((err) => {
                    console.log(err);
                    if (err.response.status === 401) {
                        console.log(err);
                    } else if (err.response.status === 404) {
                        console.log(err);
                    }
                    console.log(err);
                });
        },
    },
};
</script>
