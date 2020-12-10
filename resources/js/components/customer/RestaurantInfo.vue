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
                            <span @click="overlay = false" class="justify-center align-center">
                                        <v-overlay :value="overlay">
                                           <v-img
                                               :src="imgTemp"
                                               :max-width="windowSize.x-50"
                                               :max-height="windowSize.y-50"
                                               contain
                                           ></v-img>
                                        </v-overlay>
                                    </span>
                            <v-list-item
                                v-for="(dishes,index) in menu"
                                :key="index"
                            >
                                <v-list-item-avatar class="rounded">
                                    <v-img
                                        :alt="dishes.name"
                                        :src="dishes.img"
                                        @click="storeImg(dishes.img)"
                                    ></v-img>
                                </v-list-item-avatar>

                                <v-list-item-content>
                                    <v-list-item-title v-text="dishes.name"></v-list-item-title>

                                    <v-list-item-subtitle>價格：{{ dishes.price }}</v-list-item-subtitle>
                                </v-list-item-content>

                                <v-list-item-action>

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
                <v-btn color="indigo" @click="dialog = true;forceRerender();getTotalAmount();cDeliveryTime();"
                       class="text-sm-button"
                       outlined bottom large block>
                    查看購物清單
                </v-btn>
                <v-dialog v-model="dialog" :fullscreen="fullScreen" transition="dialog-bottom-transition"
                          :overlay=false
                          scrollable>
                    <v-card>
                        <v-toolbar style="flex: 0 0 auto;" dark class="primary elevation-0">
                            <v-btn icon @click.native="dialog = false" dark>
                                <v-icon>close</v-icon>
                            </v-btn>
                            <v-toolbar-title>購物清單</v-toolbar-title>
                            <v-spacer></v-spacer>
                        </v-toolbar>
                        <v-card-text>
                            <div class="mt-2 mb-16">
                                <v-simple-table
                                    fixed-header
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
                                <v-container></v-container>
                                <v-row class="rounded mx-1" style="background-color:#CCEEFF">
                                    <v-col cols="9">
                                        <span class="font-weight-bold">餐點費用：</span>
                                    </v-col>
                                    <v-spacer></v-spacer>
                                    <v-col cols="3">
                                        ${{ totalAmount }}
                                    </v-col>
                                </v-row>
                                <v-row class="rounded mx-1" style="background-color:#77DDFF">
                                    <v-col cols="9">
                                        <span class="font-weight-bold">外送費：</span>
                                    </v-col>
                                    <v-spacer></v-spacer>
                                    <v-col cols="3">
                                        ${{ deliveryFee }}
                                    </v-col>
                                </v-row>
                                <v-row class="rounded mx-1" style="background-color:#CCEEFF">
                                    <v-col cols="9">
                                        <span class="font-weight-bold">總計：</span>
                                    </v-col>
                                    <v-spacer></v-spacer>
                                    <v-col cols="3">
                                        ${{ order.totalAmount }}
                                    </v-col>
                                </v-row>
                                <v-container></v-container>
                                <v-card
                                    color="#CCEEF"
                                >
                                    <v-toolbar
                                        flat
                                        color="#26c6da"
                                    >
                                        <v-icon>mdi-fish</v-icon>
                                        <v-toolbar-title class="font-weight-light">
                                            送餐資訊
                                        </v-toolbar-title>
                                        <v-spacer></v-spacer>
                                        <v-btn
                                            color="orange"
                                            fab
                                            small
                                            @click="EditInfo"
                                        >
                                            <v-icon v-if="isEditing" @click="cDeliveryTime();">
                                                mdi-close
                                            </v-icon>
                                            <v-icon v-else>
                                                mdi-account-edit-outline
                                            </v-icon>
                                        </v-btn>
                                    </v-toolbar>
                                    <v-card-text>
                                        <!--<v-text-field
                                            :disabled="!isEditing"
                                            label="取餐姓名"
                                            v-model="CustomerInfo.name"
                                            hint="例如: 魚餌,小蝦,有機物"
                                            :rules="[rules.required]"
                                            counter="10"
                                            maxlength="10"
                                            outlined
                                            persistent-hint
                                        ></v-text-field>-->
                                        <v-text-field
                                            :disabled="!isEditing"
                                            label="取餐地址"
                                            v-model="CustomerInfo.address"
                                            hint="例如: 基隆市中正區北寧路2號"
                                            :rules="[rules.required]"
                                            counter="40"
                                            maxlength="40"
                                            outlined
                                            persistent-hint
                                            append-icon="mdi-target"
                                            @click:append="geoFindMe"
                                        ></v-text-field>
                                        <v-text-field
                                            :disabled="!isEditing"
                                            label="備註"
                                            v-model="CustomerInfo.note"
                                            hint="有什麼事想要告訴外送員嗎? A"
                                            counter="40"
                                            maxlength="40"
                                            outlined
                                            persistent-hint
                                        ></v-text-field>
                                    </v-card-text>
                                    <v-divider></v-divider>
                                    <v-card-actions>
                                        <v-row>
                                            <v-col>
                                                <v-btn-toggle
                                                    mandatory
                                                >
                                                    <v-btn small @click="Type=0;isSelectingTime=true;date='';time='';">
                                                        盡快送達
                                                    </v-btn>
                                                    <v-btn small @click="Type=1;isSelectingTime=false;getCurrentTime()">
                                                        選擇預定時間
                                                    </v-btn>

                                                </v-btn-toggle>
                                            </v-col>
                                            <v-col cols="6">
                                                <v-menu
                                                    v-model="menu2"
                                                    :close-on-content-click="false"
                                                    :nudge-right="40"
                                                    transition="scale-transition"
                                                    offset-y
                                                    min-width="290px"
                                                >
                                                    <template v-slot:activator="{ on, attrs }">
                                                        <v-text-field
                                                            :disabled="isSelectingTime"
                                                            v-model="date"
                                                            label="日期"
                                                            prepend-icon="event"
                                                            readonly
                                                            v-bind="attrs"
                                                            v-on="on"
                                                        ></v-text-field>
                                                    </template>
                                                    <v-date-picker
                                                        v-model="date"
                                                        @input="menu2 = false"
                                                        no-title
                                                        :min="dayNow"
                                                        :max="dayConstrain"
                                                        locale="zh-cn"
                                                        :first-day-of-week="0"></v-date-picker>
                                                </v-menu>
                                            </v-col>
                                            <v-col cols="6">
                                                <v-menu
                                                    v-model="menu1"
                                                    :close-on-content-click="false"
                                                    :nudge-right="40"
                                                    transition="scale-transition"
                                                    offset-y
                                                    min-width="290px"
                                                >
                                                    <template v-slot:activator="{ on, attrs }">
                                                        <v-text-field
                                                            :disabled="isSelectingTime"
                                                            v-model="time"
                                                            label="時間"
                                                            prepend-icon="mdi-clock-time-four-outline"
                                                            readonly
                                                            v-bind="attrs"
                                                            v-on="on"
                                                        ></v-text-field>
                                                    </template>
                                                    <v-time-picker
                                                        v-model="time"
                                                        @input="menu1 = false"
                                                        format="24hr"
                                                        :min="(date===dayNow)?timeConstrain:''"
                                                    ></v-time-picker>
                                                </v-menu>
                                            </v-col>
                                            <v-col cols="6">
                                                預計最快送達時間：
                                            </v-col>
                                            <v-col cols="5" class="rounded text-center"
                                                   style="background-color: #ccf2ff">
                                                {{ maxMakingTime }} 分鐘
                                            </v-col>
                                        </v-row>

                                    </v-card-actions>
                                </v-card>
                            </div>
                            <div>
                                <v-footer padless fixed color="white">
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
        <div class="text-center">
            <v-dialog
                v-model="isCorrectDialog"
                width="500"
            >

                <v-card>
                    <v-card-title class="headline grey lighten-2">
                        地址確認
                    </v-card-title>

                    <v-card-text>
                        請問"{{ this.tempAddress }}"是您希望送達的地址嗎?
                    </v-card-text>

                    <v-divider></v-divider>

                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn
                            color="primary"
                            text
                            @click="CustomerInfo.address=tempAddress;isCorrectDialog = false;"
                        >
                            是
                        </v-btn>
                        <v-btn
                            color="primary"
                            text
                            @click="isCorrectDialog = false"
                        >
                            否
                        </v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>
        </div>
    </div>

</template>
<script>
import {
    customerGetAllDishByRestaurantIDAPI,
    customerGetRestaurantByIDAPI,
    customerGetDeliveryTimeIDAPI,
    customerlocationToAddressAPI
} from "../../api";

export default {
    data: () => ({
            sheet: true,
            windowSize: {
                x: 0,
                y: 0,
            },
            menu: [],
            menu1: false,
            menu2: false,
            overlay: false,
            order: {
                id: [],
                amount: [],
                totalAmount: 0,
            },
            totalAmount: 0,
            deliveryFee: 25,
            MakingTime: 0,
            durationTime: {
                text: 0,
                value: 0,
            },
            maxMakingTime: 0,
            list: [],
            imgTemp: '',
            amountTemp: 0,


            dialog: false,
            fullScreen: true,
            rules: {
                required: value => !!value || '不可為空',
            },
            CustomerInfo: {
                address: '基隆市中正區北寧路2號',
                note: '',
            },
            tempAddress: '',
            isEditing: null,
            Type: 0,
            isSelectingTime: true,
            isCorrectDialog: false,
            date: '',
            time: '',
            dayConstrain: '',
            timeConstrain: '',
            dayNow: '',
            CurPos: {
                latitude: '',
                longitude: '',
            }
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
        customerGetRestaurantByIDAPI(
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
        Date.prototype.toISOString = function () {
            let pad = (n) => (n < 10) ? '0' + n : n;
            let hours_offset = this.getTimezoneOffset() / 60;
            let offset_date = this.setHours(this.getHours() - hours_offset);
            let symbol = (hours_offset >= 0) ? "-" : "+";
            let time_zone = symbol + pad(Math.abs(hours_offset)) + ":00";

            return this.getUTCFullYear() +
                '-' + pad(this.getUTCMonth() + 1) +
                '-' + pad(this.getUTCDate()) +
                'T' + pad(this.getUTCHours()) +
                ':' + pad(this.getUTCMinutes()) +
                ':' + pad(this.getUTCSeconds()) +
                '.' + (this.getUTCMilliseconds() / 1000).toFixed(3).slice(2, 5) +
                time_zone;
        };
        console.log(new Date().toISOString())

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
            customerGetAllDishByRestaurantIDAPI(
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
            this.totalAmount = this.order.totalAmount = 0
            for (let i = 0; i < this.order.id.length; i++) {
                if (this.order.amount[i] > 0) {
                    this.order.totalAmount += this.order.amount[i] * this.menu[i].price;
                    if (this.menu[i].making_time > this.MakingTime) this.MakingTime = this.menu[i].making_time
                }
            }
            this.TotalDeliveryTime()
            this.totalAmount = this.order.totalAmount
            this.order.totalAmount += this.deliveryFee;
        },
        storeImg(img) {
            this.imgTemp = img
            this.overlay = true
        },
        EditInfo() {
            if (this.CustomerInfo.address.length <= 0) {
                this.$emit("showSnackBar", "姓名或地址不可為空");
            } else {
                this.isEditing = !this.isEditing
                console.log("收貨地址")
                console.log(this.CustomerInfo.address)
            }
        },
        getCurrentTime() {
            this.dayNow = this.date = new Date().toISOString().substr(0, 10)
            //this.time = new Date().toISOString().substr(11, 5)
            let date = new Date()
            date.setDate(date.getDate() + 3)
            this.dayConstrain = date.toISOString().substr(0, 10)
            let time = new Date()
            time.setMinutes(time.getMinutes() + this.maxMakingTime)
            this.time = this.timeConstrain = time.toISOString().substr(11, 5)
            console.log("timeConstrain")
            console.log(this.timeConstrain)
        },
        geoFindMe() {
            if (!navigator.geolocation) {
                alert("Geolocation is not supported by your browser");
                return;
            }
            let options = {
                enableHighAccuracy: true,
                timeout: 5000,
                maximumAge: 0
            };
            navigator.geolocation.getCurrentPosition(this.success, this.error, options);
        },
        success(position) {
            this.CurPos.latitude = position.coords.latitude;
            this.CurPos.longitude = position.coords.longitude;
            console.log("Latitude is " + this.CurPos.latitude + " Longitude is " + this.CurPos.longitude);
            console.log("CurPos")
            console.log(this.CurPos.latitude + "," + this.CurPos.longitude);
            let config = {
                params: {'long': this.CurPos.longitude, 'lat': this.CurPos.latitude},
                headers: {Authorization: "Bearer " + this.$store.getters.getAccessToken}
            };
            customerlocationToAddressAPI(
                config
            ).then((res) => {
                this.tempAddress = res.data.data
                this.isCorrectDialog = true
                console.log("lotoadd")
                console.log(res.data.data)
            })
                .catch((error) => {
                    console.error(error)
                })
        },
        error(err) {
            alert('ERROR(' + err.code + '): ' + err.message);
            console.warn('ERROR(' + err.code + '): ' + err.message);
            // error.code can be:
            //   0: unknown error
            //   1: permission denied
            //   2: position unavailable (error response from location provider)
            //   3: timed out
        },
        cDeliveryTime() {
            let config = {
                params: {'ori_address': this.list.address, 'des_address': this.CustomerInfo.address},
                headers: {Authorization: "Bearer " + this.$store.getters.getAccessToken}
            };
            customerGetDeliveryTimeIDAPI(
                config
            ).then((res) => {
                console.log(res.data)
                console.log(res.data["rows"])
                this.durationTime.text = res.data.data.rows[0].elements[0].duration.text
                this.durationTime.text = this.durationTime.text.substring(0, this.durationTime.text.length - 5)
                this.durationTime.value = res.data.data.rows[0].elements[0].duration.value
                console.log("durationTime")
                console.log(this.durationTime)
                this.TotalDeliveryTime()
            })
                .catch((error) => {
                    console.error(error)
                })

        },
        TotalDeliveryTime() {
            let temp
            if (this.durationTime.value + this.MakingTime > this.durationTime.text * 60) temp = parseInt(this.durationTime.text, 10) + 1
            this.maxMakingTime = temp
            console.log("maxMakingTime,durationTime.value,MakingTime")
            console.log(this.maxMakingTime + ',' + this.durationTime.value + ',' + this.MakingTime)
        }
    }
}
;
</script>

<style>

#container {
    max-width: 400px;
}

</style>
