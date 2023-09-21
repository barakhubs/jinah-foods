import axios from "axios";
import appService from "../../../services/appService";

export const frontendBranch = {
    namespaced: true,
    state: {
        lists: [],
        show: {},
        latest: [],
        popular: {},
    },
    getters: {
        lists: function (state) {
            return state.lists;
        },
        show: function (state) {
            return state.show;
        },
        latest: function (state) {
            return state.latest;
        },
        popular: function (state) {
            return state.popular;
        },
    },
    actions: {
        lists: function (context, payload) {
            return new Promise((resolve, reject) => {
                let url = "frontend/branch";
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
                    axios.get(`frontend/branch/show/${payload}`).then((res) => {
                        context.commit("show", res.data.data);
                        resolve(res);
                    }).catch((err) => {
                        reject(err);
                    });
                });
            }
        },
        latest: function (context, payload) {
            return new Promise((resolve, reject) => {
                let url = "frontend/item-restaurant/latest-branches";
                if (payload) {
                    url = url + appService.requestHandler(payload);
                }
                axios.get(url).then((res) => {
                    if (typeof payload.vuex === "undefined" || payload.vuex === true) {
                        context.commit("latest", res.data.data);
                    }
                    resolve(res);
                }).catch((err) => {
                    reject(err);
                });
            });
        },
        popular: function (context, payload) {
            return new Promise((resolve, reject) => {
                let url = "frontend/item-restaurant/popular-branches";
                if (payload) {
                    url = url + appService.requestHandler(payload);
                }
                axios.get(url).then((res) => {
                    if (typeof payload.vuex === "undefined" || payload.vuex === true) {
                        context.commit("popular", res.data.data);
                    }
                    resolve(res);
                }).catch((err) => {
                    reject(err);
                });
            });
        },
    },
    mutations: {
        lists: function (state, payload) {
            state.lists = payload;
        },
        show: function (state, payload) {
            state.show = payload;
        },
        latest: function (state, payload) {
            state.latest = payload;
        },
        popular: function (state, payload) {
            state.popular = payload;
        }
    },
};
