<template>
    <LoadingComponent :props="loading" />
    <SmModalCreateComponent :props="addButton" />

    <div id="modal" class="modal">
        <div class="modal-dialog">
            <div class="modal-header">
                <h3 class="modal-title">{{ $t("menu.branches") }}</h3>
                <button class="modal-close fa-solid fa-xmark text-xl text-slate-400 hover:text-red-500"
                    @click="reset"></button>
            </div>
            <div class="modal-body">
                <form @submit.prevent="save">
                    <div class="form-row">
                        <div class="form-col-12 sm:form-col-6">
                            <label for="name" class="db-field-title required">{{
                                $t("label.name")
                            }}</label>
                            <input v-model="props.form.name" v-bind:class="errors.name ? 'invalid' : ''" type="text"
                                id="name" class="db-field-control" />
                            <small class="db-field-alert" v-if="errors.name">{{
                                errors.name[0]
                            }}</small>
                        </div>
                        <div class="form-col-12 sm:form-col-6">
                            <label for="image" class="db-field-title">{{ $t('label.image') }} (74px,48px)</label>
                            <input @change="changeImage" v-bind:class="errors.image ? 'invalid' : ''" id="image" type="file"
                                class="db-field-control" ref="imageProperty" accept="image/png, image/jpeg, image/jpg">
                            <small class="db-field-alert" v-if="errors.image">{{ errors.image[0] }}</small>
                        </div>
                        <div class="form-col-12 sm:form-col-6">
                            <label class="db-field-title" for="latitude">{{ $t("label.latitude") }}/{{
                                $t("label.longitude")
                            }}</label>
                            <div class="db-multiple-field">
                                <input v-model="props.form.latitude" v-bind:class="errors.latitude ? 'invalid' : ''
                                    " type="text" id="latitude" />
                                <input v-model="props.form.longitude" v-bind:class="errors.longitude ? 'invalid' : ''
                                    " type="text" id="longitude" />
                                <button @click="add" v-on:click="isMap = true" type="button"
                                    class="fa-solid fa-map-location-dot" data-modal="#branchMap"></button>
                            </div>

                            <small class="db-field-alert" v-if="errors.latitude">{{ errors.latitude[0] }}</small>
                            <small class="db-field-alert" v-if="errors.longitude">{{ errors.longitude[0] }}</small>
                        </div>

                        <div class="form-col-12 sm:form-col-6">
                            <label for="email" class="db-field-title">{{
                                $t("label.email")
                            }}</label>
                            <input v-model="props.form.email" v-bind:class="errors.email ? 'invalid' : ''" type="email"
                                id="email" class="db-field-control" />
                            <small class="db-field-alert" v-if="errors.email">{{
                                errors.email[0]
                            }}</small>
                        </div>
                        <div class="form-col-12 sm:form-col-6">
                            <label for="phone" class="db-field-title">{{
                                $t("label.phone")
                            }}</label>
                            <input v-model="props.form.phone" v-bind:class="errors.phone ? 'invalid' : ''" type="text"
                                id="phone" class="db-field-control" />
                            <small class="db-field-alert" v-if="errors.phone">{{
                                errors.phone[0]
                            }}</small>
                        </div>

                        <div class="form-col-12 sm:form-col-6">
                            <label for="city" class="db-field-title required">{{
                                $t("label.city")
                            }}</label>
                            <input v-model="props.form.city" v-bind:class="errors.city ? 'invalid' : ''" type="text"
                                id="city" class="db-field-control" />
                            <small class="db-field-alert" v-if="errors.city">{{
                                errors.city[0]
                            }}</small>
                        </div>

                        <div class="form-col-12 sm:form-col-6">
                            <label for="state" class="db-field-title required">{{ $t("label.state") }}</label>
                            <input v-model="props.form.state" v-bind:class="errors.state ? 'invalid' : ''" type="text"
                                id="state" class="db-field-control" />
                            <small class="db-field-alert" v-if="errors.state">{{
                                errors.state[0]
                            }}</small>
                        </div>

                        <div class="form-col-12 sm:form-col-6">
                            <label for="zip_code" class="db-field-title required">{{ $t("label.zip_code") }}</label>
                            <input v-model="props.form.zip_code" v-bind:class="errors.zip_code ? 'invalid' : ''" type="text"
                                id="zip_code" class="db-field-control" />
                            <small class="db-field-alert" v-if="errors.zip_code">{{ errors.zip_code[0] }}</small>
                        </div>

                        <div class="form-col-12 sm:form-col-6">
                            <label for="zip_code" class="db-field-title required">FOOD PREPARATION TIME (Min)</label>
                            <input v-model="props.form.time_to_prepare" v-bind:class="errors.time_to_prepare ? 'invalid' : ''" type="number"
                                id="time_to_prepare" class="db-field-control" />
                            <small class="db-field-alert" v-if="errors.time_to_prepare">{{ errors.time_to_prepare[0] }}</small>
                        </div>

                        <div class="form-col-12 sm:form-col-6">
                            <label for="zip_code" class="db-field-title">DELIVERY CHARGE</label>
                            <input v-model="props.form.delivery_charge" v-bind:class="errors.delivery_charge ? 'invalid' : ''" type="text"
                                id="delivery_charge" class="db-field-control" />
                            <small class="db-field-alert" v-if="errors.delivery_charge">{{ errors.delivery_charge[0] }}</small>
                        </div>

                        <div class="form-col-12 sm:form-col-6">
                            <label class="db-field-title required" for="active">{{ $t("label.status") }}</label>
                            <div class="db-field-radio-group">
                                <div class="db-field-radio">
                                    <div class="custom-radio">
                                        <input :value="enums.statusEnum.ACTIVE" v-model="props.form.status" id="active"
                                            type="radio" class="custom-radio-field" />
                                        <span class="custom-radio-span"></span>
                                    </div>
                                    <label for="active" class="db-field-label">{{ $t("label.active") }}</label>
                                </div>
                                <div class="db-field-radio">
                                    <div class="custom-radio">
                                        <input :value="enums.statusEnum.INACTIVE" v-model="props.form.status" type="radio"
                                            id="inactive" class="custom-radio-field" />
                                        <span class="custom-radio-span"></span>
                                    </div>
                                    <label for="inactive" class="db-field-label">{{ $t("label.inactive") }}</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-col-12">
                            <label for="address" class="db-field-title required">{{ $t("label.address") }}</label>
                            <textarea v-model="props.form.address" v-bind:class="errors.address ? 'invalid' : ''"
                                id="address" class="db-field-control"></textarea>
                            <small class="db-field-alert" v-if="errors.address">{{ errors.address[0] }}</small>
                        </div>

                        <div class="form-col-12">
                            <div class="modal-btns">
                                <button type="button" class="modal-btn-outline modal-close" @click="reset">
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
    <div id="branchMap" class="modal">
        <div class="modal-dialog">
            <div class="modal-header">
                <h3 class="modal-title">{{ $t("label.address") }}</h3>
                <button class="modal-close fa-solid fa-xmark text-xl text-slate-400 hover:text-red-500"
                    @click="mapReset"></button>
            </div>
            <div class="modal-body">
                <form @submit.prevent="save">
                    <div class="form-row">
                        <div class="form-col-12 map-height">
                            <MapComponent v-if="isMap" :location="{ lat: props.form.latitude, lng: props.form.longitude }"
                                :position="location" />
                        </div>

                        <div class="form-col-12">
                            <label for="apartment" class="db-field-title font-medium text-sm my-0">
                                {{ address }}
                            </label>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
<script>
import SmModalCreateComponent from "../../components/buttons/SmModalCreateComponent";
import LoadingComponent from "../../components/LoadingComponent";
import statusEnum from "../../../../enums/modules/statusEnum";
import alertService from "../../../../services/alertService";
import appService from "../../../../services/appService";
import MapComponent from "../../../admin/components/MapComponent";

export default {
    name: "BranchCreateComponent",
    components: { SmModalCreateComponent, LoadingComponent, MapComponent },
    props: ["props"],
    data() {
        return {
            loading: {
                isActive: false,
            },

            addButton: {
                title: this.$t("button.add_branch"),
            },
            enums: {
                statusEnum: statusEnum,
                statusEnumArray: {
                    [statusEnum.ACTIVE]: this.$t("label.active"),
                    [statusEnum.INACTIVE]: this.$t("label.inactive"),
                },
            },
            isMap: false,
            address: "",
            image: "",
            errors: {},
        };
    },
    methods: {
        changeImage: function (e) {
            this.image = e.target.files[0];
        },
        add: function () {
            appService.modalShow('#branchMap');
        },
        location: function (e) {
            this.address = e.address;
            this.props.form.latitude = e.location.lat;
            this.props.form.longitude = e.location.lng;
            this.props.form.city = e.other.city;
            this.props.form.state = e.other.state;
            this.props.form.zip_code = e.other.zipCode;
            this.props.form.address = e.address;
        },
        reset: function () {
            appService.modalHide();
            this.$store.dispatch("branch/reset").then().catch();
            this.errors = {};
            this.$props.props.form = {
                name: "",
                email: "",
                phone: "",
                latitude: "",
                longitude: "",
                city: "",
                state: "",
                zip_code: "",
                address: "",
                time_to_prepare: "",
                delivery_charge: "",
                status: statusEnum.ACTIVE,
            };
            if (this.image) {
                this.image = "";
                this.$refs.imageProperty.value = null;
            }
        },
        mapReset: function () {
            appService.modalHide('#branchMap');

        },


        save: function () {
            try {
                const fd = new FormData();
                fd.append('name', this.props.form.name);
                fd.append('email', this.props.form.email);
                fd.append('phone', this.props.form.phone);
                fd.append('latitude', this.props.form.latitude);
                fd.append('longitude', this.props.form.longitude);
                fd.append('city', this.props.form.city);
                fd.append('state', this.props.form.state);
                fd.append('zip_code', this.props.form.zip_code);
                fd.append('address', this.props.form.address);
                fd.append('status', this.props.form.status);
                fd.append('time_to_prepare', this.props.form.time_to_prepare);
                fd.append('delivery_charge', this.props.form.delivery_charge);

                if (this.image) {
                    fd.append('image', this.image);
                }

                const tempId = this.$store.getters["branch/temp"].temp_id;
                this.loading.isActive = true;

                // Adjusting the dispatch to send FormData
                this.$store.dispatch('branch/save', {
                    form: fd,
                    search: this.props.search // Preserving this part if it's necessary in your context
                }).then((res) => {
                    appService.modalHide();
                    this.loading.isActive = false;
                    alertService.successFlip((tempId === null ? 0 : 1), this.$t('menu.branches'));

                    this.props.form = {
                        name: "",
                        email: "",
                        phone: "",
                        latitude: "",
                        longitude: "",
                        city: "",
                        state: "",
                        zip_code: "",
                        address: "",
                        time_to_prepare: "",
                        delivery_charge: "",
                        status: statusEnum.ACTIVE,
                    };
                    this.image = "";
                    this.errors = {};
                    this.$refs.imageProperty.value = null; // Resetting image input

                }).catch((err) => {
                    this.loading.isActive = false;
                    this.errors = err.response.data.errors;
                });

            } catch (err) {
                this.loading.isActive = false;
                alertService.error(err);
            }
        },

    },
};
</script>
