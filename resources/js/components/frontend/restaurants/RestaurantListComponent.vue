<template>
    <LoadingComponent :props="loading" />
    <section class="mb-5 pt-8">
        <div class="container content-body" v-if="restaurants.length > 0">
            <h2 class="text-2xl font-semibold capitalize mb-6">All Restaurants</h2>
            <RestaurantComponent :items="restaurants" :type="itemProps.type" :design="itemProps.design" />
        </div>
    </section>
</template>

<script>

import alertService from "../../../services/alertService";
import statusEnum from "../../../enums/modules/statusEnum";
import restaurantDesignEnum from "../../../enums/modules/restaurantDesignEnum";
import RestaurantComponent from "../components/RestaurantComponent";
import ItemComponent from "../components/ItemComponent";
import itemDesignEnum from "../../../enums/modules/itemDesignEnum";
import itemTypeEnum from "../../../enums/modules/itemTypeEnum";
import LoadingComponent from "../components/LoadingComponent";

export default {
    name: "RestaurantListComponent",
    components: {RestaurantComponent, ItemComponent, LoadingComponent},
    props: {
        items: Object,
    },
    data() {
        return {
            loading: {
                isActive: false,
            },
            itemProps: {
                design: itemDesignEnum.GRID,
                type: null,
            },
        };
    },
    mounted() {
        try {
            this.loading.isActive = true;
            this.$store.dispatch("frontendBranch/lists", {
                order_column: "id",
                order_type: "desc"
            }).then(res => {
                this.loading.isActive = false;
            }).catch((err) => {
                this.loading.isActive = false;
            });
        } catch (err) {
            this.loading.isActive = false;
        }
    },
    computed: {
        restaurants: function () {
            return this.$store.getters["frontendBranch/lists"];
        },
    },
    methods: {},
}
</script>
