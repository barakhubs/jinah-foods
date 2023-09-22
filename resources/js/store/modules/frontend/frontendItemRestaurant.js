import axios from "axios";
import appService from "../../../services/appService";

export const frontendItemRestaurant = {
    namespaced: true,
    state: {
        lists: [],
        show: {},
    },
    getters: {
        lists: function (state) {
            return state.lists;
        },
        show: function (state) {
            return state.show;
        },
    },
    actions: {
        lists: function (context, payload) {
            return new Promise((resolve, reject) => {
                let url = "frontend/item-restaurant";
                if (payload) {
                    url = url + appService.requestHandler(payload);
                }
                axios.get(url).then((res) => {
                    if (typeof payload.vuex === "undefined" || payload.vuex === true) {
                        context.commit("lists", res.data.data);
                    }
                    resolve(res);
                }).catch((err) => {
                    reject(err);
                });
            });
        },
        show: function (context, payload) {
            if(payload) {
                return new Promise((resolve, reject) => {
                    axios.get(`frontend/item-restaurant/show/${payload.id}`).then((res) => {
                        if (typeof payload.vuex === "undefined" || payload.vuex === true) {
                            context.commit("show", res.data.data);
                        }
                        resolve(res);
                    }).catch((err) => {
                        reject(err);
                    });
                });
            }
            else {
                console.error("Invalid payload:", payload);
                return Promise.reject("Invalid payload");
            }
        },
    },
    mutations: {
        lists: function (state, payload) {
            state.lists = payload;
        },
        show: function (state, payload) {
            state.show = payload;
        }
    },
};
