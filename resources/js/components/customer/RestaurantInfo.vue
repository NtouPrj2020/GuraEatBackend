<template>
    <div>
        <v-app-bar
            absolute
            color="white"
            hide-on-scroll
            scroll-target="#scrolling-technique"
        >
            <v-btn icon @click="RollBack">
                <v-icon>mdi-arrow-left</v-icon>
            </v-btn>

            <v-toolbar-title>{{ list.name }}</v-toolbar-title>

            <v-spacer></v-spacer>

            <v-btn icon>
                <v-icon>mdi-dots-vertical</v-icon>
            </v-btn>
        </v-app-bar>
        <v-sheet
            id="scrolling-technique"
            class="overflow-y-auto"
            :height="windowSize.y"

        >
            <v-container class="my-14"   fluid id="container">
                <v-card class="mx-auto mt-5" >
                    <v-img
                        :src="list.img"
                        class="white--text align-end"
                        height="200px"
                    >
                        <v-card-title>
                            <v-chip class="text-h4 pa-5" label>{{ list.name }}</v-chip>
                        </v-card-title>
                    </v-img>

                </v-card>

                <v-row dense>
                    <v-col><!--v-for="(item,i) in list[0]" :key="i" -->

                        <v-list
                            subheader
                            two-line
                        >
                            <v-subheader inset>{{ list.tags[0].name }}</v-subheader>

                            <v-list-item
                                v-for="(dishes,index) in menu"
                                :key="index"
                            >
                                <v-list-item-avatar class="rounded">
                                    <v-img
                                        :alt="dishes.name"
                                        :src="dishes.img"
                                        @click="overlay = !overlay"
                                    ></v-img>
                                    <span @click="overlay = false" class="justify-center align-center">
                                        <v-overlay :value="overlay">
                                        <v-img
                                            :alt="dishes.name"
                                            :src="dishes.img"
                                            :max-width="windowSize.x-50"
                                            :max-height="windowSize.y-50"
                                            contain
                                        >
                                        </v-img>
                                    </v-overlay>
                                    </span>

                                </v-list-item-avatar>

                                <v-list-item-content>
                                    <v-list-item-title v-text="dishes.name"></v-list-item-title>

                                    <v-list-item-subtitle>價格：{{ dishes.price }}</v-list-item-subtitle>
                                </v-list-item-content>

                                <v-list-item-action>
                                    <v-row dense>
                                        <v-col>
                                            <v-btn color="orange darken-5" @click="increaseScore(player_one)" icon>
                                                <v-icon>mdi-plus-circle-outline</v-icon>
                                            </v-btn>
                                            <span>{{ player_one.score }}</span>
                                            <v-btn color="" @click="decreaseScore(player_two)" icon>
                                                <v-icon>mdi-minus-circle-outline</v-icon>
                                            </v-btn>
                                        </v-col>
                                    </v-row>
                                </v-list-item-action>
                            </v-list-item>

                            <v-divider></v-divider>
                        </v-list>
                    </v-col>
                </v-row>
            </v-container>
        </v-sheet>
    </div>
</template>
<script>
import {getDishByRestaurantID, getRestaurantByID} from "../../api";

export default {
    data: () => ({
        windowSize: {
            x: 0,
            y: 0,
        },
        arr: [],
        page: 1,
        overlay: false,
        menu: [
            {
                id:1,
                amount:0
            },
            {
                id:2,
                amount:0
            }
        ],
        list: [],
        order:{
            id:[],
            name:[],
        },
        player_one: {
            score: 0
        },
        player_two: {
            score: 0
        },
        isActive:"v-btn--active",
    }),
    props: ['id'],
    mounted() {
        this.onResize()
        console.log(this.$route.params.id)
        let config = {
            params: {"ID": this.$route.params.id},
            headers: {Authorization: "Bearer " + this.$store.getters.getAccessToken}
        };
        getRestaurantByID(
            config
        ).then((res) => {
            this.list = res.data.data
            console.log(res.data.data)

        })
            .catch((error) => {
                console.error(error)
            })
    },
    methods: {
        onResize() {
            this.windowSize = {x: window.innerWidth, y: window.innerHeight}
        },
        getDish(page) {
            let config = {
                params: {"ID": this.$route.params.id},
                headers: {Authorization: "Bearer " + this.$store.getters.getAccessToken}
            };
            getDishByRestaurantID(
                config
            ).then((res) => {
                console.log("data")
                console.log(res.data)
                this.menu=res.data.data;
                /*for (let dishes = 0; dishes < res.data.data.length; dishes++) {
                    this.menu.push(res.data.data[dishes]);
                }*/
                console.log("menu")
                console.log(this.menu)
                console.log(res.data.data.length)
                for(let i =0;i<this.menu;i++){
                    this.menu[i].amount =0
                }
            })
                .catch((error) => {
                    console.error(error)
                })
        },

        RollBack() {
            this.$router.push("/customer/home");
        },
        increaseScore(player) {
            player.score += 1;
        },
        decreaseScore(player) {
            if (player.score > 0) {
                player.score -= 1;
            }
        },

    }
};
</script>

<style>

#container{
    max-width: 400px;
}

</style>
