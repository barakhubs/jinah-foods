<template>
    <LoadingComponent :props="loading"/>
    <section class="mb-16 mt-8">
        <div class="container">

            <div v-if="Object.keys(restaurant).length > 0"
                 class="flex gap-4 flex-col sm:flex-row items-center justify-between mb-6">
                <h2 class="capitalize text-[26px] leading-[40px] font-semibold text-center sm:text-left text-primary">{{
                        restaurant.name
                    }}</h2>
            </div>

            <ItemComponent :items="items.items" :type="itemProps.type" :design="itemProps.design"/>
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
    name: "MenuComponent",
    components: {RestaurantComponent, ItemComponent, LoadingComponent},
    data() {
        return {
            loading: {
                isActive: false
            },
            itemTypeEnum: itemTypeEnum,
            itemDesignEnum: itemDesignEnum,
            restaurant: {},
            items: {},
            restaurantProps: {
                search: {
                    paginate: 0,
                    order_column: 'id',
                    order_type: 'asc',
                    status: statusEnum.ACTIVE
                },
                design: restaurantDesignEnum.SECOND
            },
            itemProps: {
                design: itemDesignEnum.GRID,
                type: null
            }
        }
    },
    computed: {
        restaurants: function () {
            return this.$store.getters['frontendItemRestaurant/lists'];
        },
        setting: function () {
            return this.$store.getters['frontendSetting/lists'];
        }
    },
    mounted() {
        if (typeof this.$route.params.id !== "undefined" && this.$route.params.id !== "") {
            this.loading.isActive = true;
            this.$store.dispatch("frontendItemRestaurant/show", {
                id: this.$route.params.id,
            }).then((res) => {
                this.loading.isActive = false;
                this.restaurant = res.data.data;
                this.items = res.data.data;
                this.loading.isActive = false;
            }).catch((err) => {
                this.loading.isActive = false;
            });
        }
    },

}
</script>
