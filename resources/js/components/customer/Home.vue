<template>
    <div>
        <div class="panel-heading text-center">Home</div>
        <div class="container">
            <v-main>
                <v-card class="mx-auto" max-width="400">
                    <v-img
                        class="white--text align-end"
                        height="200px"
                        src="https://cdn.vuetifyjs.com/images/cards/docks.jpg"
                    >
                        <v-card-title>Top 10 Australian beaches</v-card-title>
                    </v-img>

                    <v-card-subtitle class="pb-0"> Number 10 </v-card-subtitle>

                    <v-card-text class="text--primary">
                        <div>Whitehaven Beach</div>

                        <div>Whitsunday Island, Whitsunday Islands</div>
                    </v-card-text>

                    <v-card-actions>
                        <v-btn color="orange" text> Share </v-btn>

                        <v-btn color="orange" text> Explore </v-btn>
                    </v-card-actions>
                </v-card>
                <v-card class="mx-auto" max-width="400">
                    <v-img
                        class="white--text align-end"
                        height="200px"
                        src="https://cdn.vuetifyjs.com/images/cards/docks.jpg"
                    >
                        <v-card-title>Top 10 Australian beaches</v-card-title>
                    </v-img>

                    <v-card-subtitle class="pb-0"> Number 10 </v-card-subtitle>

                    <v-card-text class="text--primary">
                        <div>Whitehaven Beach</div>

                        <div>Whitsunday Island, Whitsunday Islands</div>
                    </v-card-text>

                    <v-card-actions>
                        <v-btn color="orange" text> Share </v-btn>

                        <v-btn color="orange" text> Explore </v-btn>
                    </v-card-actions>
                </v-card>
            </v-main>
        </div>
    </div>
</template>
<script>
import {getRestaurantall} from "../../api";
import axios from "axios";
export default {
    data: () => ({
        page:1,
    }),
    mounted() {
        let config = {
            params:{"page":this.page,},
            headers: {Authorization: "Bearer " + "2|BFZMyvO5kPHPKrX2bfCuoy1JPgYE7RW0ITUCiW8P"}
        };
        getRestaurantall(
            config
        ).then((res) => { console.log(res.data) })
            .catch((error) => { console.error(error) })

    },
};
</script>
