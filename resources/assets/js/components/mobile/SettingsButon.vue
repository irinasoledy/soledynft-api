<template>
    <li v-if="load" class="widthSettings" data-toggle="modal" data-target="#userSettings">
        <p>
            {{ currentCurrency.abbr }} /
            {{ currentLang.lang }} /
            <img :src="'/images/flags/24x24/' + currentCountry.flag" alt="icon" />
        </p>
        <p>|</p>
        <a data-toggle="modal" data-target="#userSettings">{{ trans.vars.TehButtons.change }}</a>
    </li>
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

            bus.$on('shareCurrencyCurrency', data => {
                this.currentCurrency = data
            });

            bus.$on('shareCurrencyCountry', data => {
                this.currentCountry = data
            });
        },
        methods: {}
    }
</script>
