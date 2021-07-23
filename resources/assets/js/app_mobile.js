require('./bootstrap');

window.Vue                  = require('vue');
Vue.prototype.$lang         = document.documentElement.getAttribute('lang');
Vue.prototype.$currency     = document.documentElement.getAttribute('currency');
Vue.prototype.$currencyRate = document.documentElement.getAttribute('currency-rate');
Vue.prototype.$mainCurrency = document.documentElement.getAttribute('main-currency');
Vue.prototype.$device       = document.documentElement.getAttribute('device');
Vue.prototype.trans         = trans;

export const bus = new Vue();
import BootstrapVue from 'bootstrap-vue';
import Fragment     from 'vue-fragment';

Vue.component('google-events-mob', require('./components/mobile/GoogleComponent.vue'));

// home components
Vue.component('home-sliders-mob',           require('./components/mobile/home/HomeSliders.vue'));
Vue.component('home-slider-category-mob',   require('./components/mobile/home/HomeSliderCategory.vue'));
Vue.component('home-slider-collection-mob', require('./components/mobile/home/HomeSliderCollection.vue'));
// product/sets components
Vue.component('category-mob',           require('./components/mobile/CategoryComponent.vue'));
Vue.component('parameters-filter-mob',  require('./components/mobile/ParametersFilterComponent.vue'));
Vue.component('filter-block',                 require('./components/mobile/Filter.vue'));
Vue.component('sort-block',                 require('./components/mobile/Sort.vue'));
Vue.component('new-mob',                require('./components/mobile/NewComponent.vue'));
Vue.component('sale-mob',               require('./components/mobile/SaleComponent.vue'));
Vue.component('product-mobile',         require('./components/mobile/ProductComponent.vue'));
Vue.component('promo-sets-mobile',      require('./components/mobile/PromoSets.vue'));

// Vue.component('collection-mobile',      require('./components/mobile/CollectionComponent.vue'));
// Vue.component('set-mobile',             require('./components/mobile/SetComponent.vue'));

// cart components
Vue.component('cart-mob',           require('./components/mobile/cart/CartComponent.vue'));
Vue.component('cart-summary-mob',   require('./components/mobile/cart/CartSummary.vue'));
Vue.component('cart-counter-mob',   require('./components/mobile/cart/CartCounter.vue'));
// auth components
Vue.component('reset-password-mob', require('./components/mobile/auth/ResetPasswordComponent.vue'));
Vue.component('auth-mob',           require('./components/mobile/auth/Auth.vue'));
// order components
Vue.component('order-mob',          require('./components/mobile/OrderShippingComponent.vue'));
Vue.component('order-payment-mob',  require('./components/mobile/OrderPaymentComponent.vue'));
Vue.component('alerts-mob',         require('./components/mobile/AlertsComponent.vue'));
Vue.component('settings-btn-mob', require('./components/mobile/SettingsButon.vue'));
Vue.component('settings-modal-mob', require('./components/mobile/SettingsModal.vue'));

Vue.component('collection', require('./components/mobile/collections/Collections.vue'));
Vue.component('set', require('./components/mobile/collections/Set.vue'));
Vue.component('set-modal', require('./components/mobile/collections/SetModal.vue'));


setTimeout(function(vm){
    $('.sniper-load').fadeOut();
    document.querySelector('body').classList.remove('scroll-stop');
}, 1700);

const app = new Vue({
    el: '#cover-mob'
});
