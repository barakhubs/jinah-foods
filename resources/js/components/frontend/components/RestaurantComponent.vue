<template>
    <!--========ITEM PART START=========-->
    <div v-if="design === itemDesignEnum.GRID" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3 lg:gap-6">
        <div v-for="item in items" :key="item" v-show="type === null || type === item.item_type" class="product-card-grid">
            <router-link :class="checkIsQueryAndRouteQuerySame(item.id) ? 'menu-category-active' : ''"
                v-if="design === itemDesignEnum.GRID" :to="{ name: 'frontend.restaurant', query: { s: item.id } }">
                <img class="product-card-grid-image" :src="item.cover" alt="restaurant">
                <div class="product-card-grid-content-group">
                    <div class="product-card-grid-header-group justify-content-center">
                        <h3 class="product-card-grid-title">{{ textShortener(item.name, 26) }}</h3>
                    </div>
                </div>
            </router-link>
        </div>
    </div>
    <!--========ITEM PART END===========-->
</template>
<script>
import itemDesignEnum from "../../../enums/modules/itemDesignEnum";
import appService from "../../../services/appService";
import 'vue3-carousel/dist/carousel.css';
import { Carousel, Slide, Pagination, Navigation } from 'vue3-carousel';
import _ from 'lodash';
import alertService from "../../../services/alertService";

export default {
    name: "RestaurantComponent",
    components: {
        Carousel,
        Slide,
        Pagination,
        Navigation,
    },
    props: {
        items: Object,
        design: Number,
        type: Number
    },
    data() {
        return {
            item: null,
            itemInfo: null,
            addons: {},
            addonQuantity: {},
            itemArrays: [],
            itemDesignEnum: itemDesignEnum,
            settings: {
                itemsToShow: 4.3,
                wrapAround: false,
                snapAlign: "start"
            },
            addonSettings: {
                itemsToShow: 3,
                wrapAround: false,
                snapAlign: "start"
            },
            temp: {
                name: "",
                image: "",
                item_id: 0,
                quantity: 0,
                discount: 0,
                currency_price: 0,
                convert_price: 0,
                item_variations: {
                    variations: {},
                    names: {}
                },
                item_extras: {
                    extras: [],
                    names: []
                },
                item_variation_total: 0,
                item_extra_total: 0,
                total_price: 0,
                instruction: "",
            },
            itemBreakpoints: {
                200: {
                    itemsToShow: 1.1,
                    wrapAround: false,
                    snapAlign: 'start',
                },
                250: {
                    itemsToShow: 1.5,
                    wrapAround: false,
                    snapAlign: 'start',
                },
                300: {
                    itemsToShow: 2.2,
                    wrapAround: false,
                    snapAlign: 'start',
                },
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
                700: {
                    itemsToShow: 4.3,
                    wrapAround: false,
                    snapAlign: 'start',
                }
            },
            addonBreakpoints: {
                200: {
                    itemsToShow: 1.1,
                    wrapAround: false,
                    snapAlign: 'start',
                },
                250: {
                    itemsToShow: 1.3,
                    wrapAround: false,
                    snapAlign: 'start',
                },
                300: {
                    itemsToShow: 1.4,
                    wrapAround: false,
                    snapAlign: 'start',
                },
                375: {
                    itemsToShow: 1.7,
                    wrapAround: false,
                    snapAlign: 'start',
                },
                540: {
                    itemsToShow: 2.5,
                    wrapAround: false,
                    snapAlign: 'start',
                },
                700: {
                    itemsToShow: 3,
                    wrapAround: false,
                    snapAlign: 'start',
                }
            },
        }
    },
    computed: {
        setting: function () {
            return this.$store.getters['frontendSetting/lists'];
        },
    },
    methods: {
        textShortener: function (text, number) {
            return appService.textShortener(text, number);
        },
        checkIsQueryAndRouteQuerySame(query) {
            if (this.currentRestaurant === query) {
                return true;
            }
        },
    }
}
</script>
