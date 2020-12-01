<template>

    <div>
        <v-app-bar
            absolute
            color="white"
            elevate-on-scroll
            scroll-target="#scrolling-techniques-7"
        >
            <v-btn icon @click="RollBack">
                <v-icon>mdi-arrow-left</v-icon>
            </v-btn>

            <v-toolbar-title>{{ id }}</v-toolbar-title>

            <v-spacer></v-spacer>

            <v-btn icon>
                <v-icon>mdi-dots-vertical</v-icon>
            </v-btn>
        </v-app-bar>
        <div class="d-flex">
            <v-checkbox
                v-model="readonly"
                label="Readonly"
            ></v-checkbox>
        </div>

        <v-expansion-panels
            v-model="panel"
            :readonly="readonly"
            multiple
        >
            <v-expansion-panel>
                <v-expansion-panel-header>Panel 1</v-expansion-panel-header>
                <v-expansion-panel-content>
                    Some content
                </v-expansion-panel-content>
            </v-expansion-panel>

            <v-expansion-panel>
                <v-expansion-panel-header>Panel 2</v-expansion-panel-header>
                <v-expansion-panel-content>
                    Some content
                </v-expansion-panel-content>
            </v-expansion-panel>

            <v-expansion-panel>
                <v-expansion-panel-header>Panel 3</v-expansion-panel-header>
                <v-expansion-panel-content>
                    Some content
                </v-expansion-panel-content>
            </v-expansion-panel>
        </v-expansion-panels>
    </div>
</template>
<script>
import {getRestaurantall} from "../../api";
import axios from "axios";

export default {
    data: () => ({
        menu: [],
        panel: [0, 1],
        readonly: false,
    }),
    props: ['id'],
    mounted() {
        console.log(this.$route.params.id)
        let data = {}
        let config = {
            params: {"restaurant_id": this.$route.params.id},
            headers: {Authorization: "Bearer " + this.$store.getters.getAccessToken}
        };
        getRestaurantall(
            config
        ).then((res) => {
            this.list = res.data.data.data
            console.log(this.list)
        })
            .catch((error) => {
                console.error(error)
            })

    },
    methods: {
        RollBack() {
            this.$router.push("/customer/home");
        }
    }
};
</script>
