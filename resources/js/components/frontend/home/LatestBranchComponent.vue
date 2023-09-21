<template>
    <LoadingComponent :props="loading" />
    <section class="mb-12">
        <div class="container" v-if="latestItems.length > 0">
            <h2 class="text-2xl font-semibold capitalize mb-6">New Restaurants</h2>
            <RestaurantComponent :items="latestItems" :type="itemProps.type" :design="itemProps.design" />
        </div>
    </section>
</template>
<script>

import alertService from "../../../services/alertService";
import itemDesignEnum from "../../../enums/modules/itemDesignEnum";
import RestaurantComponent from "../components/RestaurantComponent";
import LoadingComponent from "../components/LoadingComponent";

export default {
    name: "LatestBranchComponent",
    components: {
        RestaurantComponent,
        LoadingComponent
    },
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
            this.$store.dispatch("frontendBranch/latest", {
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
        latestItems: function () {
            return this.$store.getters["frontendBranch/latest"];
        },
    },
    methods: {},
};
</script>
