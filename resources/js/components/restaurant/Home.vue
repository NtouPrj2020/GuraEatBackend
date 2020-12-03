<template>
  <div>
    <v-container class="my-14" fluid>
      <v-card>
        <v-img :src="resImg" class="white--text align-end" height="200px">
          <v-card-title>
            <v-chip class="text-h4 pa-5" :label="resName">res name</v-chip>
          </v-card-title>
        </v-img>
      </v-card>

      <v-row dense>
        <v-col>
          <v-list subheader two-line>
            <v-subheader inset>MENU</v-subheader>

            <v-list-item v-for="dishes in menu" :key="dishes.id">
              <v-list-item-avatar class="rounded">
                <v-img
                  :alt="dishes.name"
                  :src="dishes.img"
                  @click="overlay = !overlay"
                ></v-img>
                <span
                  @click="overlay = false"
                  class="justify-center align-center"
                >
                  <v-overlay :value="overlay">
                    <v-img
                      :alt="dishes.name"
                      :src="dishes.img"
                      :max-width="windowSize.x - 50"
                      :max-height="windowSize.y - 50"
                      contain
                    >
                    </v-img>
                  </v-overlay>
                </span>
              </v-list-item-avatar>

              <v-list-item-content>
                <v-list-item-title v-text="dishes.name"></v-list-item-title>

                <v-list-item-subtitle
                  >價格：{{ dishes.price }}</v-list-item-subtitle
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
                  <v-btn icon @click="deleteDish(dishes.id)">
                    <v-icon>fas fa-trash</v-icon>
                  </v-btn>
                </v-row>
              </v-list-item-action>
            </v-list-item>
          </v-list>
        </v-col>
      </v-row>
      <v-dialog id="editdialog" v-model="editDialog" scrollable class="py-5">
        <v-card>
          <v-card-title>編輯餐點</v-card-title>
          <v-card-text style="height: 300px"> </v-card-text>
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
    </v-container>
  </div>
</template>
<script>
import {
  getDishByRestaurantID,
  getDishByDishID,
  restaurantEditDish,
} from "../../api";

export default {
  data: () => ({
    resimg: "https://i.imgur.com/3f98UhC.jpg",
    resName: "test res",
    windowSize: {
      x: 0,
      y: 0,
    },
    overlay: false,
    menu: [],
    config: {},
    editDialog: false,
    editDishName: "",
    editDishImg: "",
    editDishPrice: "",
    editDishTime: "",
    nowEditing: "",
    editComfirmLoading: false,
  }),
  props: ["id"],
  mounted() {
    this.onResize();
    this.config = {
      headers: {
        Authorization: "Bearer " + this.$store.getters.getAccessToken,
      },
    };
    this.refreshAllDish();
  },
  methods: {
    onResize() {
      this.windowSize = { x: window.innerWidth, y: window.innerHeight };
    },
    refreshAllDish() {
      getDishByRestaurantID(this.config)
        .then((resp) => {
          if (resp.status === 200) {
            for (let dishes = 0; dishes < resp.data.data.length; dishes++) {
              this.menu.push(resp.data.data[dishes]);
            }
            console.log(resp.data);
            console.log("done");
          }
        })
        .catch((err) => {
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
        params: { ID: id },
        headers: {
          Authorization: "Bearer " + this.$store.getters.getAccessToken,
        },
      };
      this.nowEditing = id;
      getDishByDishID(config)
        .then((resp) => {
          if (resp.status === 200) {
            this.nowEditing = id;
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
      restaurantEditDish(config)
        .then((resp) => {
          if (resp.status === 200) {
            this.editDialog = false;
            this.editComfirmLoading = false;
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
    deleteDish(id) {},
  },
};
</script>
