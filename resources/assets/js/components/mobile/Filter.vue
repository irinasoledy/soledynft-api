<template>
    <div>
        <div class="filter__header">
            <button type="button" id="closeFilter" class="filter__back">
            &times;
            </button>
            <div class="filter__name">Set Filters</div>
            <button @click="removeFilter" id="clearFormFilter" type="button" class="filter__clear">
                {{ trans.vars.TehButtons.clearFilter }}
            </button>
        </div>
        <div class="filter__options">
            <div class="filter__option" >
                <div class="filter__option-title">
                    Budget
                </div>
                <div class="filter__price-wrapper">
                    <label for="minPrice">{{ $currency }}</label>
                    <input type="text" name="minPrice" id="minPrice" v-model="priceMin"/>
                    <label for="maxPrice">To {{ $currency }}</label>
                    <input type="text" name="maxPrice" id="maxPrice" v-model="priceMax"/>
                </div>
            </div>
            <div class="filter__option" v-for="parameter in category.params" v-if="parameter.property.in_filter == 1">
                <div class="filter__option-title" v-if="parameter.property.multilingual == 1">
                    {{ parameter.property.translation.name }}
                </div>
                <div class="filter__items">
                    <div class="filter__item filter__check-container" v-for="(value, index) in parameter.property.parameter_values" v-if="parameter.property.multilingual == 1 && filter.includes(value.id) && index > 4" data-show="false">
                        <label class="check-container">
                            {{ value.translation.name }}
                            <input type="checkbox" name="statusSeller" @change="setProperty" :name="parameter.property.id" :value="value.id"/>
                            <span class="check-container__mark">
                                <svg style="fill:currentColor" width="11" height="9" viewBox="0 0 11 9" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M3.64489 8.10164L0.158292 4.61504C-0.0511769 4.40557 -0.0511769 4.06594 0.158292 3.85645L0.916858 3.09786C1.12633 2.88837 1.46598 2.88837 1.67545 3.09786L4.02419 5.44658L9.05493 0.41586C9.2644 0.206391 9.60405 0.206391 9.81352 0.41586L10.5721 1.17445C10.7816 1.38392 10.7816 1.72355 10.5721 1.93303L4.40348 8.10166C4.19399 8.31113 3.85436 8.31113 3.64489 8.10164V8.10164Z"></path>
                                </svg>
                            </span>
                        </label>
                        <span class="filter__count"></span>
                    </div>
                    <div class="filter__item filter__check-container" v-for="(value, index) in parameter.property.parameter_values" v-if="parameter.property.multilingual == 1 && filter.includes(value.id) && index < 4" >
                        <label class="check-container">
                            {{ value.translation.name }}
                            <input type="checkbox" name="statusSeller" @change="setProperty" :name="parameter.property.id" :value="value.id"/>
                            <span class="check-container__mark">
                                <svg style="fill:currentColor" width="11" height="9" viewBox="0 0 11 9" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M3.64489 8.10164L0.158292 4.61504C-0.0511769 4.40557 -0.0511769 4.06594 0.158292 3.85645L0.916858 3.09786C1.12633 2.88837 1.46598 2.88837 1.67545 3.09786L4.02419 5.44658L9.05493 0.41586C9.2644 0.206391 9.60405 0.206391 9.81352 0.41586L10.5721 1.17445C10.7816 1.38392 10.7816 1.72355 10.5721 1.93303L4.40348 8.10166C4.19399 8.31113 3.85436 8.31113 3.64489 8.10164V8.10164Z"></path>
                                </svg>
                            </span>
                        </label>
                        <span class="filter__count"></span>
                    </div>
                    <button data-status="false" type="button" class="filter__more" >
                        + Show More
                    </button>
                </div>
            </div>
        </div>
        <div class="filter__footer">
            <button class="button" type="button" @click="filterProducts">
                Show Results
            </button>
        </div>
    </div>
</template>

<script>
    import { bus } from "../../app_mobile";

    export default {
        props: ["category", "site"],
        data() {
            return {
                filter: [],
                priceMin: 0,
                priceMax: 1000000,
                categories: [],
                properties: [],
                page: 1,
                last_page: false,
                loading: false,
                products: false,
                repeted: false,
                sort: 'default',
            };
        },
        mounted() {
            axios.interceptors.request.use(
                (config) => {
                    bus.$emit("showPreloader");
                    return config;
                },
                (error) => {
                    return Promise.reject(error);
                }
            );

            axios.interceptors.response.use(
                (response) => {
                    bus.$emit("hidePreloader");
                    return response;
                },
                (error) => {
                    this.loading = false;
                    return Promise.reject(error);
                }
            );

            this.setDefaultCategories();
            this.filterProducts();
            this.setDefaultFilter();

            bus.$on("shareSortOptions", (data) => {
                this.sort = sort
                this.filterProducts()
            });

            bus.$on("handleScroll", (data) => {
                this.handleScroll(data);
            });
        },
        methods: {
            setDefaultCategories() {
                let ret = [];
                if (this.category.children.length > 0) {
                    this.category.children.forEach(function (entry, key) {
                        ret[key] = entry.id;
                    });
                    this.categories = ret;
                } else {
                    this.categories = [this.category.id];
                }
            },
            filterCategory(id) {
                this.categories = [id];
                this.page = 0;
                this.filterProducts();
            },
            filterPrice() {
                this.page = 0;
                this.filterProducts();
            },
            filterProducts(loadMore = 0) {
                this.loading = true;
                axios
                    .post("/" + this.$lang + "/" + this.site + "/filter?page=" + this.page, {
                        priceMin: this.priceMin,
                        priceMax: this.priceMax,
                        categories: this.categories,
                        category: this.category,
                        properties: this.properties,
                        sort: this.sort,
                    })
                    .then((response) => {
                        this.last_page = response.data.last_page;
                        this.page = response.data.current_page + 1;
                        if (this.products === false) {
                            this.products = true;
                            bus.$emit("sendInitProducts", response.data);
                        } else {
                            if (loadMore == 1) {
                                bus.$emit("loadMoreFilterProducts", response.data);
                            } else {
                                bus.$emit("filterProducts", response.data.data);
                            }
                        }
                        this.loading = false;
                    })
                    .catch((e) => {
                        this.errors.push(e);
                    });
            },
            setProperty(e) {
                if (e.target.checked == true) {
                    this.properties.push({ name: e.target.name, value: e.target.value });
                } else {
                    this.properties = this.removeFromObject(this.properties, e.target.name, e.target.value);
                }
                this.page = 0;
                // this.filterProducts();
            },
            setDefaultFilter() {
                axios
                    .post("/" + this.$lang + "/" + this.site + "/setDefaultFilter", {
                        category: this.category,
                    })
                    .then((response) => {
                        this.filter = response.data.parameters;
                        this.priceMin = response.data.prices.min;
                        this.priceMax = response.data.prices.max;
                    })
                    .catch((e) => {
                        this.errors.push(e);
                    });
            },
            removeFromObject(object, name, value) {
                object.filter(function (element, key) {
                    if (element.name == name && element.value == value) {
                        object.splice(key, 1);
                    }
                });
                return object;
            },
            handleScroll(data) {
                let lastPage;

                if (this.last_page === false) {
                    lastPage = data.last_page;
                } else {
                    lastPage = this.last_page;
                }

                if (this.page <= lastPage) {
                    let bottomOfPage = data.visible + data.scrollY >= data.pageHeight;
                    let diff = bottomOfPage || data.pageHeight < data.visible;

                    if (diff && !this.loading) {
                        if (this.page == 0) {
                            this.filterProducts();
                        } else {
                            this.filterProducts(1);
                        }
                    }
                }
            },
            removeFilter() {
                location.reload();
            },
        },
    };
</script>
