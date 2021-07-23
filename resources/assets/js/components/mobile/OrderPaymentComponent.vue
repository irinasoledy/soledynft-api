<template>
    <form class="row shippingInformation">
        <div class="col-12 titleCeck">
            <p>{{ trans.vars.Orders.paymentMethod }}</p>
            <span>{{ trans.vars.Orders.stepPayment }}</span>
        </div>
        <div class="col-12 shippingBloc">
            <div class="row paymentMethodContainer">
                <div class="col-12" v-if="countrydelivery == 176">
                    <div class="paymentMethod" @click="cashOnDelivery">
                        <div class="checkMethod"></div>
                        <div>
                            <p>Cash on Delivery</p>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="paymentMethod" @click="setPayment('paypal')">
                        <div class="checkMethod"></div>
                        <div>
                            <p>PayPal</p>
                            <span>
                                <img class="paypalnew" src="https://www.paypalobjects.com/webstatic/mktg/Logo/pp-logo-100px.png" border="0" alt="PayPal Logo" />
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row paymentMethodContainer">
                <div class="alertUser text-center" v-if="validateError">
                    <div class="closeAlert" @click="dismisAllert"></div>
                    <p class="text-danger">{{ validateError }}</p>
                </div>
            </div>
        </div>
    </form>
</template>

<script>
    import { bus } from "../../app_mobile";

    export default {
        props: ["items", "mode", "order_id", "site", "countrydelivery"],
        data() {
            return {
                ready: true,
                validateError: false,
                serverError: false,
                payments: [],
                mainPayment: [],
                choosePayment: false,
                defaultPayment: false,
                cartData: [],
                amount: "0.00",
                lookOrderBtn: false,
                paymentDriver: "paydo",
                step: 0,
            };
        },
        mounted() {
            this.getPayments();

            bus.$on("getCartData", (data) => {
                this.cartData = data;
                this.amount = this.cartData.amount;
            });

            bus.$on("pay", (data) => {
                this.pay(data);
            });

            bus.$on("redirectToPayment", (data) => {
                bus.$emit("updateCart");
                window.location.href = window.location.origin + "/" + this.$lang + "/" + this.site + "/order/payment/methods/" + this.choosePayment + "/" + this.amount + "/" + this.order_id + "/" + this.paymentDriver;
            });
        },
        methods: {
            dismisAllert() {
                this.validateError = false;
            },
            getPayments() {
                axios
                    .post("/" + this.$lang + "/order-get-payments")
                    .then((response) => {
                        this.payments = response.data;
                    })
                    .catch((e) => {
                        this.serverError = "A avut loc o problema tehnica!";
                        console.log("error load payments");
                    });
            },
            pay(data) {
                let vm = this;
                bus.$emit("cartData");
                this.validate();

                if (!this.validateError) {
                    if (this.lookOrderBtn === false) {
                        this.lookOrderBtn = true;
                        bus.$emit("validateStocks");
                    }
                }
                setTimeout(function () {
                    vm.dismisAllert();
                }, 5000);
            },
            order() {
                let vm = this;
                bus.$emit("cartData");
                this.validate();

                if (!this.validateError) {
                    if (this.lookOrderBtn === false) {
                        this.lookOrderBtn = true;
                        axios
                            .post("/" + this.$lang + "/order-shipping", {
                                payment: this.choosePayment,
                            })
                            .then((response) => {
                                window.location.href = window.location.origin + "/" + this.$lang + "/order/payment/" + response.data;
                            })
                            .catch((e) => {
                                this.serverError = "A avut loc o problema tehnica!";
                                console.log("error");
                            });
                    }
                }
                setTimeout(function () {
                    vm.dismisAllert();
                }, 5000);
            },
            validate() {
                let error = false;
                if (this.choosePayment === false) {
                    error = "Choose payment method!";
                }

                if (this.cartData.agreeCond === false) {
                    error = trans.vars.Notifications.agreeTerms;
                }

                this.validateError = error;
            },
            selectPayment() {
                this.paymentDriver = "payop";
                this.step = this.step + 1;
            },
            selectPaymentPaydo() {
                bus.$emit('changePriceType', 'main');
                this.choosePayment = 1;
                this.paymentDriver = "paydo";
                this.step = this.step + 1;
            },
            selectPaymentPaynet() {
                this.choosePayment = 'pn';
                this.paymentDriver = "paynet";
                this.step = this.step + 1;
            },
            maibCardsPayment() {
                this.choosePayment = 0;
                this.paymentDriver = "maib";
                this.step = this.step + 1;
            },
            cashOnDelivery() {
                // bus.$emit('changePriceType', 'personal');
                this.choosePayment = 0;
                this.paymentDriver = "cash";
                this.step = this.step + 1;
            },
            setPayment(payment) {
                if (payment == 'paypal') {
                    bus.$emit('changePriceType', 'main');
                }
                this.choosePayment = "pp";
                this.paymentDriver = payment;
                this.step = this.step + 1;
            },
        },
    };
</script>

<style media="screen">
    .alertUser{
        position: fixed;
        width: 200px;
        left: 50%;
        margin-left: -100px;
        top: 150px;
        background-color: #eddcd5;
        z-index: 10;
        padding: 10px;
    }
</style>
