import Vue from 'vue';
import Vuex from 'vuex';
import axios from 'axios'

Vue.use(Vuex);

export const store = new Vuex.Store({
    strict: process.env.NODE_ENV !== 'production',
    state: {
        authenticated: false,
        user: null,
        error: "",
    },
    actions: {
        async signIn({ dispatch }, credentials) {
            await axios.get('sanctum/csrf-cookie')
            await axios.post('api/login', credentials)

            return dispatch('me')
        },

        async signOut({ dispatch }) {
            await axios.post('/logout')

            return dispatch('me')
        },

        me({ commit }) {
            return axios.get('api/user').then((response) => {
                commit('SET_AUTHENTICATED', true)
                commit('SET_USER', response.data)

            }).catch(() => {
                commit('SET_AUTHENTICATED', false)
                commit('SET_USER', null)
            })
        },
        SET_ERROR: (context, errorMsg) => {
            context.commit("POST_ERROR", errorMsg);
        },
    },
    mutations: {
        SET_AUTHENTICATED(state, value) {
            state.authenticated = value
        },
        SET_USER(state, value) {
            state.user = value
        },
        POST_ERROR: (state, payload) => {
            state.error = payload;
        },
    },
    getters: {
        authenticated(state) {
            console.log(state)
            return state.authenticated
        },

        user(state) {
            return state.user
        },
    },
    modules: {

    },
});