<template>
    <div class="productInner">
        <div class="h1container">
            <h1>{{ collection.translation.name }}</h1>
        </div>

        <div class="collectionBlock" v-for="set in other_sets">
            <p class="name collectionName">{{ set.translation.name }}</p>

            <Slick ref="slick" :options="slickOptions">
              <div v-if="set.photos.length > 0" v-for="image in set.photos">
                  <div class="slider-one-product">
                      <img :src="'/images/sets/md/' + image.src" />
                  </div>
              </div>
            </Slick>
            <!-- <hooper class="hooper-right" :settings="settingsHooper" >
                <slide v-if="set.photos.length > 0" v-for="image in set.photos">
                    <div class="slider-one-product">
                        <img :src="'/images/sets/md/' + image.src" />
                    </div>
                </slide>
                <hooper-pagination slot="hooper-addons"></hooper-pagination>
            </hooper> -->

            <set
                :site="site"
                :set="set"
                >
            </set>

        </div>

        <div class="similarsBlock">
            <h3>{{ trans.vars.DetailsProductSet.youMightLikeAlso }}</h3>
            <hooper :settings="settingsHooperSimilar">
                <slide v-if="similars.length > 0" v-for="similarSet in similars">
                    <div class="slider-one-product">
                        <a :href="'/' + $lang + '/' + site + '/collection/' + collection.alias + '?order=' + similarSet.id ">
                            <img :src="'/images/sets/og/' + similarSet.main_photo.src" />
                        </a>
                    </div>
                </slide>
                <hooper-navigation slot="hooper-addons"></hooper-navigation>
            </hooper>
        </div>

    </div>
</template>

<script>
    import { bus } from '../../../app_mobile';
    import { Hooper, Slide, Pagination as HooperPagination, Navigation as HooperNavigation } from "hooper";
    import "hooper/dist/hooper.css";
    import Slick from "vue-slick";

    export default {
        components: { Slick, Hooper, Slide, HooperPagination, HooperNavigation },
        props: ["main_set", "other_sets", "collection", "similars", "site"],
        data() {
            return {
                slickOptions: {
                  slidesToShow: 1,
                  slidesToScroll: 1,
                  mobileFirst: true,
                  arrows: true,
                  speed: 500,
                  playSpeed: 5000,
                  dots: true,
                  responsive: [
                    {
                      breakpoints: 991,
                      settings: {
                        arrows: true
                      }
                    }
                  ]
                },
                settingsHooper: {
                  itemsToShow: 1,
                  infiniteScroll: true,
                  centerMode: true,
                  wheelControl: false,
                  playSpeed: 5000,
                  transition: 1000,
                  autoPlay: true
                },
                settingsHooperSimilar: {
                  itemsToShow: 1,
                  infiniteScroll: true,
                  centerMode: true,
                  wheelControl: false,
                  playSpeed: 5000,
                  transition: 1000,
                  autoPlay: true,
                  breakpoints: {
                    1100: {
                      itemsToShow: 3,
                      wheelControl: false
                    }
                  }
                }
            };
        },
        mounted() {
            if (this.main_set !== 'empty') {
                this.other_sets.unshift(this.main_set)
            }


        },
        methods: {},
    };
</script>

<style lang="css" scoped>
    .hooper {
        height: auto;
    }
    .oneProductContent .description {
        height: auto;
    }
    .innerContainer.collectionInner {
        height: auto;
    }
    .collectionBlock{
        margin-bottom: 100px;
    }
    .descriptionInnerHooper {
      margin-left: -15px;
      margin-right: -15px;
      margin-top: 40px;
    }
    .descriptionInnerHooper .cartBtn {
      text-align: center;
      border: 1px solid #42261D;
      background: #fff1d7;
      color: #42261D;
      display: block;
      font-size: 14px;
      font-family: "GillSans-Light";
      text-transform: uppercase;
      padding-top: 5px;
      max-width: 330px;
      margin-left: auto;
      margin-right: auto;
    }
    .descriptionInnerHooper .cartBtn svg {
      margin-bottom: 6px;
      width: 15px;
    }
    .setHooper .hooperPadding {
      padding: 0 15px;
      position: relative;
    }
</style>
