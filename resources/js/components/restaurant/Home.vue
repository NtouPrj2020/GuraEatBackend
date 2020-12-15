<template>
    <div>
        <v-card>
            <v-img :src="resImg" class="white--text align-end" height="200px">
                <v-card-title>
                    <div
                        class="text-h4 rounded pa-2"
                        style="background-color: #808080"
                    >
                        {{ resName }}
                    </div>
                </v-card-title>
            </v-img>
        </v-card>
        <v-row dense>
            <v-col>
                <v-list class="mb-11" subheader two-line>
                    <v-subheader inset>MENU</v-subheader>

                    <v-list-item v-for="dishes in menu" :key="dishes.id">
                        <v-list-item-avatar class="rounded">
                            <v-img
                                :alt="dishes.name"
                                :src="dishes.img"
                                @click="storeImg(dishes.img)"
                            ></v-img>

                        </v-list-item-avatar>

                        <v-list-item-content>
                            <v-list-item-title v-text="dishes.name"></v-list-item-title>

                            <v-list-item-subtitle
                            >價格：{{ dishes.price }}
                            </v-list-item-subtitle
                            >
                        </v-list-item-content>

                        <v-list-item-action>
                            <v-row dense>
                                <v-btn icon @click="showEditDish(dishes.id)">
                                    <v-icon>fas fa-edit</v-icon>
                                </v-btn>
                            </v-row>
                        </v-list-item-action>
                        <v-list-item-action>
                            <v-row dense>
                                <v-btn icon @click="showDeleteDish(dishes.id)">
                                    <v-icon>fas fa-trash</v-icon>
                                </v-btn>
                            </v-row>
                        </v-list-item-action>
                    </v-list-item>
                    <span
                        @click="overlay = false"
                        class="justify-center align-center"
                    >
                  <v-overlay :value="overlay">
                    <v-img
                        :src="imgTemp"
                        :max-width="windowSize.x - 50"
                        :max-height="windowSize.y - 50"
                        contain
                    >
                    </v-img>
                  </v-overlay>
                </span>
                </v-list>
            </v-col>
        </v-row>
        <v-dialog v-model="editDialog" scrollable class="py-5">
            <v-card>
                <v-card-title>編輯餐點</v-card-title>
                <v-text-field
                    label="餐點名稱"
                    outlined
                    v-model="editDishName"
                ></v-text-field>
                <v-text-field
                    label="餐點圖片"
                    outlined
                    v-model="editDishImg"
                ></v-text-field>
                <v-text-field
                    label="餐點價格"
                    outlined
                    v-model="editDishPrice"
                ></v-text-field>
                <v-text-field
                    label="餐點製作時間"
                    outlined
                    v-model="editDishTime"
                ></v-text-field>
                <v-card-actions>
                    <v-btn color="blue darken-1" @click="editDialog = false">
                        取消
                    </v-btn>
                    <v-btn
                        :loading="editComfirmLoading"
                        color="blue darken-1"
                        @click="sendEditDish"
                    >
                        儲存
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-dialog v-model="addDialog" scrollable class="py-5">
            <v-card>
                <v-card-title>新增餐點</v-card-title>
                <v-text-field
                    label="餐點名稱"
                    outlined
                    v-model="addDishName"
                ></v-text-field>
                <v-text-field
                    label="餐點圖片"
                    outlined
                    v-model="addDishImg"
                ></v-text-field>
                <v-text-field
                    label="餐點價格"
                    outlined
                    v-model="addDishPrice"
                ></v-text-field>
                <v-text-field
                    label="餐點製作時間"
                    outlined
                    v-model="addDishTime"
                ></v-text-field>
                <v-card-actions>
                    <v-btn color="blue darken-1" @click="addDialog = false">
                        取消
                    </v-btn>
                    <v-btn
                        :loading="addComfirmLoading"
                        color="blue darken-1"
                        @click="sendAddDish"
                    >
                        儲存
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-dialog v-model="deleteDialog" scrollable class="py-5">
            <v-card>
                <v-card-text>確定要刪除{{ deleteDishName }}?</v-card-text>
                <v-card-actions>
                    <v-btn color="blue darken-1" @click="deleteDialog = false">
                        取消
                    </v-btn>
                    <v-btn
                        :loading="deleteComfirmLoading"
                        color="blue darken-1"
                        @click="sendDeleteDish"
                    >
                        儲存
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog
        >
        <v-btn fab fixed right bottom class="mb-15" @click="showAddDish">
            <v-icon dark> mdi-plus</v-icon>
        </v-btn>
    </div>
</template>
<script>
import {
    restaurantGetInfoAPI,
    restaurantGetAllDishAPI,
    restaurantGetDishByDishIDAPI,
    restaurantEditDishAPI,
    restaurantDeleteDishAPI,
    restaurantAddDishAPI,
} from "../../api";

export default {
    data: () => ({
        resImg: "https://cdn.discordapp.com/attachments/758534409023389700/786551377962336276/24884996f313402e30875c5aa4a0c66d.JPG",
        imgTemp: '',
        resName: "loading",
        windowSize: {
            x: 0,
            y: 0,
        },
        overlay: false,
        menu: [],
        config: {},
        editDialog: false,
        deleteDialog: false,
        addDialog: false,
        editDishName: "",
        editDishImg: "",
        editDishPrice: "",
        editDishTime: "",
        nowEditingID: "",
        addDishName: "",
        addDishImg: "",
        addDishPrice: "",
        addDishTime: "",
        nowDeletingID: "",
        deleteDishName: "",
        editComfirmLoading: false,
        deleteComfirmLoading: false,
        addComfirmLoading: false,
    }),
    props: ["id"],
    mounted() {
        this.$emit("changefocus", "home");
        this.onResize();
        this.config = {
            headers: {
                Authorization: "Bearer " + this.$store.getters.getAccessToken,
            },
        };
        this.showResInfo();
        this.refreshAllDish();
    },
    methods: {
        onResize() {
            this.windowSize = {x: window.innerWidth, y: window.innerHeight};
        },
        showResInfo() {
            restaurantGetInfoAPI(this.config)
                .then((resp) => {
                    if (resp.status === 200) {
                        this.resName = resp.data.data.name;
                        this.resImg = resp.data.data.img;
                        console.log(resp.data);
                        console.log("done");
                    }
                })
                .catch((err) => {
                    console.log(err);
                    if (err.response.status === 401) {
                        this.$emit("showSnackBar", "信箱/密碼錯誤");
                        console.log(err);
                    } else if (err.response.status === 404) {
                        this.$emit("showSnackBar", "未知的錯誤");
                        console.log(err);
                    }
                    console.log(err);
                });
        },
        refreshAllDish() {
            restaurantGetAllDishAPI(this.config)
                .then((resp) => {
                    if (resp.status === 200) {
                        this.menu = [];
                        for (let dishes = 0; dishes < resp.data.data.length; dishes++) {
                            this.menu.push(resp.data.data[dishes]);
                        }
                        console.log(resp.data);
                        console.log("done");
                    }
                })
                .catch((err) => {
                    console.log(err);
                    if (err.response.status === 401) {
                        this.$emit("showSnackBar", "信箱/密碼錯誤");
                        console.log(err);
                    } else if (err.response.status === 404) {
                        this.$emit("showSnackBar", "未知的錯誤");
                        console.log(err);
                    }
                    console.log(err);
                });
        },
        showEditDish(id) {
            let config = {
                params: {ID: id},
                headers: {
                    Authorization: "Bearer " + this.$store.getters.getAccessToken,
                },
            };
            this.nowEditingID = id;
            restaurantGetDishByDishIDAPI(config)
                .then((resp) => {
                    if (resp.status === 200) {
                        this.nowEditingID = id;
                        this.editDishName = resp.data.data[0].name;
                        this.editDishImg = resp.data.data[0].img;
                        this.editDishPrice = resp.data.data[0].price;
                        this.editDishTime = resp.data.data[0].making_time;
                        this.editDialog = true;
                        console.log(resp.data);
                        console.log("done");
                    }
                })
                .catch((err) => {
                    if (err.response.status === 401) {
                        console.log(err);
                    } else if (err.response.status === 404) {
                        console.log(err);
                    }
                    console.log(err);
                });
        },
        sendEditDish() {
            this.editComfirmLoading = true;
            let config = {
                headers: {
                    Authorization: "Bearer " + this.$store.getters.getAccessToken,
                },
            };
            let data = {
                ID: this.nowEditingID.toString(),
                making_time: this.editDishTime,
                name: this.editDishName,
                img: this.editDishImg,
                price: this.editDishPrice,
            };
            restaurantEditDishAPI(data, config)
                .then((resp) => {
                    if (resp.status === 200) {
                        this.refreshAllDish();
                        this.editComfirmLoading = false;
                        this.editDialog = false;
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
        showAddDish() {
            this.addDialog = true;
            this.addDishName = "";
            this.addDishImg = "";
            this.addDishPrice = "";
            this.addDishTime = "";
        },
        sendAddDish() {
            console.log("this.editDishTime" + this.editDishTime);
            this.addComfirmLoading = true;
            let config = {
                headers: {
                    Authorization: "Bearer " + this.$store.getters.getAccessToken,
                },
            };
            let data = {
                ID: this.nowEditingID.toString(),
                making_time: this.addDishTime,
                name: this.addDishName,
                img: this.addDishImg,
                price: this.addDishPrice,
            };
            restaurantAddDishAPI(data, config)
                .then((resp) => {
                    if (resp.status === 200) {
                        this.refreshAllDish();
                        this.addComfirmLoading = false;
                        this.addDialog = false;
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
        showDeleteDish(id) {
            let config = {
                params: {ID: id},
                headers: {
                    Authorization: "Bearer " + this.$store.getters.getAccessToken,
                },
            };
            restaurantGetDishByDishIDAPI(config)
                .then((resp) => {
                    if (resp.status === 200) {
                        this.nowDeletingID = id;
                        this.deleteDishName = resp.data.data[0].name;
                        this.deleteDialog = true;
                        console.log(resp.data);
                        console.log("done");
                    }
                })
                .catch((err) => {
                    if (err.response.status === 401) {
                        console.log(err);
                    } else if (err.response.status === 404) {
                        console.log(err);
                    }
                    console.log(err);
                });
        },
        storeImg(img) {
            this.imgTemp = img;
            this.overlay = true;
        },
        sendDeleteDish() {
            this.deleteComfirmLoading = true;
            let config = {
                headers: {
                    Authorization: "Bearer " + this.$store.getters.getAccessToken,
                },
                data: {
                    ID: this.nowDeletingID.toString(),
                },
            };
            let data = {};
            restaurantDeleteDishAPI(config)
                .then((resp) => {
                    if (resp.status === 200) {
                        this.refreshAllDish();
                        this.deleteComfirmLoading = false;
                        this.deleteDialog = false;
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
