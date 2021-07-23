<template>
    <div class="modalBody">
        <div class="cartContent" v-if="set">
            <div class="cartItem" v-for="setProduct in set.set_products" v-if="enableStocks.includes(setProduct.product.id)">
                <a href="#">
                    <img :src="'/images/products/sm/' + setProduct.product.set_image.src" v-if="setProduct.product.set_image" />
                    <img src="/images/no-image-ap.jpg" v-else/>
                </a>
                <div class="description">
                    <a href="#" class="name">{{ setProduct.product.translation.name }}</a>

                    <div class="price" v-if="setProduct.gift == 0">
                        <span> {{ setProduct.product.personal_price.price }}</span><span></span> /
                        <span>
                            {{ setProduct.product.personal_price.old_price }}
                        </span>
                        <span>{{ $currency }}</span>

                        <div v-if="!enableStocks.includes(setProduct.product.id)">
                            {{ trans.vars.DetailsProductSet.outOfStock }}
                        </div>
                    </div>
                    <div v-else>
                        gift
                    </div>

                    <div class="sizeBtn" v-if="setProduct.product.subproducts.length > 0"
                                        @click="openSizesBlock(setProduct.product)"
                                        >
                        <span v-if="sizesText[setProduct.product.id]">{{ sizesText[setProduct.product.id] }}</span>
                        <span v-else>{{ trans.vars.DetailsProductSet.outOfStock }}</span>

                        <div class="sizes-block" :id="'sizes-block' + setProduct.product.id">
                            <div class="sizeContainerProduct">

                                <div class="size-block-head">
                                    <img :src="'/images/products/sm/' + setProduct.product.set_image.src" v-if="setProduct.product.set_image" />
                                    <a href="#" class="name">{{ setProduct.product.translation.name }}</a>
                                </div>

                                <div class="head">
                                    <p>{{ trans.vars.DetailsProductSet.selectSize }}</p>
                                    <p data-toggle="modal" data-target="#modalSize" class="sizeGuide">{{ trans.vars.DetailsProductSet.sizeGuide }}</p>
                                </div>

                                <div class="sizeCheckContainer">
                                    <label class="sizeCheck" v-for="subproduct in setProduct.product.subproducts">
                                        <input type="radio" name="size" :disabled="subproduct.warehouse.stock == 0" :value="subproduct.parameter_value.translation.name" @change="choosedSubproduct(setProduct.product, subproduct)"/>
                                        <span class="check">
                                            {{ subproduct.parameter_value.translation.name }}
                                            <span class="count" v-if="subproduct.warehouse.stock == 0">{{ trans.vars.DetailsProductSet.outOfStock }}</span>
                                            <span class="count" v-if="subproduct.warehouse.stock > 0">{{ trans.vars.DetailsProductSet.inStock }}</span>
                                        </span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <p v-if="sizesText[setProduct.product.id] !== trans.vars.DetailsProductSet.selectSize">check!</p>
                </div>
            </div>

            <div class="descriptionInner descriptionInnerHooper">
                <div class="price">
                    <span>
                      {{ trans.vars.DetailsProductSet.pricePerSet }}:
                    </span>
                    <span>{{ amount.toFixed(2) }}</span>
                    /
                    <span class="oldAmount">{{ oldAmount.toFixed(2) }}</span>
                    <span>{{ $currency }}</span>
                </div>
                <div class="cartBtn" @click="addSetToCart()">
                    <span>
                        {{ trans.vars.Cart.setCartAddTo }}
                        <svg data-v-f3c95118="" width="24px" height="32px" viewBox="0 0 24 32" version="1.1" xmlns="http://www.w3.org/2000/svg" > <g data-v-f3c95118="" id="Symbols" stroke="none" strokeWidth="1" fill="none" fillRule="evenodd" > <g data-v-f3c95118="" id="project_20200129_150142" transform="translate(-1529.000000, -61.000000)" > <g data-v-f3c95118="" transform="translate(-359.000000, -1055.000000)" id="Group-8" > <g data-v-f3c95118="" id="Group-16" transform="translate(356.500000, 1055.000000)" > <g data-v-f3c95118="" id="Shape-2" transform="translate(1532.050781, 61.363636)" > <path data-v-f3c95118="" d="M4.56105523,8.4535472 L18.8601019,8.10653496 C21.0685906,8.0529389 22.9023727,9.79982458 22.9559687,12.0083133 C22.9589832,12.1325272 22.9562084,12.256814 22.9476534,12.3807696 L21.9919921,26.2275651 C21.8472458,28.324827 20.1037358,29.9521531 18.0014848,29.9521531 L5.04927707,29.9521531 C2.88525509,29.9521531 1.11363495,28.2311249 1.0509553,26.0680108 L0.65977772,12.5682275 C0.59579128,10.3600154 2.33402965,8.5180345 4.54224179,8.45404806 C4.54851254,8.45386635 4.5547837,8.4536994 4.56105523,8.4535472 Z" id="Rectangle" fill="none" opacity="0.916666667" transform="translate(11.896687, 18.976077) rotate(-360.000000) translate(-11.896687, -18.976077) " ></path> <path data-v-f3c95118="" d="M22.5214956,7.96933632 L18.0691228,7.96933632 L18.0691228,6.17062023 C18.0691228,2.76285464 15.2646973,-1.11910481e-13 11.8056573,-1.11910481e-13 C8.34639139,-1.11910481e-13 5.5419659,2.76285464 5.5419659,6.17062023 L5.5419659,7.96933632 L0.721725511,7.96933632 C0.323804812,7.97044937 0.00112980001,8.28789607 1.95399252e-14,8.68036342 L1.95399252e-14,27.7180369 C0.000903851575,29.6810416 1.61586067,31.2720594 3.60817549,31.2727273 L19.6420505,31.2727273 C21.6345913,31.2720594 23.2495481,29.6810416 23.25,27.7180369 L23.25,8.68036342 C23.2454807,8.28656036 22.9207721,7.97000411 22.5214956,7.96933632 Z M6.75,6.20190826 C6.75,3.58021034 8.93286651,1.45454545 11.6248857,1.45454545 C14.3175905,1.45454545 16.5,3.58021034 16.5,6.20190826 L16.5,8 L6.75,8 L6.75,6.20190826 Z M21.75,27.6953403 C21.7464048,28.8665479 20.7846764,29.8148583 19.5973463,29.8181818 L3.6528784,29.8181818 C2.46509894,29.8148583 1.50337056,28.8665479 1.5,27.6953403 L1.5,9.45454545 L5.57566104,9.45454545 L5.57566104,12.3342628 C5.57566104,12.7251085 5.89721089,13.0419505 6.29313742,13.0419505 C6.68951332,13.0419505 7.01083843,12.7251085 7.01083843,12.3342628 L7.01083843,9.45454545 L16.6049779,9.45454545 L16.6049779,12.3342628 C16.6049779,12.7251085 16.926303,13.0419505 17.3226789,13.0419505 C17.7186054,13.0419505 18.0401552,12.7251085 18.0401552,12.3342628 L18.0401552,9.45454545 L21.75,9.45454545 L21.75,27.6953403 Z" id="Shape" fill="#42261D" fillRule="nonzero" transform="translate(11.625000, 15.636364) rotate(-360.000000) translate(-11.625000, -15.636364) " ></path> </g> </g> </g> </g> </g></svg >
                    </span>
                    <div class="discountBox" v-if="set.discount > 0">
                      -{{ set.discount }}%
                    </div>
                </div>
            </div>

            <div :class="['cancelBackround2', cancelBackround2 == false ? 'hide-block' : '']"
                 @click="clooseSizesBlock2()"
                 style="z-index: 3;">
            </div>
        </div>
    </div>
</template>

<script>
import { bus } from '../../../app_mobile';

export default {
    data() {
        return {
            set: [],
            amount: 0,
            oldAmount: 0,
            choosed: [],
            enableStocks: [],
            cancelBackround2: false,
            translateYSizes: 0,
            offsetTopSizes: 0,
            sizesText: [],
        };
    },
    mounted() {
        bus.$on("shareSetDetails", data => {
            this.set = data.set
            this.amount = data.amount
            this.oldAmount = data.oldAmount
            this.enableStocks = data.enableStocks

            this.set.set_products.forEach(entry => {
                if (this.enableStocks.includes(entry.product.id)) {
                    if (entry.product.subproducts.length > 0) {
                        this.choosed[entry.product.id] = 'subproduct'
                        this.sizesText[entry.product.id] = trans.vars.DetailsProductSet.selectSize
                    }else {
                        this.choosed[entry.product.id] = 0
                        this.sizesText[entry.product.id] = true
                    }
                }
            })
        })
    },
    methods: {
        openSizesBlock(product) {
            if (product.subproducts.length > 0) {
                $('.sizes-block').hide();
                $('#sizes-block'+product.id).toggle();
                this.cancelBackround2 = true;
            }
        },
        clooseSizesBlock2() {
            $('.sizes-block').hide();
            this.translateYSizes = "700";
            this.cancelBackround2 = false;
        },
        choosedSubproduct(product, subproduct){
            if (subproduct.warehouse.stock > 0) {
                $('.sizes-block').hide();
                this.cancelBackround2 = false;
                this.choosed[product.id] = subproduct.id
                this.sizesText[product.id] = subproduct.parameter_value.translation.name
                return
            }
        },
        addSetToCart(){
            if (this.validate()) {
                axios
                    .post("/" + this.$lang + "/homewear/add-mix-set-to-cart", {
                        data: this.choosed,
                        setId: this.set.id
                    })
                    .then((response) => {
                        this.addAnimation("cart", this.$el)
                        bus.$emit("updateCartBox", { data: response.data })
                        // this.setAdded.indexOf(set.id) === -1 ? this.setAdded.push(set.id) : ''
                        // bus.$emit("ga-event-addToCart-bulk", set)
                    })
                    .catch((e) => {
                        console.log("error add set to cart");
                    });
            }
        },
        validate(){
            let ret = true
            this.choosed.forEach((entry, key) => {
                if (entry == 'subproduct') {
                    $('#sizes-block'+key).toggle();
                    this.cancelBackround2 = true;
                    ret = false
                }
            })
            return ret
        },
        addAnimation(el, target) {
            document.getElementById(el).classList.add("heartBeat");

            if (el == "cart") {
                $(target).find(".addToCart").addClass("elAdded");
                $(".cartBtn").addClass("heartBeat");
            } else if (el == "wish") {
                $(target).find(".addToWish").addClass("elAdded");
            } else {
                return null;
            }
            setInterval(() => {
                document.getElementById(el).classList.remove("heartBeat");
                $(".cartBtn").removeClass("heartBeat");
            }, 2500);
        },
    },
}
</script>

<style media="screen">
    .cartItem .price{
        font-size: 16px;
    }
    .sizeBtn{
        width: 100px;
        border: 1px solid #42261D;
        font-size: 12px;
        text-align: center;
        height: 45px;
        font-family: "GillSans-Light";
        line-height: 44px;
        text-transform: uppercase;
        padding-right: 15px;
        position: relative;
        background-color: #fffcf5;
    }
    .cancelBackround2 {
        z-index: 5;
        width: 100%;
        height: 100vh;
        top: 0;
        left: 0;
        position: fixed;
        background-color: rgba(0, 0, 0, 0.8);
    }
    .hide-block {
        left: -100%;
    }
    .sizes-block{
        display: none;
        position: fixed;
        left: 10px;
        bottom: 10px;
        width: calc(100% - 20px);
        min-height: 200px;
        background-color: #FFF;
        z-index: 4;
    }
    .show{
        display: block;
    }
    .sizeContainerProduct{
        position: static;
        width: auto;
    }
    .size-block-head{
        display: flex;
        background-color: #FFF;
    }
    .size-block-head a{
        display: flex;
        align-items: center;
        text-align: center;
        margin: 10px;
    }
</style>
