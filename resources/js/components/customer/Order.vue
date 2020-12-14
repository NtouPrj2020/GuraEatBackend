<template>
  <v-card class="mx-auto" max-width="500">
    <v-app-bar
      absolute
      color="white"
      elevate-on-scroll
      scroll-target="#scrolling-techniques-7"
    >
      <v-btn icon @click="RollBack">
        <v-icon>mdi-arrow-left</v-icon>
      </v-btn>
      <v-toolbar-title>歷史訂單</v-toolbar-title>
      <v-spacer></v-spacer>
      <v-btn icon>
        <v-icon>mdi-magnify</v-icon>
      </v-btn>
      <v-btn icon>
        <v-icon>mdi-dots-vertical</v-icon>
      </v-btn>
    </v-app-bar>
    <v-sheet
      id="scrolling-techniques-7"
      class="overflow-y-auto"
      :height="windowSize.y"
    >
      <v-list flat subheader three-line class="mb-16">
        <v-subheader>General</v-subheader>

        <v-list-item-group v-model="settings" multiple active-class="">
          <v-list-item v-for="order in orders" :key="order.id">
            <template v-slot:default="{ active }">
              <v-list-item-action>
                <v-checkbox :input-value="active"></v-checkbox>
              </v-list-item-action>

              <v-list-item-content>
                <v-list-item-title>Notifications</v-list-item-title>
                <v-list-item-subtitle
                  >Notify me about updates to apps or games that I downloaded
                </v-list-item-subtitle>
              </v-list-item-content>
            </template>
          </v-list-item>
        </v-list-item-group>
      </v-list>
    </v-sheet>
  </v-card>
</template>
<script>
import { customerGetAllRestaurantAPI } from "../../api";
import axios from "axios";

export default {
  data: () => ({
    page: 1,
    list: [],
    windowSize: {
      x: 0,
      y: 0,
    },
  }),
  mounted() {},
  methods: {},
};
</script>

<style>
</style>
