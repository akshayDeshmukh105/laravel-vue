require('./bootstrap');
import Vue from "vue";
import VueRouter from "vue-router";
import Vuex from 'vuex';
import StoreData from './store';
import { routes } from "./routes";
import MainApp from './components/MainApp'
import config from "./config";

Vue.use(VueRouter)
Vue.use(Vuex)

const store = new Vuex.Store(StoreData);
const router = new VueRouter({
    routes,
    mode: "history"
});

router.beforeEach((toRoute, fromRoute, nextRoute) => {
    const requiredAuth = toRoute.matched.some(record => record.meta.auth)
    const currentUser = store.state.currentUser

    if (toRoute.name) {
        if (requiredAuth && !currentUser) {
            nextRoute(config.host + '/login')
        } else if (toRoute.path === (config.host + '/login') && currentUser){
            nextRoute(config.host)
        } else {
            nextRoute()
        }
    } else {
        nextRoute(config.host)
    }
})

router.afterEach((toRoute, fromRoute) => {
    window.document.title = toRoute.name.toUpperCase()
})

const app = new Vue({
    el: '#app',
    router,
    store,
    components: {
        MainApp
    }
});
