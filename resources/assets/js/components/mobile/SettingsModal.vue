<template>
    <div class="modal-dialog">
        <div class="modal-content">
                <form class="modalContent">
                <div class="closeModal" data-dismiss="modal">
                    <img src="/fronts/img/icons/plusIconBlack.svg" alt="" />
                </div>
                <div class="modalTitle">
                     {{ trans.vars.FormFields.settings }}
                </div>
                <div class="modalBody">
                    <div class="inputGroup">
                        <label for="country">{{ trans.vars.FormFields.shipTo }}</label>
                        <div class="selectContainer">
                            <select name="country_id" v-model="currentCountry.id">
                                <option :value="oneCountry.id" v-for="oneCountry in countries">
                                    {{ oneCountry.name }}
                                </option>
                            </select>
                            <span>
                                <svg width="10px" height="6px" viewBox="0 0 10 6" version="1.1" xmlns="http://www.w3.org/2000/svg" > <g id="AnaPopova-Site" stroke="none" strokeWidth="1" fill="none" fillRule="evenodd"> <g id="Cos._APL---" transform="translate(-1592.000000, -545.000000)" fill="#42261D" fillRule="nonzero" > <g id="Order-summery" transform="translate(1233.000000, 423.000000)"> <g id="Ship" transform="translate(15.000000, 108.000000)"> <polygon id="Shape" transform="translate(349.000000, 17.000000) scale(1, -1) translate(-349.000000, -17.000000) " points="349 14 344 20 348.763602 20 354 20" ></polygon> </g> </g> </g> </g> </svg>
                            </span>
                        </div>
                    </div>
                    <div class="inputGroup">
                        <label for="languege">{{ trans.vars.FormFields.language }}</label>
                        <div class="selectContainer">
                            <select name="lang_id" v-model="currentLang.id">
                                <option :value="oneLanguage.id"
                                    v-for="oneLanguage in langs"> {{ oneLanguage.description }}
                                </option>
                            </select>
                            <span>
                                <svg width="10px" height="6px" viewBox="0 0 10 6" version="1.1" xmlns="http://www.w3.org/2000/svg" > <g id="AnaPopova-Site" stroke="none" strokeWidth="1" fill="none" fillRule="evenodd"> <g id="Cos._APL---" transform="translate(-1592.000000, -545.000000)" fill="#42261D" fillRule="nonzero" > <g id="Order-summery" transform="translate(1233.000000, 423.000000)"> <g id="Ship" transform="translate(15.000000, 108.000000)"> <polygon id="Shape" transform="translate(349.000000, 17.000000) scale(1, -1) translate(-349.000000, -17.000000) " points="349 14 344 20 348.763602 20 354 20" ></polygon> </g> </g> </g> </g> </svg>
                            </span>
                        </div>
                    </div>
                        <div class="inputGroup">
                            <label for="currency">{{ trans.vars.FormFields.currency }}</label>
                            <div class="selectContainer">
                                <select name="currency_id" v-model="currentCurrency.id">
                                    <option :value="oneCurrency.id" v-for="oneCurrency in currencies">
                                        {{ oneCurrency.abbr }}
                                    </option>
                                </select>
                                <span>
                                    <svg width="10px" height="6px" viewBox="0 0 10 6" version="1.1" xmlns="http://www.w3.org/2000/svg" > <g id="AnaPopova-Site" stroke="none" strokeWidth="1" fill="none" fillRule="evenodd"> <g id="Cos._APL---" transform="translate(-1592.000000, -545.000000)" fill="#42261D" fillRule="nonzero" > <g id="Order-summery" transform="translate(1233.000000, 423.000000)"> <g id="Ship" transform="translate(15.000000, 108.000000)"> <polygon id="Shape" transform="translate(349.000000, 17.000000) scale(1, -1) translate(-349.000000, -17.000000) " points="349 14 344 20 348.763602 20 354 20" ></polygon> </g> </g> </g> </g> </svg>
                                </span>
                            </div>
                        </div>
                    <input type="submit"  @click.prevent="save" :value="trans.vars.FormFields.save"/>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
    import { bus } from '../../app_mobile';

    export default {
        props: ['country', 'currency', 'lang', 'countries', 'currencies', 'langs'],
        data() {
            return {
                load: false,
                currentLang: 0,
                currentCurrency: 0,
                currentCountry: 0,
            }
        },
        mounted() {
            this.currentLang = this.lang
            this.currentCurrency = this.currency
            this.currentCountry = this.country
            this.load = true
            bus.$on('changeCurrency', data => {
                this.getCurrency(data)
            });
            bus.$on('changeWarehouse', data => {
                this.getCountry(data)
            });
        },
        methods: {
            getCurrency(id){
                axios
                    .post('/' + this.$lang + '/homewear/settings/get-currency', {currencyId: id})
                    .then(response => {
                        if (response.data !== 'false') {
                            this.currentCurrency = response.data
                            bus.$emit("shareCurrencyCurrency", this.currentCurrency)
                        }
                    })
                    .catch(e => {
                        console.log('error save settings')
                    })
            },
            getCountry(id){
                axios
                    .post('/' + this.$lang + '/homewear/settings/get-country', {countryId: id})
                    .then(response => {
                        if (response.data !== 'false') {
                            this.currentCountry= response.data
                            bus.$emit("shareCurrencyCountry", this.currentCountry)
                        }
                    })
                    .catch(e => {
                        console.log('error save settings')
                    })
            },
            save(){
                axios
                    .post('/' + this.$lang + '/homewear/settings/save-settings', {
                                            langId: this.currentLang.id,
                                            currencyId: this.currentCurrency.id,
                                            countryId: this.currentCountry.id,
                                        })
                    .then(response => {
                        window.location = response.data;
                    })
                    .catch(e => {
                        console.log('error save settings')
                    })
            }
        }
    }
</script>

<style media="screen">
    .modal .selectContainer select{
        width: 100%;
    }
</style>
