<template>
    <LoadingComponent :props="loading" />
    <!-- Button for 'admin.order.list' -->
    <button v-if="$route.name === 'admin.order.list'" type="button" @click="reasonModal" data-modal="#reasonModal"
        class="db-table-action delete">
        <i class="lab lab-close"></i>
        <span class="db-tooltip">Reject</span>
    </button>

    <!-- Button for all other cases -->
    <button v-else type="button" @click="reasonModal" data-modal="#reasonModal"
        class="flex items-center justify-center text-white gap-2 px-4 h-[38px] rounded shadow-db-card bg-[#FB4E4E]">
        <i class="lab lab-close"></i>
        <span class="text-sm capitalize text-white">{{ $t('button.reject') }}</span>
    </button>



    <div id="reasonModal" class="modal">
        <div class="modal-dialog">
            <div class="modal-header">
                <h3 class="modal-title">{{ $t("label.reason") }}</h3>
                <button class="modal-close fa-solid fa-xmark text-xl text-slate-400 hover:text-red-500"
                    @click.prevent="resetModal"></button>
            </div>
            <div class="modal-body">
                <form @submit.prevent="rejectOrder">
                    <div class="form-row">
                        <div class="form-col-12">
                            <label for="name" class="db-field-title">
                                {{ $t("label.reason") }}
                            </label>
                            <input v-model="form.reason" v-bind:class="error ? 'invalid' : ''" type="text" id="name"
                                class="db-field-control" />
                            <small class="db-field-alert" v-if="error">
                                {{ error }}
                            </small>
                        </div>
                        <div class="form-col-12">
                            <div class="modal-btns">
                                <button type="button" class="modal-btn-outline modal-close" @click.prevent="resetModal">
                                    <i class="lab lab-close"></i>
                                    <span>{{ $t("button.close") }}</span>
                                </button>

                                <button type="submit" class="db-btn py-2 text-white bg-primary">
                                    <i class="lab lab-save"></i>
                                    <span>{{ $t("button.save") }}</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import appService from "../../../services/appService";
import alertService from "../../../services/alertService";
import orderStatusEnum from "../../../enums/modules/orderStatusEnum";
import LoadingComponent from "../components/LoadingComponent";

export default {
    name: "OnlineOrderReasonComponent",
    components: {
        LoadingComponent
    },
    props: {
        id: {
            type: [String, Number],
            default: null,
        }
    },
    data() {
        return {
            loading: {
                isActive: false
            },
            form: {
                reason: ""
            },
            error: ""
        }
    },
    methods: {
        reasonModal: function () {
            appService.modalShow('#reasonModal');
        },
        resetModal: function () {
            appService.modalHide('#reasonModal');
            this.form.reason = '';
            this.error = "";
        },
        rejectOrder: function () {
            console.log("Route Params ID:", this.$route.params.id);  // Debugging line
            console.log("Props ID:", this.id);  // Debugging line
            console.log("Route Name:", this.$route.name);  // Debugging line

            const orderId = this.$route.name === 'admin.order.list' ? this.id : this.$route.params.id;

            console.log("Selected ID:", orderId);  // Debugging line
            try {
                this.loading.isActive = true;
                this.$store.dispatch("onlineOrder/changeStatus", {
                    id: orderId, // Conditionally set id here
                    status: orderStatusEnum.REJECTED,
                    reason: this.form.reason,
                }).then((res) => {
                    this.loading.isActive = false;
                    appService.modalHide();
                    this.form = {
                        reason: "",
                    };
                    this.error = "";
                    alertService.successFlip(
                        1,
                        this.$t("label.status")
                    );
                    if (this.$route.name === 'admin.order.list') {
                        this.$emit('orderUpdated');
                    }
                }).catch((err) => {
                    this.loading.isActive = false;
                    this.error = err.response.data.message;
                });
            } catch (err) {
                this.loading.isActive = false;
                alertService.error(err.response.data.message);
            }
        },
    }
}
</script>
