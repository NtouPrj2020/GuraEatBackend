<template>
    <v-card :height="windowSize.y-70" style="background-color: white" class="mb-16">
        <v-toolbar style="flex: 0 0 auto;background-color:#FFFFFF;color:black;" dark class="elevation-0">
            <v-btn icon @click="RollBack" color="black" large>
                <v-icon>close</v-icon>
            </v-btn>
            <v-toolbar-title style="font-size: x-large">評價訂單</v-toolbar-title>
        </v-toolbar>
        <v-card-text>
            <v-container></v-container>
            <v-container></v-container>
            <v-card style="font-size: x-large;text-align: center;"
                    :height="windowSize.y/5" flat>
                <v-row>
                    <v-col>對這次的訂單留下評價吧!</v-col>
                </v-row>
            </v-card>
            <v-card style="font-size: x-large;text-align: center;" :min-height="windowSize.y/4"
                    flat>
                <v-row>
                    <v-col>
                        外送員
                    </v-col>
                </v-row>
                <v-rating
                    v-model="deli_rating"
                    empty-icon="mdi-star-outline"
                    full-icon="mdi-star"
                    background-color="orange lighten-3"
                    color="orange"
                    x-large
                    length="5"
                ></v-rating>
                <v-container></v-container>
                <v-row>
                    <v-col>
                        餐廳
                    </v-col>
                </v-row>
                <v-rating
                    v-model="rest_rating"
                    empty-icon="mdi-star-outline"
                    full-icon="mdi-star"
                    background-color="orange lighten-3"
                    color="orange"
                    x-large
                    length="5"
                ></v-rating>
            </v-card>

            <v-footer
                padless
                fixed
                color="white"
                style="bottom: 56px; max-width: 500px; margin: 0 auto"
            >
                <v-col class="text-center" cols="12">
                    <v-btn
                        color="indigo"
                        class="text-sm-button"
                        @click="sendOut"
                        outlined
                        bottom
                        large
                        block
                    >
                        送出
                    </v-btn>
                </v-col>
            </v-footer>
        </v-card-text>

    </v-card>
</template>
<script>
import {
    customerGetOrderstatusAPI,
    customerSwitchOrderStateAPI,
    customerGiveRateAPI,
} from "../../api";
import axios from "axios";

export default {
    data: () => ({
        windowSize: {
            x: 0,
            y: 0,
        },
        deli_rating: 4,
        rest_rating: 4,
        orderinfoid: 0,
    }),
    mounted() {
        this.onResize();
        this.config = {
            headers: {
                Authorization: "Bearer " + this.$store.getters.getAccessToken,
            },
        };
        this.updateorder();
    },
    methods: {
        onResize() {
            this.windowSize = {x: window.innerWidth, y: window.innerHeight};
        },
        RollBack() {
            this.$router.push("/customer/home");
        },
        changeorderstatus() {
            console.log("changeorderstatus" + this.changestatusmsg);
            customerSwitchOrderStateAPI(this.config, {
                status: 4,
                id: this.orderinfoid,
            })
                .then((resp1) => {
                    this.$emit("showSnackBar", "送出成功");
                    this.RollBack();

                })
                .catch((err) => {
                    console.log(err);
                    if (err.response.status === 401) {
                        this.$emit("showSnackBar", "401error");
                        console.log(err);
                    } else if (err.response.status === 404) {
                        this.$emit("showSnackBar", "404error");
                        console.log(err);
                    }
                    console.log(err);
                });
        },
        updateorder() {
            customerGetOrderstatusAPI(this.config)
                .then((resp) => {

                    if (resp.status === 200) {
                        this.orderinfoid = resp.data.data.id;
                    }
                })
                .catch((err) => {
                    console.log(err);
                    if (err.response.status === 401) {
                        this.$emit("showSnackBar", "401error");
                        console.log(err);
                    } else if (err.response.status === 404) {
                        this.noorder = true;
                        console.log(err);
                    }
                    console.log(err);
                });
        },
        sendOut() {
            let context = this;
            let config = {
                params: {
                    delivery_man_star: this.deli_rating,
                    restaurant_star: this.rest_rating,
                },
                headers: {
                    Authorization: "Bearer " + this.$store.getters.getAccessToken,
                },
            };
            customerGiveRateAPI(config)
                .then(function (response) {
                    context.changeorderstatus();
                    console.log(response);
                    //context.$router.push("/customer/home");
                })
                .catch(function (error) {

                    console.error(error);
                });

        },
    },

};
</script>

<style>
</style>
