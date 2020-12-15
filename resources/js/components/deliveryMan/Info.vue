<template>
    <div>
        <div class="panel-heading text-center">個人資訊</div>
        <div class="container mb-12">
            <v-card>
                <v-card-text>姓名: {{ info.name }}</v-card-text>
                <v-card-text>地址: {{ info.address }}</v-card-text>
                <v-card-text>電話: {{ info.phone }}</v-card-text>
                <v-card-text>電子郵件: {{ info.email }}</v-card-text>
            </v-card>
            <v-row justify="center" class="pa-2">
                <v-select
                    v-model="wanted_role"
                    :items="role_list"
                    label="切換至"
                    class="px-14 pt-5"
                    outlined
                ></v-select>
                <v-btn min-width="200" @click="switch_user">切換使用者身分</v-btn>
            </v-row>
            <v-row justify="center" class="pa-2">
                <v-btn min-width="200" @click="history_order">歷史訂單紀錄</v-btn>
            </v-row>
            <v-row justify="center" class="pa-2">
                <v-btn min-width="200" @click="show_edit">更改個人資料</v-btn>
            </v-row>
            <v-row justify="center" class="pa-2">
                <v-btn min-width="200" @click="signout">登出</v-btn>
            </v-row>
            <v-dialog v-model="editDialog" scrollable class="py-5">
                <v-card>
                    <v-card-title>編輯個人資料</v-card-title>
                    <v-text-field
                        label="姓名"
                        outlined
                        v-model="info.name"
                    ></v-text-field>
                    <v-text-field
                        label="地址"
                        outlined
                        v-model="info.address"
                    ></v-text-field>
                    <v-text-field
                        label="電話"
                        outlined
                        v-model="info.phone"
                    ></v-text-field>
                    <v-text-field
                        label="電子郵件"
                        outlined
                        v-model="info.email"
                    ></v-text-field>
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
                </v-card>
            </v-dialog>
        </div>
    </div>
</template>

<script>
import {
    deliveryManGetInfoAPI,
    deliveryManEditInfoAPI,
    restaurantSwitchUserModeAPI,
    deliveryManSwitchUserModeAPI,
} from "../../api";

export default {
    props: {},
    mounted() {
        this.$emit("changefocus", "info");
        let config = {
            params: {ID: this.$route.params.id},
            headers: {
                Authorization: "Bearer " + this.$store.getters.getAccessToken,
            },
        };

        deliveryManGetInfoAPI(config)
            .then((resp) => {
                this.info.id = resp.data.data.id;
                this.info.name = resp.data.data.name;
                this.info.phone = resp.data.data.phone;
                this.info.address = resp.data.data.address;
                this.info.email = resp.data.data.email;
                console.log("info");
                console.log(this.info);
            })
            .catch((error) => {
                console.error(error);
            });
    },
    data: () => ({
        value: "info",
        info: {
            id: "id",
            phone: "phone",
            email: "email",
            name: "name",
            address: "address",
        },
        editDialog: false,
        editComfirmLoading: false,
        wanted_mode: 0,
        role_list: ["顧客", "餐廳管理員"],
        wanted_role: "",
        resp: "",
    }),
    methods: {
        switch_user() {
            /*console.log(this.wanted_role);*/
            if (this.wanted_role === "顧客") {
                this.wanted_mode = 1;
            } else {
                this.wanted_mode = 3;
            }
            let config = {
                headers: {
                    Authorization: "Bearer " + this.$store.getters.getAccessToken,
                },
            };
            /* 需要判斷select回傳身分並設定至wanted_mode */

            deliveryManSwitchUserModeAPI(
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
            }).catch((err) => {
                if (err.response.status === 403) {
                    context.$emit("showSnackBar", "無其他身分");
                } else if (err.response.status === 404) {
                    context.$emit("showSnackBar", "未知的錯誤");
                }
            });
        },
        history_order() {
            this.$router.push("");
        },
        edit_info() {
            this.editDialog = true;
            let config = {
                headers: {
                    Authorization: "Bearer " + this.$store.getters.getAccessToken,
                },
            };
            let data = {
                name: this.info.name,
                email: this.info.email,
                address: this.info.address,
                phone: this.info.phone,
            };
            deliveryManEditInfoAPI(data, config)
                .then((resp) => {
                    if (resp.status === 200) {
                        this.editComfirmLoading = false;
                        this.editDialog = false;
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
