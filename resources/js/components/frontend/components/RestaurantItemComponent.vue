<template>
    <div class="swiper-wrapper" :class="design === 10 ? 'menu-slides' : ''">
        <Carousel :settings="settings" :breakpoints="breakpoints">
            <slide class="swiper-slide" v-for="restaurant in restaurants" :key="restaurant">
                <router-link  v-if="design === 5"
                             :to="{name: 'frontend.restaurant', query:{ id: restaurant.id}}"
                             class="swiper-slide w-32 flex flex-col items-center text-center gap-4 p-3 c-h-30 rounded-2xl border-b-2 border-transparent transition hover:bg-[#FFEDF4] bg-[#F7F7FC]">
                    <img class="h-12 drop-shadow-category" :src="restaurant.thumb" :alt="restaurant.id">
                    <h3 class="text-xs font-medium">{{ restaurant.name }}</h3>
                </router-link>

                <router-link :class="checkIsQueryAndRouteQuerySame(restaurant.id) ? 'menu-category-active' : ''" v-else-if="design === restaurantDesignEnum.SECOND"
                             :to="{name: 'frontend.restaurant', query:{ s: restaurant.id}}"
                             class="swiper-slide w-32 flex flex-col items-center text-center gap-4 p-3 c-h-25 rounded-2xl border-b-2 border-transparent transition hover:bg-[#FFEDF4]">
                    <img class="h-9 drop-shadow-category" :src="restaurant.thumb" alt="restaurant">
                    <h3 class="text-xs font-medium">{{ restaurant.name }}</h3>
                </router-link>
            </slide>
        </Carousel>
    </div>
</template>

<script>

import restaurantDesignEnum from "../../../enums/modules/restaurantDesignEnum";
import 'vue3-carousel/dist/carousel.css';
import {Carousel, Slide, Pagination, Navigation} from 'vue3-carousel'

export default {
    name: "RestaurantItemComponent",
    props: {
        restaurants: Object,
        design: Number
    },
    components: {
        Carousel,
        Slide,
        Pagination,
        Navigation,
    },
    data() {
        return {
            currentRestaurant: "",
            restaurantDesignEnum: restaurantDesignEnum,
            settings: {
                itemsToShow: 8,
                wrapAround: false,
                snapAlign: "start"
            },
            breakpoints: {
                // 200px and up
                200: {
                    itemsToShow: 1.1,
                    wrapAround: false,
                    snapAlign: 'start',
                },
                // 250px and up
                250: {
                    itemsToShow: 1.5,
                    wrapAround: false,
                    snapAlign: 'start',
                },
                // 300px and up
                300: {
                    itemsToShow: 2,
                    wrapAround: false,
                    snapAlign: 'start',
                },
                // 375px and up
                375: {
                    itemsToShow: 2.5,
                    wrapAround: false,
                    snapAlign: 'start',
                },
                540: {
                    itemsToShow: 3.5,
                    wrapAround: false,
                    snapAlign: 'start',
                },
                // 700px and up
                700: {
                    itemsToShow: 4.5,
                    wrapAround: false,
                    snapAlign: 'start',
                },
                // 1024 and up
                1024: {
                    snapAlign: 'start',
                    itemsToShow: 7,
                    wrapAround: false,
                },
                // 1180 and up
                1180: {
                    snapAlign: 'start',
                    itemsToShow: 8,
                    wrapAround: false,
                }
            },
        }
    },
    mounted() {
        if(this.$route.query.s !== "undefined") {
            this.currentRestaurant = this.$route.query.s;
        }
    },
    methods: {
        submit: function (msg, e) {
            e.stopPropagation()
        },
        checkIsQueryAndRouteQuerySame(query) {
            if (this.currentRestaurant === query) {
                return true;
            }
        },
    },
    watch: {
        $route(to, from) {
            if(to.query.s !== "undefined") {
                this.currentRestaurant = to.query.s;
            }
        }
    }
}
</script>
