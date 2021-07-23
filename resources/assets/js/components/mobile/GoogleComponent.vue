<template>
    <div>
        <div class="cookie-policy" v-if="showCookiePolicy">
            <div class="inner">
                <div class="row">
                    <div class="col-8">
                        <p>Acest site utilizeaza cookie-uri. Prin continuarea navigarii sunteti de acord cu
                            <a href="/ro/cookie">Politica cookie</a>
                        </p>
                    </div>
                    <div class="col-4">
                        <button type="button" class="accept-btn animated heartBeat" @click="acceptCookiePolicy">ok</button>
                    </div>
                    <div>
                        <span class="close" @click="acceptCookiePolicy">X</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { bus } from '../../app_mobile';

export default {
    props: ['products', 'list', 'cookie'],
    data(){
        return {
            ready: true,
            fbqHomewear: '583132129069944',
            fbqBijoux: '273958797023426',
            showCookiePolicy: true,
        }
    },
    mounted(){
        this.showCookiePolicy = !this.cookie;

        // console.log(this.products);

        if (this.products.length > 0) {
            this.createImpressions()
        }

        if (this.products == 'purchase') {
            this.purchase()
        }

        bus.$on('ga-event-clickImpresions', data => {
            this.clickImpresions(data.product, data.list);
        });

        bus.$on('ga-event-viewProduct', data => {
            this.viewProduct(data);
        });

        bus.$on('ga-event-addToCart-bulk', data => {
            this.addToCartBulk(data);
        });

        bus.$on('ga-event-addToCart', data => {
            this.addToCart(data);
        });

        bus.$on('ga-event-removeFromCart', data => {
            this.removeFromCart(data);
        });

        bus.$on('ga-event-addToFavorites', data => {
            this.addTofavorites(data);
        });

        bus.$on('ga-event-viewCart', data => {
            this.viewCart(data.products, data.subproducts);
        });

        bus.$on('ga-event-initiateCheckout', data => {
            this.initiateCheckout(data.promo, data.products, data.amount);
        });

        bus.$on('ga-event-addShippingInfo', data => {
            this.addShippingInfo(data.country, data.products, data.subproducts);
        });

        bus.$on('ga-event-addPAymentInfo', data => {
            console.log(data);
            this.addPAymentInfo(data.payment, data.products, data.subproducts);
        });
    },
    methods: {
        // View Content Event
        viewProduct(product){
            if (typeof product.translation === "undefined") {
                product = product.product;
            }
            let aditionall = JSON.parse(product.translation.aditionall);

            window.dataLayer.push({
                event: 'eec.prod_view',
                ecommerce: {
                    detail: {
                        actionField: {
                            list: 'viewProduct'
                        },
                        products: [{
                            id          : product.code,
                            name        : product.translation.name,
                            category    : aditionall ? aditionall.category : '',
                            dimension1  : aditionall ? aditionall.color : '',
                            dimension2  : aditionall ? aditionall.collection+'&'+aditionall.set : '',
                            price       : product.main_price.price,
                            brand:       'Soledy',
                        }]
                    }
                }
            });

            window.dataLayer.push({
                event: 'select_item',
                ecommerce: {
                    items: [{
                        item_name: product.translation.name,
                        item_id: product.code,
                        price: product.mainPrice.price,
                        item_brand: 'Soledy',
                        item_category: aditionall ? aditionall.category : '',
                        item_variant: aditionall ? aditionall.color : '',
                        item_list_name: 'viewProduct',
                        index: 1,
                        quantity: '1'
                    }]
                }
            });

            window.fbq('track', 'ViewContent', {
                content_ids: [product.code],
                content_type: 'product',
                value: product.main_price.price,
                currency: 'EUR'
            });
        },
        addToCartBulk(set){
            let data = [];

            set.products.forEach((entry) => {
                data = {
                    product: entry,
                    actionField: entry.translation.name,
                }
                this.addToCart(data)
            })
        },
        // Add To Cart Event
        addToCart(data){
            let product = data.product;
            let actionField = data.actionField;
            let aditionall = JSON.parse(product.translation.aditionall);

            window.dataLayer.push({
                'event': 'eec.add_to_cart',
                'ecommerce': {
                    'currencyCode': this.$mainCurrency,
                    'add': {
                        'actionField': {
                            'list': actionField,
                        },
                        'products': [{
                            'id':           product.code,
                            'name':         product.translation.name,
                            'category':     aditionall ? aditionall.category : '',
                            'dimension1':   aditionall ? aditionall.color : '',
                            'dimension2':   aditionall ? aditionall.collection+'&'+aditionall.set : '',
                            'price':        product.main_price.price,
                            'brand': '      Soledy',
                            'quantity':     1
                        }]
                    }
                }
            });

            window.dataLayer.push({
                event: 'add_to_cart',
                ecommerce: {
                    items: [{
                        item_name:  product.translation.name,
                        item_id: product.code,
                        price: product.mainPrice.price,
                        item_brand: 'Soledy',
                        item_category:  aditionall ? aditionall.category : '',
                        item_variant: aditionall ? aditionall.color : '',
                        item_list_name: actionField,
                        quantity: '1'
                    }]
                }
            });

            window.fbq('track', 'AddToCart', {
                content_ids: [product.code],
                content_type: 'product',
                value: product.main_price.price,
                currency: 'EUR',
            });
        },
        // Add To Cart Event
        removeFromCart(product){
            let aditionall = JSON.parse(product.translation.aditionall);

            if (product.subproduct) {
                product = product.subproduct.product
            }else{
                product = product.product
            }
            window.dataLayer.push({
                'event': 'eec.remove_from_cart',
                'ecommerce': {
                    'currencyCode': this.$mainCurrency,
                    'add': {
                        'actionField': {
                            'list': 'Product-one',
                        },
                        'products': [{
                            'id':       product.code,
                            'name':     product.translation.name,
                            'price':    product.main_price.price,
                            'brand':    'Soledy',
                            'quantity': 1
                        }]
                    }
                }
            });

            window.dataLayer.push({
                event: 'remove_from_cart',
                ecommerce: {
                    items: [{
                        item_name: product.translation.name,
                        item_id: product.code,
                        price: product.main_price.price,
                        item_brand: 'Soledy',
                        item_category: aditionall ? aditionall.category : '',
                        item_variant: aditionall ? aditionall.color : '',
                        quantity: 1
                    }]
                }
            });
        },
        // Add to Favorites Event
        addTofavorites(product){
            window.dataLayer.push({
                 'event': 'eec.add_to_wish',
                 'ecommerce': {
                     'add': {
                         'actionField': {
                             'list': 'Product-one'
                         },
                         'products': [{
                             'id': product.code,
                             'name': product.translation.name,
                             'price': product.main_price.price,
                             'brand': 'Soledy',
                             'category':  product.category.translation.name,
                             'quantity': 1
                         }]
                     }
                 }
             });
             window.fbq('track', 'AddToWishlist', {
                content_ids: [product.code],
                content_type: 'product',
                value: product.main_price.price,
                currency: 'EUR',
            });
        },
        // View Cart Event
        viewCart(products, subproducts){
            let entities = [];
            let items = [];

            for (var i = 0; i < products.length; i++) {
                let aditionall = JSON.parse(products[i].product.translation.aditionall);
                entities.push({
                    'id': products[i].product.code,
                    'name': products[i].product.translation.name,
                    'category'    : aditionall ? aditionall.category : '',
                    'dimension1'  : aditionall ? aditionall.color : '',
                    'dimension2'  : aditionall ? aditionall.collection+'&'+aditionall.set : '',
                    'brand': 'Soledy',
                    'quantity': products[i].qty,
                    'price': products[i].product.main_price.price,
               });
               items.push({
                   'item_name': products[i].product.translation.name,
                   'item_id': products[i].product.code,
                   'price': products[i].product.main_price.price,
                   'item_brand': 'Soledy',
                   'item_category': aditionall ? aditionall.category : '',
                   'item_variant': aditionall ? aditionall.color : '',
                   'quantity': products[i].qty,
               });
            }

            for (var i = 0; i < subproducts.length; i++) {
                let aditionall = JSON.parse(subproducts[i].subproduct.product.translation.aditionall);
                entities.push({
                  'id': subproducts[i].subproduct.product.code,
                  'name': subproducts[i].subproduct.product.translation.name,
                  'category'    : aditionall ? aditionall.category : '',
                  'dimension1'  : aditionall ? aditionall.color : '',
                  'dimension2'  : aditionall ? aditionall.collection+'&'+aditionall.set : '',
                  'brand': 'Soledy',
                  'quantity': subproducts[i].qty,
                  'price': subproducts[i].subproduct.product.main_price.price,
               });
               items.push({
                   'item_name': subproducts[i].subproduct.product.translation.name,
                   'item_id': subproducts[i].subproduct.product.code,
                   'price': subproducts[i].subproduct.product.main_price.price,
                   'item_brand': 'Soledy',
                   'item_category': aditionall ? aditionall.category : '',
                   'item_variant': aditionall ? aditionall.color : '',
                   'quantity': subproducts[i].subproduct.qty,
               });
            }

            window.dataLayer.push({
                'event': 'eec.checkout',
                'ecommerce': {
                    'checkout': {
                        'actionField': {'step': 1},
                        'products': entities,
                    }
                },
            });

            window.dataLayer.push({
                event: 'view_cart',
                ecommerce: {
                    items: items
                }
            });
        },
        // Initiate Checkout Event
        initiateCheckout(promoAdded, products, amount){
            let contents = [];
            let amountAll = 0;
            let entities = [];
            let items = [];

            products.prods.forEach(function(entry){
                contents.push({id: entry.product.code, quantity: entry.qty});
                amountAll += parseFloat(entry.product.main_price.price);
            });

            products.subprods.forEach(function(entry){
                contents.push({id: entry.subproduct.product.code, quantity: entry.qty});
                amountAll += parseFloat(entry.subproduct.product.main_price.price);
            });


            for (var i = 0; i < products.prods.length; i++) {
                let aditionall = JSON.parse(products.prods[i].product.translation.aditionall);
                entities.push({
                    'id'          : products.prods[i].product.code,
                    'name'        : products.prods[i].product.translation.name,
                    'category'    : aditionall ? aditionall.category : '',
                    'dimension1'  : aditionall ? aditionall.color : '',
                    'dimension2'  : aditionall ? aditionall.collection+'&'+aditionall.set : '',
                    'brand'       : 'Soledy',
                    'quantity'    : products.prods[i].qty,
                    'price'       : products.prods[i].product.main_price.price,
               });
               items.push({
                   'item_name': products[i].product.translation.name,
                   'item_id': products[i].product.code,
                   'price': products[i].product.main_price.price,
                   'item_brand': 'Soledy',
                   'item_category': aditionall ? aditionall.category : '',
                   'item_variant': aditionall ? aditionall.color : '',
                   'quantity': products[i].qty,
               });
            }

            for (var i = 0; i < products.subprods.length; i++) {
                let aditionall = JSON.parse(products.subprods[i].subproduct.product.translation.aditionall);
                entities.push({
                  'id'          : products.subprods[i].subproduct.product.code,
                  'name'        : products.subprods[i].subproduct.product.translation.name,
                  'category'    : aditionall ? aditionall.category : '',
                  'dimension1'  : aditionall ? aditionall.color : '',
                  'dimension2'  : aditionall ? aditionall.collection+'&'+aditionall.set : '',
                  'brand'       : 'Soledy',
                  'quantity'    : products.subprods[i].qty,
                  'price'       : products.subprods[i].subproduct.product.main_price.price,
               });
               items.push({
                   'item_name': subproducts[i].subproduct.product.translation.name,
                   'item_id': subproducts[i].subproduct.product.code,
                   'price': subproducts[i].subproduct.product.main_price.price,
                   'item_brand': 'Soledy',
                   'item_category': aditionall ? aditionall.category : '',
                   'item_variant': aditionall ? aditionall.color : '',
                   'quantity': subproducts[i].subproduct.qty,
               });
            }

            window.dataLayer.push({
                'event': 'eec.checkout',
                'ecommerce': {
                    'checkout': {
                        'actionField': {'step': 2, 'option': promoAdded},
                        'products'      : entities,
                    }
                },
            });

            window.dataLayer.push({
                event: 'begin_checkout',
                ecommerce: {
                    items: items
                }
            });

            window.fbq('track', 'InitiateCheckout', {
                contents: contents,
                content_type: 'product',
                value: amountAll,
                currency: 'EUR',
            });

        },
        // Add Shipping Info
        onCheckout(product) {
            let products = [];
            for (var i = 0; i < product.length; i++) {
                products.push({
                  'id': product[i].code,
                  'name': product[i].translation.name,
                  'brand': 'Soledy',
                  'quantity': product[i].qty,
                  'category': product[i].category.translation.ame,
                  'price': product[i].main_price.price,
               });
            }
            window.dataLayer.push({
                'event': 'eec.checkout',
                'ecommerce': {
                    'checkout': {
                        'actionField': {'step': 1},
                        'products': products,
                    }
                },
            });
        },
        // Add Shipping Info
        addShippingInfo(country, products, subproducts){
            let entities = [];
            let items = [];
            for (var i = 0; i < products.length; i++) {
                let aditionall = JSON.parse(products[i].product.translation.aditionall);
                entities.push({
                    'id'          : products[i].product.code,
                    'name'        : products[i].product.translation.name,
                    'category'    : aditionall ? aditionall.category : '',
                    'dimension1'  : aditionall ? aditionall.color : '',
                    'dimension2'  : aditionall ? aditionall.collection+'&'+aditionall.set : '',
                    'brand'       : 'Soledy',
                    'quantity'    : products[i].qty,
                    'price'       : products[i].product.main_price.price,
               });
               items.push({
                   'item_name': products[i].product.translation.name,
                   'item_id': products[i].product.code,
                   'price': products[i].product.main_price.price,
                   'item_brand': 'Soledy',
                   'item_category': aditionall ? aditionall.category : '',
                   'item_variant': aditionall ? aditionall.color : '',
                   'quantity': products[i].qty,
               });
            }

            for (var i = 0; i < subproducts.length; i++) {
                let aditionall = JSON.parse(subproducts[i].subproduct.product.translation.aditionall);
                entities.push({
                  'id'          : subproducts[i].subproduct.product.code,
                  'name'        : subproducts[i].subproduct.product.translation.name,
                  'category'    : aditionall ? aditionall.category : '',
                  'dimension1'  : aditionall ? aditionall.color : '',
                  'dimension2'  : aditionall ? aditionall.collection+'&'+aditionall.set : '',
                  'brand'       : 'Soledy',
                  'quantity'    : subproducts[i].qty,
                  'price'       : subproducts[i].subproduct.product.main_price.price,
               });
               items.push({
                   'item_name': subproducts[i].subproduct.product.translation.name,
                   'item_id': subproducts[i].subproduct.product.code,
                   'price': subproducts[i].subproduct.product.main_price.price,
                   'item_brand': 'Soledy',
                   'item_category': aditionall ? aditionall.category : '',
                   'item_variant': aditionall ? aditionall.color : '',
                   'quantity': subproducts[i].subproduct.qty,
               });
            }

            window.dataLayer.push({
                'event': 'eec.checkout',
                'ecommerce': {
                    'checkout': {
                        'actionField'   : {'step': 3, 'option': country.translation.name},
                        'products'      : entities,
                    }
                },
            });

            window.dataLayer.push({
                event: 'add_shipping_info',
                ecommerce: {
                        shipping_tier: '$metoda-de-livrare',
                        items: items
                }
            });
        },
        // Add Payment Info
        addPAymentInfo(payment, products, subproducts){
            let entities = [];
            let items = [];
            let contentsHomewear = [];
            let contentsBijoux = [];
            let amountHomewear = 0;
            let amountBijoux = 0;
            let amount = 0;
            let contents = [];

            for (var i = 0; i < products.length; i++) {
                let aditionall = JSON.parse(products[i].product.translation.aditionall);
                entities.push({
                    'id'          : products[i].product.code,
                    'name'        : products[i].product.translation.name,
                    'category'    : aditionall ? aditionall.category : '',
                    'dimension1'  : aditionall ? aditionall.color : '',
                    'dimension2'  : aditionall ? aditionall.collection+'&'+aditionall.set : '',
                    'brand'       : 'Soledy',
                    'quantity'    : products[i].qty,
                    'price'       : products[i].product.main_price.price,
               });
               items.push({
                   'item_name': products[i].product.translation.name,
                   'item_id': products[i].product.code,
                   'price': products[i].product.main_price.price,
                   'item_brand': 'Soledy',
                   'item_category': aditionall ? aditionall.category : '',
                   'item_variant': aditionall ? aditionall.color : '',
                   'quantity': products[i].qty,
               });

               contents.push({id: products[i].product.code, quantity: products[i].qty});
               amount += parseFloat(products[i].product.main_price.price);
            }

            for (var i = 0; i < subproducts.length; i++) {
                let aditionall = JSON.parse(subproducts[i].subproduct.product.translation.aditionall);
                entities.push({
                  'id'          : subproducts[i].subproduct.product.code,
                  'name'        : subproducts[i].subproduct.product.translation.name,
                  'category'    : aditionall ? aditionall.category : '',
                  'dimension1'  : aditionall ? aditionall.color : '',
                  'dimension2'  : aditionall ? aditionall.collection+'&'+aditionall.set : '',
                  'brand'       : 'Soledy',
                  'quantity'    : subproducts[i].qty,
                  'price'       : subproducts[i].subproduct.product.main_price.price,
               });
               items.push({
                   'item_name': subproducts[i].subproduct.product.translation.name,
                   'item_id': subproducts[i].subproduct.product.code,
                   'price': subproducts[i].subproduct.product.main_price.price,
                   'item_brand': 'Soledy',
                   'item_category': aditionall ? aditionall.category : '',
                   'item_variant': aditionall ? aditionall.color : '',
                   'quantity': subproducts[i].subproduct.qty,
               });

               contents.push({id: subproducts[i].subproduct.product.code, quantity: subproducts[i].qty});
               amount += parseFloat(subproducts[i].subproduct.product.main_price.price);
            }

            window.dataLayer.push({
                event: 'eec.checkout',
                ecommerce: {
                    checkout: {
                        actionField: {
                            step: 4,
                            option: payment
                        },
                        products: entities
                    }
                }
            });

            window.dataLayer.push({
                event: 'add_payment_info',
                ecommerce: {
                    payment_type: payment,
                    items: items
                }
            });

            window.fbq('track', 'AddPaymentInfo', {
                contents: contents,
                content_type: 'product',
                value: amount,
                currency: 'EUR',
            });
        },
        createImpressions(){
            let impressions = [];
            let vm = this;
            this.products.forEach(function(entry){
                impressions.push({id: entry.code, name: entry.translation.name, price: entry.main_price.price, list: vm.list});
            });
            window.dataLayer.push({
                ecommerce: {
                    event: 'ec.impressions',
                    impressions: impressions,
                }
            });
        },
        clickImpresions(product, list){
            window.dataLayer.push({
                event: 'eec.prod_view',
                ecommerce: {
                    detail: {
                        actionField: {
                            list: list
                        },
                        products: [{
                            id: product.code,
                            name: product.translation.name,
                            price: product.main_price.price,
                            brand: 'Soledy',
                        }]
                    }
                }
            });
        },
        // Purchase Event
        purchase(){},
        // Cookie settings
        closeCookiePolicy(){
            this.showCookiePolicy = false;
        },
        acceptCookiePolicy(){
            axios.post('/'+ this.$lang + '/homewear' + '/accept-cookie-policy')
                .then(response => {
                    this.showCookiePolicy = false;
                })
                .catch(e => {
                    console.log('error cookie policy');
                })
        }
    },
}
</script>

<style media="screen">
    .cookie-policy{
        position: fixed;
        width: 100%;
        bottom: 0;
        z-index: 3;
        background-color: rgba(0, 0, 0, 0.6);
        font-family: "GillSans-Light";
    }
    .cookie-policy .inner{
        padding: 15px 10px;
        padding-top: 35px;
        color: #FFF;
    }
    .cookie-policy .inner a{
        color: #FFF;
        text-decoration: underline;
    }
    .cookie-policy .inner .close{
        color: #FFF;
        opacity: 1;
        cursor: pointer;
        font-size: 16px;
        position: absolute;
        top: 8px;
        right: 15px;
    }
    .cookie-policy .inner .accept-btn{
        font-family: "GillSans-Light";
        font-size: 15px;
        color: #B22D00;
        letter-spacing: -0.03px;
        text-align: center;
        width: 100%;
        line-height: 22px;
        background-color: #FFE7CB;
        background-image: url(/fronts/img/backgrounds/buttons.jpg);
        background-size: 100% 100%;
        border: 1px solid #FFFFFF;
        text-transform: uppercase;
        -webkit-transition: font-size .5s ease;
        transition: font-size .5s ease;
        height: 46px;
    }
</style>
