<template>
    <section class="collection">
        <h4>{{ colection.translation.name }} {{ trans.vars.General.collection }}</h4>
        <div class="sliderCollectionHome">

            <hooper :settings="settingsHooper" >
                <slide>
                    <a :href="'/' + $lang + '/' + colection.type + '/collection/' + colection.alias" class="item">
                        <img :src="'/images/collections/' + colection.banner_mob" alt="" v-if="colection.banner_mob" />
                        <img src="/images/no-image-ap.jpg" alt="" v-else />
                        <div class="innerContent">
                            <div class="butt">
                                {{ trans.vars.General.shopCollection }}
                            </div>
                        </div>
                    </a>
                </slide>
                <slide  v-for="set in colection.sets">
                    <a :href="'/' + $lang + '/' + colection.type + '/collection/' + colection.alias + '?order=' + set.id" class="item">
                        <img v-if="set.main_photo" :src="'/images/sets/sm/' + set.main_photo.src" alt="" />
                        <img v-else src="/images/no-image-ap.jpg" alt="" />
                    </a>
                </slide>
                <hooper-navigation slot="hooper-addons"></hooper-navigation>
            </hooper>

        </div>

        <a :href="'/' + $lang + '/' + colection.type + '/collection/' + colection.alias" class="butt desk-butt">
            {{ trans.vars.General.shopCollection }}
        </a>
    </section>
</template>

<script>
    import { Hooper, Slide, Navigation as HooperNavigation } from "hooper";

    export default {
        components: { Hooper, Slide, HooperNavigation },
        props: ["colection", "site"],
        data() {
            return {
                imageHeight: 0,
                settingsHooper: {
                  itemsToShow: 1.09,
                  infiniteScroll: true,
                  centerMode: true,
                  wheelControl: false,
                  breakpoints: {
                    1100: {
                      itemsToShow: 4,
                      wheelControl: false,
                    }
                  }
                }
            };
        },
        mounted() {
            this.imageHeight = window.innerWidth / 0.75 - 10;
        },
        methods: {
            setImage(banner) {
                return "/images/collections/" + banner;
            },
        },
    };
</script>

<style media="screen">

    /* :style="'height:' + imageHeight + 'px'" */

</style>
