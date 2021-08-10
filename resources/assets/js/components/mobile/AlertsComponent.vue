<template>
    <div>
        <div class="load-wrapper" v-if="spiner">
            <div class="sniper-load-inner">
                <div class="loader"></div>
            </div>
        </div>

        <div class="modals">
            <div class="modal" id="alertProdsModal">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modalContent">
                            <div class="closeModal" data-dismiss="modal">
                                <img src="/fronts/img/icons/plusIconBlack.svg" alt="" />
                            </div>

                            <div class="row" v-if="inactiveProdsModal">
                                <div class="col-md-12">
                                    <h5 class='text-center'>
                                        {{ trans.vars.Notifications.errorPartStockOutOfStock }}
                                    </h5>
                                    <p>
                                        {{ trans.vars.Orders.productsInStock }}
                                    </p>
                                    <ul>
                                        <li v-for="cartProduct in carts.products">&#8226; {{ cartProduct.product.translation.name }}</li>
                                        <li v-for="cartSubproduct in carts.subproducts">&#8226; {{ cartSubproduct.subproduct.product.translation.name }}</li>
                                    </ul>
                                    <div class="bloc last text-right">
                                        <span class="title">{{ trans.vars.Orders.orderTotal }}</span>
                                        <span>{{ personalAmount }} {{ $currency }}</span>
                                    </div>
                                </div>
                                <div class="col-md-12 d-flex justify-content-center">
                                    <button type="button"  class="butt onSubmit" @click="redirectToPayment">
                                        OK
                                        <i>({{ counter }})</i>
                                    </button>
                                </div>
                            </div>

                            <div class="row" v-if="emptyCart">
                                <div class="col-md-12">
                                    <h5 class='text-center'>
                                        {{ trans.vars.Notifications.errorAllStockOutOfStock }}
                                    </h5>
                                </div>
                                <div class="col-md-12 d-flex justify-content-center">
                                    <button type="button"  class="butt onSubmit" @click="redirectToHome">
                                        OK
                                        <i>({{ counter }})</i>
                                    </button>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
import { bus } from '../../app_mobile';

export default {
    props: ['mode'],
    data() {
        return {
            spiner: false,
            carts: [],
            incativeCarts: [],
            changedQtyCarts: [],
            inactiveProdsModal: false,
            changeQtyProdsModal: false,
            emptyCart: false,
            counter: 10,
            amount: 0,
            personalAmount: 0,
            payment: false,
        }
    },
    mounted(){
        bus.$on("validateStocks", data => {
            this.validateStocks();
            this.payment = data
        });
        bus.$on("shareAmount", data => {
            this.amount = data.amount;
            this.personalAmount = data.amountPersonal;
        });
    },
    methods: {
        validateStocks(){
            this.spiner = true;
            axios
                .post("/" + this.$lang + "/homewear/order-validate-stocks", {
                    order_id: this.order_id,
                })
                .then((response) => {
                    bus.$emit('updateCart');
                    this.incativeCarts = response.data.inactiveCarts;
                    this.carts = response.data.carts;
                    this.spiner = false;

                    if (this.carts.products.length == 0 && this.carts.subproducts.length == 0) {  // if empty cart
                        this.emptyCart = true;
                        $("#alertProdsModal").modal("show");
                        setInterval(data => {
                            if (this.counter > 0) {
                                this.counter--;
                            }else{
                                this.redirectToHome();
                            }
                        }, 1000);
                    }else{
                        if (this.incativeCarts.products.length > 0 || this.incativeCarts.subproducts.length > 0) {
                            this.inactiveProdsModal = true;
                             $("#alertProdsModal").modal("show");
                             setInterval(data => {
                                 if (this.counter > 0) {
                                     this.counter--;
                                 }else{
                                     this.redirectToPayment();
                                 }
                             }, 1000);
                        }else{
                            this.redirectToPayment();
                        }
                    }
                })
                .catch((e) => {
                    this.spiner = false;
                    console.log('error go to pay');
                });
        },
        redirectToHome(){
            window.location.href = window.location.origin + "/" + this.$lang;
        },
        redirectToPayment(){
            bus.$emit("ga-event-addPAymentInfo", {
                payment: this.payment,
                products: this.carts.products,
                subproducts: this.carts.subproducts
            });
            bus.$emit('redirectToPayment');
        },
    },
}
</script>

<style media="screen">
    .load-wrapper{
        position: fixed;
        width: 100%;
        height: 100vh;
        background-color: rgba(0, 0, 0, 0.6);
        top: 0;
        left: 0;
        z-index: 2;
        overflow: hidden;
    }
    .load-wrapper .sniper-load-inner{
        background-color: transparent !important;
    }
    .load-wrapper .loader {
        position: absolute;
        top: 40%;
        left: 50%;
        margin-left: -5em;
        margin-top: -5em;
    }
    .loader,
    .loader:after {
        border-radius: 50%;
        width: 10em;
        height: 10em;
    }
    .loader {
        margin: 60px auto;
        font-size: 6px;
        position: relative;
        text-indent: -9999em;
        border-top: 1.1em solid rgba(237, 220, 213, 0.2);
        border-right: 1.1em solid rgba(237, 220, 213, 0.2);
        border-bottom: 1.1em solid rgba(237, 220, 213, 0.2);
        border-left: 1.1em solid #eddcd5;
        -webkit-transform: translateZ(0);
        -ms-transform: translateZ(0);
        transform: translateZ(0);
        -webkit-animation: load8 0.9s infinite linear;
        animation: load8 0.9s infinite linear;
    }
    @-webkit-keyframes load8 {
        0% {
            -webkit-transform: rotate(0);
            transform: rotate(0);
        }
        100% {
            -webkit-transform: rotate(360deg);
            transform: rotate(360deg);
        }
    }
    @keyframes load8 {
        0% {
            -webkit-transform: rotate(0);
            transform: rotate(0);
        }
        100% {
            -webkit-transform: rotate(360deg);
            transform: rotate(360deg);
        }
    }
    .modal .modalContent{
        padding: 15px;
    }
    .modal .closeModal{
        right: 0;
    }
    button i{
        font-style: normal;
    }
    .onSubmit{
        background-color: #FFE7CB;
        background-image: url(/fronts/img/backgrounds/onSubmit.jpg);
        background-size: 100%;
        font-family: "GillSans-Light";
        font-size: 18px;
        color: #42261D;
        text-align: center;
        text-transform: uppercase;
        color: #B22D00;
        width: 100%;
        height: 58px;
        line-height: 58px;
        border: 1px solid #FFFFFF;
        -webkit-box-shadow: 0 2px 4px 0 rgba(0, 0, 0, 0.5);
        box-shadow: 0 2px 4px 0 rgba(0, 0, 0, 0.5);
    }
</style>
