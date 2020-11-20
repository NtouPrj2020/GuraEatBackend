<template>
<div id="app">
  <v-app id="inspire">
    <v-main>
            <v-card
      :loading="loading"
      class="mx-auto my-12"
      max-width="374"
    >
      <template slot="progress">
        <v-progress-linear
          color="deep-purple"
          height="10"
          indeterminate
        ></v-progress-linear>
      </template>
  
      <v-img
        height="250"
        src="https://cdn.vuetifyjs.com/images/cards/cooking.png"
      ></v-img>
  
      <v-card-title>Cafe Badilico</v-card-title>
  
      <v-card-text>
        <v-row
          align="center"
          class="mx-0"
        >
          <v-rating
            :value="4.5"
            color="amber"
            dense
            half-increments
            readonly
            size="14"
          ></v-rating>
  
          <div class="grey--text ml-4">
            4.5 (413)
          </div>
        </v-row>
  
        <div class="my-4 subtitle-1">
          $ • Italian, Cafe
        </div>
  
        <div>Small plates, salads & sandwiches - an intimate setting with 12 indoor seats plus patio seating.</div>
      </v-card-text>
  
    </v-card>
    </v-main>


    <v-bottom-navigation fixed v-model="value">
    <v-btn value="home">
        <span>首頁</span>
  
        <v-icon>mdi-home</v-icon>
      </v-btn>
      <v-btn value="search">
        <span>搜尋</span>
  
        <v-icon>mdi-magnify</v-icon>
      </v-btn>
  
      <v-btn value="cart">
        <span>購物車</span>
  
        <v-icon>mdi-cart</v-icon>
      </v-btn>
  
      <v-btn value="info">
        <span>資訊</span>
  
        <v-icon>mdi-account-circle-outline</v-icon>
      </v-btn>
    </v-bottom-navigation>
  </v-app>
</div>