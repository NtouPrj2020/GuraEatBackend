<template>
    <div>
        <div class="panel-heading text-center">個人資訊</div>
        <div class="container mb-12">
            <v-card 
            :key="this.change"
             >
             <div>
                <v-card-text class="font-weight-medium">餐廳名稱: {{ info.name }}</v-card-text>
                <v-card-text>地址: {{ info.address }}</v-card-text>
                <v-card-text>電話: {{ info.phone }}</v-card-text>
                <v-card-text>電子郵件: {{ info.email }}</v-card-text>
                <v-card-text>餐廳標籤: {{ info.rest_tags_name }}</v-card-text>
             </div>
            </v-card>
            <v-row class="ml-4 mr-4">
                <v-col :cols="7">
                <v-select
                    v-model="wanted_role"
                    :items="role_list"
                    label="切換至"
                    background-color="white"
                    outlined
                ></v-select>
                </v-col>
                <v-col :cols="5" class="mt-3">
                <v-btn @click="switch_user">切換使用者身分</v-btn>
                </v-col>
            </v-row>
            <v-row justify="center" class="pa-2">
                <v-btn min-width="200" @click="show_edit">更改個人資料</v-btn>
            </v-row>
            <v-row justify="center" class="pa-2">
                <v-btn min-width="200" @click="signout">登出</v-btn>
            </v-row>
            <v-dialog v-model="editDialog" scrollable class="py-5">
                <v-card>
                    <v-container>
                        <form>
                            <v-row>
                                <v-col cols="12">
                                    <v-card-title>編輯個人資料</v-card-title>

                                </v-col>
                                <v-col cols="12">
                                    <v-text-field
                                        label="姓名"
                                        outlined
                                        v-model="info.name"
                                    ></v-text-field>
                                </v-col>
                                <v-col cols="12">
                                    <v-text-field
                                        label="地址"
                                        outlined
                                        v-model="info.address"
                                    ></v-text-field>
                                </v-col>
                                <v-col cols="12">
                                    <v-text-field
                                        label="電話"
                                        outlined
                                        v-model="info.phone"
                                    ></v-text-field>
                                </v-col>
                                <v-col cols="12">
                                    <v-text-field
                                        label="電子郵件"
                                        outlined
                                        v-model="info.email"
                                    ></v-text-field>
                                </v-col>
                                <v-col cols="12">
                                    <v-text-field
                                        label="餐廳照片"
                                        outlined
                                        v-model="info.img"
                                    ></v-text-field>
                                </v-col>
                                <v-col cols="12">
                                    <v-select
                                        background-color="white"
                                        v-model="selected_tags"
                                        :items="tag_names"
                                        multiple
                                        chips
                                        label="請選擇要加入的標籤"
                                    ></v-select>
                                </v-col>

                                <v-card-actions>
                                    <v-btn color="blue darken-1" @click="editDialog = false">
                                        取消
                                    </v-btn>
                                    <v-btn
                                        :loading="editComfirmLoading"
                                        color="blue darken-1"
                                        @click="edit_info"
                                    >
                                        儲存
                                    </v-btn>
                                </v-card-actions>
                            </v-row>
                        </form>
                    </v-container>
                </v-card>
            </v-dialog>
        </div>
    </div>
</template>

<script>
import {
    restaurantGetAllTagAPI,
    restaurantGetInfoAPI,
    restaurantEditInfoAPI,
    restaurantSwitchUserModeAPI,
} from "../../api";

export default {
    props: {},
    mounted() {
        this.$emit("changefocus", "info");
        this.init_info();
    },
    data: () => ({
        info: {
            id: "id",
            phone: "phone",
            email: "email",
            name: "name",
            address: "address",
            img:"",
            rest_tags:[],
            rest_tags_name:[],
        },
        tags:[],
        id_tags:[],
        tag_names:[],
        selected_tags:[],
        selected_tags_id:[],
        editDialog: false,
        editComfirmLoading: false,
        wanted_mode: 0,
        role_list: ["顧客", "外送員"],
        wanted_role: "",
        resp: "",
        temp:"",
        change:false,
    }),
    methods: {
        init_info(){
            let config = {
            params: {ID: this.$route.params.id},
            headers: {
                Authorization: "Bearer " + this.$store.getters.getAccessToken,
            },
        };

        restaurantGetInfoAPI(config)
            .then((resp) => {
                this.info.id = resp.data.data.id;
                this.info.name = resp.data.data.name;
                this.info.phone = resp.data.data.phone;
                this.info.address = resp.data.data.address;
                this.info.email = resp.data.data.email;
                this.info.img = resp.data.data.img;
                this.info.rest_tags = resp.data.data.tags;
                for (let index = 0; index < this.info.rest_tags.length; index++) {
                    const element = this.info.rest_tags[index];
                    this.info.rest_tags_name[index] = element.name;
                }
                this.change = this.change?false:true;
                console.log("info");
                
            })
            .catch((error) => {
                console.error(error);
            });
        this.get_all_tags();
        },
        get_all_tags() {
        let config = {
            headers: {
            Authorization: "Bearer " + this.$store.getters.getAccessToken,
            },
        };
        restaurantGetAllTagAPI(config)
        .then((resp) => {       
            for (let index = 0; index < resp.data.data.length; index++) {
                const element = resp.data.data[index];
                this.tags[index] = element;
                var name = element.name;
                this.id_tags[name] = element.id;
                this.tag_names[index] = (element.name);
            }
            //console.log(this.id_tags);
        });
        },
        switch_user() {
            /*console.log(this.wanted_role);*/
            if (this.wanted_role === "顧客") {
                this.wanted_mode = 1;
            } else {
                this.wanted_mode = 2;
            }
            let config = {
                headers: {
                    Authorization: "Bearer " + this.$store.getters.getAccessToken,
                },
            };
            /* 需要判斷select回傳身分並設定至wanted_mode */

            restaurantSwitchUserModeAPI(
                {
                    role: this.wanted_mode,
                    device_name: this.$store.getters.getDeviceName,
                },
                config
            ).then((resp) => {
                if (resp.status === 200) {
                    this.$store.commit("MODE", this.wanted_mode);
                    this.$store.commit("ACCESS_TOKEN", resp.data.data.access_token);
                    this.$store.commit("USER_NAME", resp.data.data.name);
                    this.$router.push("/guest");
                } else if (resp.status === 403) {
                    this.$emit("showSnackBar", "無其他身分");
                } else if (resp.status === 404) {
                    this.$emit("showSnackBar", "未知的錯誤");
                }
            });
        },
        
        edit_info() {
            this.editDialog = true;
            let config = {
                headers: {
                    Authorization: "Bearer " + this.$store.getters.getAccessToken,
                },
            };
            for (let index = 0; index < this.selected_tags.length; index++) {
                const element = this.selected_tags[index];
                this.selected_tags_id[index] = (this.id_tags[element]);
            }
            console.log(this.selected_tags_id)
            let data = {
                name: this.info.name,
                email: this.info.email,
                address: this.info.address,
                phone: this.info.phone,
                img:this.info.img,
                tags: this.selected_tags_id,
            };
            restaurantEditInfoAPI(data,config)
                .then((resp) => {
                    if (resp.status === 200) {
                        this.editComfirmLoading = false;
                        this.editDialog = false;
                        console.log("done");
                        
                        this.init_info();
                        console.log("done rerender");
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
        show_edit() {
            this.editDialog = true;
        },
        signout() {
            if (this.$store.getters.getAccessToken != "") {
                this.$store.commit("ACCESS_TOKEN", "");
                this.$store.commit("USER_NAME", "");
                this.$store.commit("MODE", 0);
                this.$router.push("/guest/login");
            } else {
                this.$router.push("/guest/login");
            }
        },
    },
};
</script>
