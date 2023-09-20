<template>
    <LoadingComponent :props="loading" />

    <!--========TRACK PART START===========-->
    <TrackOrderComponent />
    <!--========TRACK PART END=============-->


    <!--========BANNER PART START===========-->
    <SliderComponent />
    <!--========BANNER PART END=============-->

    <!--========Restaurant PART START=========-->
    <section v-if="restaurants.length > 0" class="mb-12">
        <div class="container">


            <div class="flex items-center justify-between gap-2 mb-6 mt-4">
                <h2 class="text-2xl font-semibold capitalize">Restaurants</h2>
                <router-link :to="{ name: 'frontend.restaurant', query: { s: restaurantProps.id } }"
                    class="rounded-3xl capitalize text-sm leading-6 font-medium py-1 px-3 transition text-primary bg-[#FFEDF4] hover:text-white hover:bg-primary">
                    {{ $t("button.view_all") }}
                </router-link>
            </div>
            <div class="swiper menu-swiper">
                <RestaurantItemComponent :restaurants="restaurants" :design="restaurantProps.design" />
            </div>
        </div>
    </section>
    <!--========Restaurant PART END===========-->

    <!--========LATEST PART START=========-->
    <FeaturedItemComponent />
    <!--========LATEST PART END=========-->

    <!--========FEATURE PART START=========-->
    <LatestItemComponent/>

    <!--========OFFER PART START=========-->
    <OfferComponent :limit="limit" />
    <!--========OFFER PART START=========-->

    <!--========POPULAR PART START=========-->
    <PopularItemComponent />
    <!--========POPULAR PART START=========-->
</template>

<script>
import SliderComponent from "../../frontend/home/SliderComponent";
import RestaurantItemComponent from "../components/RestaurantItemComponent";
import FeaturedItemComponent from "../home/FeaturedItemComponent";
import PopularItemComponent from "../home/PopularItemComponent";
import OfferComponent from "../components/OfferComponent";
import restaurantDesignEnum from "../../../enums/modules/restaurantDesignEnum";
import statusEnum from "../../../enums/modules/statusEnum";
import LoadingComponent from "../components/LoadingComponent";
import TrackOrderComponent from "./TrackOrderComponent";
import LatestItemComponent from "../home/LatestItemComponent.vue";

export default {
    name: "HomeComponent",
    components: {
    TrackOrderComponent,
    RestaurantItemComponent,
    SliderComponent,
    FeaturedItemComponent,
    PopularItemComponent,
    OfferComponent,
    LoadingComponent,
    LatestItemComponent
},
    data() {
        return {
            loading: {
                isActive: false,
            },
            restaurantProps: {
                design: 5,
                id: '',
            },
            limit: 4,
        };
    },
    computed: {
        restaurants: function () {
            return this.$store.getters["frontendItemRestaurant/lists"];
        },
    },
    mounted() {
        this.loading.isActive = true;
        this.$store.dispatch("frontendItemRestaurant/lists", {
            paginate: 0,
            order_column: "id",
            order_type: "asc",
            status: statusEnum.ACTIVE,
        }).then(res => {
            this.loading.isActive = false;
        }).catch((err) => {
            this.loading.isActive = false;
        });
    },
    watch: {
        restaurants: {
            deep: true,
            handler(restaurant) {
                if (restaurant.length > 0) {
                    if (restaurant[0].id !== "undefined") {
                        this.restaurantProps.id = restaurant[0].id;
                    }
                }
            },
        },
    },
};
</script>
