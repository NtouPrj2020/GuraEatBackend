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
            :height="windowSize.y-100"

        >
            <v-container class="mt-10" fluid id="container">
                <v-card class="mx-auto mt-5">
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
                            <v-subheader inset>菜單</v-subheader>

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
                                </v-list-item-avatar>

                                <v-list-item-content>
                                    <v-list-item-title v-text="dishes.name"></v-list-item-title>

                                    <v-list-item-subtitle>價格：{{ dishes.price }}</v-list-item-subtitle>
                                </v-list-item-content>

                                <v-list-item-action>
                                    <span @click="overlay = false" class="justify-center align-center">
                                        <v-overlay :value="overlay">
                                           <v-img
                                               :src="menu[index].img"
                                               :max-width="windowSize.x-50"
                                               :max-height="windowSize.y-50"
                                               contain
                                           ></v-img>
                                        </v-overlay>
                                    </span>
                                    <v-row dense>
                                        <v-col>
                                            <v-btn color="orange darken-5"
                                                   @click="increaseNumber(index,dishes.id); $forceUpdate();" icon>
                                                <v-icon>mdi-plus-circle-outline</v-icon>
                                            </v-btn>
                                            <span>{{ order.amount[index] }}</span>
                                            <v-btn color="" @click="decreaseNumber(index,dishes.id); $forceUpdate();"
                                                   icon>
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
        <v-footer padless fixed style="bottom:56px" color="white">
            <v-col
                class="text-center"
                cols="12"
            >
                <v-btn color="indigo" @click="dialog = true;forceRerender();getTotalAmount();" class="text-sm-button"
                       outlined bottom large block>
                    查看購物清單
                </v-btn>
                <v-dialog v-model="dialog" :fullscreen="fullScreen" transition="dialog-bottom-transition"
                          :overlay=false
                          scrollable>
                    <v-card>
                        <v-toolbar style="flex: 0 0 auto;" dark class="primary">
                            <v-btn icon @click.native="dialog = false" dark>
                                <v-icon>close</v-icon>
                            </v-btn>
                            <v-toolbar-title>購物清單</v-toolbar-title>
                            <v-spacer></v-spacer>
                        </v-toolbar>
                        <v-card-text>
                            <div class="mt-2">
                                <v-simple-table
                                    fixed-header
                                    :height="windowSize.y/2"
                                >
                                    <template v-slot:default>
                                        <thead>
                                        <tr>
                                            <th class="text-left">
                                                名稱
                                            </th>
                                            <th class="text-left">
                                                數量
                                            </th>
                                            <th class="text-left">
                                                金額
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr
                                            v-for="(item,i) in order.amount"
                                            :key="i"
                                            v-if="item>0"
                                        >
                                            <td>{{ menu[i].name }}</td>
                                            <td>{{ item }}</td>
                                            <td>${{ item * menu[i].price }}</td>
                                        </tr>
                                        </tbody>
                                    </template>
                                </v-simple-table>
                                <v-divider></v-divider>

                                <v-row class="rounded" style="background-color:#CCEEFF">
                                    <v-col>
                                        <span class="font-weight-bold">餐點費用：</span>
                                    </v-col>
                                    <v-spacer></v-spacer>
                                    <v-col>
                                        ${{ totalAmount }}
                                    </v-col>
                                </v-row>
                                <v-divider></v-divider>
                                <v-row class="rounded" style="background-color:#77DDFF">
                                    <v-col>
                                        <span class="font-weight-bold">外送費：</span>
                                    </v-col>
                                    <v-spacer></v-spacer>
                                    <v-col>
                                        $25
                                    </v-col>
                                </v-row>
                                <v-divider></v-divider>
                                <v-row class="rounded" style="background-color:#CCEEFF">
                                    <v-col>
                                        <span class="font-weight-bold">總計：</span>
                                    </v-col>
                                    <v-spacer></v-spacer>
                                    <v-col>
                                        ${{ totalAmount + 25 }}
                                    </v-col>
                                </v-row>
                                <v-divider></v-divider>
                            </div>
                            <div>
                                <v-footer padless fixed style="bottom:56px" color="white">
                                    <v-col
                                        class="text-center"
                                        cols="12"
                                    >
                                        <v-btn color="indigo" class="text-sm-button" outlined bottom large block>
                                            送出
                                        </v-btn>
                                    </v-col>
                                </v-footer>
                            </div>
                        </v-card-text>
                    </v-card>

                </v-dialog>
            </v-col>
        </v-footer>
    </div>
</template>
<script>
import {getDishByRestaurantID, getRestaurantByID} from "../../api";

export default {
    data: () => ({
            sheet: true,
            windowSize: {
                x: 0,
                y: 0,
            },
            menu: [],
            overlay: false,
            order: {
                id: [],
                amount: [],
            },
            list: [],
            imgTemp: '',
            amountTemp: 0,
            totalAmount: 0,

            dialog: false,
            fullScreen: true,

        }
    ),
    props: ['id'],
    mounted() {
        this.onResize()
        console.log("restaurantID:")
        console.log(this.$route.params.id)
        let config = {
            params: {"ID": this.$route.params.id},
            headers: {Authorization: "Bearer " + this.$store.getters.getAccessToken}
        };
        getRestaurantByID(
            config
        ).then((res) => {
            this.list = res.data.data
            console.log("list")
            console.log(this.list)
            this.getDish()
        })
            .catch((error) => {
                console.error(error)
            })
    },
    computed: {},
    methods: {
        onResize() {
            this.windowSize = {x: window.innerWidth, y: window.innerHeight}
        }
        ,
        getDish() {
            let config = {
                params: {"ID": this.$route.params.id},
                headers: {Authorization: "Bearer " + this.$store.getters.getAccessToken}
            };
            getDishByRestaurantID(
                config
            ).then((res) => {
                this.menu = res.data.data;
                /*for (let dishes = 0; dishes < res.data.data.length; dishes++) {
                    this.menu.push(res.data.data[dishes]);
                }*/
                console.log("res.data")
                console.log(res.data)
                console.log("res.data.data")
                console.log(res.data.data)
                console.log("menu")
                console.log(this.menu)

                for (let i = 0; i < this.menu.length; i++) {
                    this.order.id.push(this.menu[i].id)
                    this.order.amount.push(0)
                }
                console.log("order")
                console.log(this.order)
            })
                .catch((error) => {
                    console.error(error)
                })
        }
        ,

        RollBack() {
            this.$router.push("/customer/home");
        }
        ,
        increaseNumber(index, player) {
            console.log("id")
            console.log(player)
            console.log("index")
            console.log(index)
            this.amountTemp = this.order.amount[index]++
            //this.order.amount.splice(index, 1, this.amountTemp)
            this.$set(this.order.amount, this.index, this.amountTemp)
            //this.order.amount[index]++
            console.log("amount")
            console.log(this.order.amount[index])
        }
        ,
        decreaseNumber(index, player) {
            console.log("id")
            console.log(player)
            if (this.order.amount[index] > 0) {
                this.amountTemp = this.order.amount[index]--
                //this.order.amount.splice(index, 1, this.amountTemp)
                this.$set(this.order.amount, this.index, this.amountTemp)
                //this.order.amount[index]--
            }
            console.log("amount")
            console.log(this.order.amount[index])
        }
        ,
        forceRerender() {  //透過更新:key強制重新渲染
            this.amountTemp = this.order.amount[0]
            this.$set(this.order.amount, 0, this.amountTemp)
        },
        getTotalAmount() {
            this.totalAmount = 0;
            for (let i = 0; i < this.order.id.length; i++) {
                if (this.order.amount[i] > 0) this.totalAmount += this.order.amount[i] * this.menu[i].price;
            }
        },
    }
}
;
</script>

<style>

#container {
    max-width: 400px;
}

</style>
