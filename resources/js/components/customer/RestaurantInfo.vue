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

            <v-toolbar-title>{{ windowSize }}</v-toolbar-title>

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
            <v-container class="my-14" fluid>
                <v-card>
                    <v-img
                        src="https://images2.imagebam.com/e9/55/c6/3de1be1336122756.jpg"
                        class="white--text align-end"
                        height="200px"
                    >
                        <v-card-title>
                            <v-chip class="text-h4 pa-5" label>{{ list[0].name }}</v-chip>
                        </v-card-title>
                    </v-img>

                </v-card>

                <v-row dense>
                    <v-col><!--v-for="(item,i) in list[0]" :key="i" -->

                        <v-list
                            subheader
                            two-line
                        >
                            <v-subheader inset>MENU</v-subheader>

                            <v-list-item
                                v-for="dishes in menu"
                                :key="menu.id"
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
                                    <!--<v-btn icon>
                                        <v-icon color="grey lighten-1">mdi-information</v-icon>
                                    </v-btn>-->
                                    <v-row dense>
                                        <v-col>
                                            <v-btn color="orange darken-5" @click="increaseScore(player_one)" icon>
                                                <v-icon>mdi-plus-circle-outline</v-icon>
                                            </v-btn>
                                            <span>{{ player_one.score }}</span>
                                            <v-btn color="" @click="decreaseScore(player_one)" icon>
                                                <v-icon>mdi-minus-circle-outline</v-icon>
                                            </v-btn>
                                        </v-col>
                                    </v-row>
                                </v-list-item-action>
                            </v-list-item>

                            <v-divider inset></v-divider>

                            <v-subheader inset>Files</v-subheader>

                            <v-list-item
                                v-for="folder in folders"
                                :key="folder.title"
                            >
                                <v-list-item-avatar class="rounded">
                                    <v-img
                                        alt="85195367_p1.jpg avatar"
                                        src="https://images2.imagebam.com/e9/55/c6/3de1be1336122756.jpg"
                                    ></v-img>
                                </v-list-item-avatar>

                                <v-list-item-content>
                                    <v-list-item-title v-text="folder.title"></v-list-item-title>

                                    <v-list-item-subtitle v-text="folder.subtitle"></v-list-item-subtitle>
                                </v-list-item-content>

                                <v-list-item-action>
                                    <!--<v-btn icon>
                                        <v-icon color="grey lighten-1">mdi-information</v-icon>
                                    </v-btn>-->
                                    <v-row dense>
                                        <v-col>
                                            <v-btn color="orange darken-5" @click="increaseScore(player_one)" icon>
                                                <v-icon>mdi-plus-circle-outline</v-icon>
                                            </v-btn>
                                            <span>{{ player_one.score }}</span>
                                            <v-btn color="" @click="decreaseScore(player_one)" icon>
                                                <v-icon>mdi-minus-circle-outline</v-icon>
                                            </v-btn>
                                        </v-col>
                                    </v-row>
                                </v-list-item-action>
                            </v-list-item>
                        </v-list>
                    </v-col>
                </v-row>
            </v-container>
        </v-sheet>
    </div>
</template>
<script>
import {getDishByRestaurantID, getRestaurantByID} from "../../api";
import axios from "axios";

export default {
    data: () => ({
        windowSize: {
            x: 0,
            y: 0,
        },
        arr: [],
        page: 1,
        overlay: false,
        menu: [],
        list: [],
        player_one: {
            score: 0
        },
        player_two: {
            score: 0
        },
        files: [
            {
                color: 'blue',
                icon: 'mdi-clipboard-text',
                subtitle: 'Jan 20, 2014',
                title: 'Vacation itinerary',
            },
            {
                color: 'amber',
                icon: 'mdi-gesture-tap-button',
                subtitle: 'Jan 10, 2014',
                title: 'Kitchen remodel',
            },
        ],
        folders: [
            {
                subtitle: 'Jan 9, 2014',
                title: 'Photos',
            },
            {
                subtitle: 'Jan 17, 2014',
                title: 'Recipes',
            },
            {
                subtitle: 'Jan 28, 2014',
                title: 'Work',
            },
        ],
    }),
    props: ['id'],
    mounted() {
        this.onResize()
        console.log(this.$route.params.id)
        let data = {}
        let config = {
            params: {"ID": this.$route.params.id},
            headers: {Authorization: "Bearer " + this.$store.getters.getAccessToken}
        };
        getRestaurantByID(
            config
        ).then((res) => {
            this.list = res.data.data
            this.getDish(this.page)
            console.log(this.list[0])
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
                params: {"page": page,},
                headers: {Authorization: "Bearer " + this.$store.getters.getAccessToken}
            };
            getDishByRestaurantID(
                config
            ).then((res) => {
                if (page === 1) {
                    this.loop(res.data.data.last_page)
                }
                for (let i = 0; i < res.data.data.data.length; i++) {
                    if (res.data.data.data[i].restaurant_id === this.list[0].id) {
                        this.menu.push(res.data.data.data[i])
                    }

                }
                console.log("menu")
                console.log(this.menu)
            })
                .catch((error) => {
                    console.error(error)
                })
        },
        loop(last_page) {
            if (last_page !== 1) {
                for (let i = 2; i <= last_page; i++) {
                    this.getDish(i);
                }
            }
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
        addcart(arr) {
            let data = {
                "list": this.list,
            }

        }
    }
};
</script>
