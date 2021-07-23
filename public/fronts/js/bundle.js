$(function () {
    $(document).ready(function () {
        let e = $(".collectionSlider"),
            t = $(".categorySlider"),
            s = $(".oneCollectionSlider"),
            o = $(".navCollection");
        $(".additional").slick({ dots: !1, infinite: !0, speed: 800, slidesToShow: 3, slidesToScroll: 1, swipe: !0 , arrows: false}),
            $(".sliderBanner").slick({ dots: !1, infinite: !0, speed: 800, slidesToShow: 1, slidesToScroll: 1, swipe: !0, nextArrow: $(".nextBanner"), prevArrow: $(".prevBanner") });
        for (let t = 0; t < e.length; t++) $(e[t]).slick({ dots: !1, infinite: !0, speed: 800, slidesToShow: 1, slidesToScroll: 1, swipe: !0 });
        for (let e = 0; e < t.length; e++) $(t[e]).slick({ dots: !1, infinite: !0, speed: 800, slidesToShow: 3, slidesToScroll: 1, swipe: !0 });
        for (let e = 0; e < s.length; e++)
            $(s[e]).slick({ infinite: false, speed: 800, slidesToShow: 1, slidesToScroll: 1, arrows: !1, centerMode: !1, focusOnSelect: !0, infinite: false, vertical: !0, asNavFor: o[e], autoplay: true,
  autoplaySpeed: 2000, }),
                $(o[e]).slick({ infinite: false, speed: 800, slidesToShow: 1, slidesToScroll: 1, swipe: !1, arrows: !1, centerMode: !1, focusOnSelect: !0, infinite: false, vertical: !0, asNavFor: s[e], draggable: !1 });
        // if ($("main").hasClass(".oneProductContent")) {
        //     let e = document.getElementById("myVideo");
        //     e.onloadeddata = function () {
        //         let t = e.duration;
        //         console.log(t),
        //             setTimeout(function () {
        //                 $(s).eq(0).slick("slickPlay"), $(s).eq(0).slick("slickSetOption", "autoplaySpeed", 3e3, !1);
        //             }, 1e3 * t - 3e3);
        //     };
        // }
        var item_length = $(s).find(".slick-slide").length - 1;
        let video_rol = document.getElementById("myVideo");
        if (video_rol) {
            s.on('afterChange', function(event, slick, currentSlide, nextSlide){
                //check the length of total items in .slide container
              //if that number is the same with the number of the last slider
              //Then pause the slider
              if( item_length === s.slick('slickCurrentSlide') ){
                //this should do the same thing -> slider.slickPause();
                s.slick("slickPause")
                o.slick("slickPause")
                video_rol.play();
              };
            });
        }
        $(window).scroll(function () {
            $(window).scrollTop() > 20 ? ($("header").addClass("onScroll"), $(".titlePage").css("top", "150px")) : ($("header").removeClass("onScroll"), $(".titlePage").css("top", "220px"));
        });
    });
}),
    $(function () {
        $(document).ready(function () {
            $(".selSize").on("click", function (e) {
                let t = $(e.target).parents(".selSize").children("input").val();
                $(e.target).parents(".sizeContainer").children("button").text(t), $(e.target).parents(".sizeContainer").children("button").css("text-transform", "uppercase");
            });
            $(".oneProduct").on("click", ".addToWish", function () {
                $(this).toggleClass("addedToWish");
            }),
                $(".paymentMethod").click(function () {
                    $(".paymentMethod").removeClass("selected"), $(this).addClass("selected");
                }),
                $("main").hasClass(".categoryContent") ||
                    ($("#filterButton").click((e) => {
                        !(function (e) {
                            $(e.target).hasClass("active") ? ($(e.target).next().removeClass("show"), $(e.target).removeClass("active")) : ($(e.target).next().addClass("show"), $(e.target).addClass("active"));
                        })(e);
                    }),
                    $(".filterCat").on("click", ".filterButton", function () {
                        $(this).hasClass("active") ? ($(this).removeClass("active"), $(this).next().removeClass("show")) : ($(this).addClass("active"), $(this).next().addClass("show"));
                    }),
                    $(".filterContainer").on("click", "#closeFilter", function () {
                        document.getElementById("filterInner").classList.remove("show"), document.getElementById("filterButton").classList.remove("active");
                    })),
                $(".sizeContainer").on("click", "span", (e) => {
                    const t = e.target.closest("div").querySelectorAll("span");
                    for (let e of t) e.classList.remove("selected");
                    e.target.classList.add("selected");
                }),
                $(".description").on("click", ".title", function () {
                    $(".description").find(".title").removeClass("minus"),
                        $(".description").find(".title").next().removeClass("show"),
                        $(this).next().hasClass("show") ? ($(this).removeClass("minus"), $(this).next().removeClass("show")) : ($(this).addClass("minus"), $(this).next().addClass("show"));
                });
        });
    }),
    $(function () {
        $(document).ready(function () {
            const e = document.getElementById("about1"),
                t = document.getElementById("about2"),
                s = document.getElementById("about3"),
                o = document.getElementById("about4"),
                n = document.getElementById("titleAbout");
            let l = window.pageYOffset;
            $("main").hasClass(".homeContent") &&
                window.addEventListener("scroll", function (a) {
                    let i = window.pageYOffset;
                    (l = i), (e.style.top = 70 - l / 80 + "px"), (t.style.top = 100 - l / 20 + "px"), (s.style.top = 105 - l / 100 + "px"), (o.style.top = l / 30 - 140 + "px"), (n.style.top = l / 70 - 40 + "px");
                });
        });
    }),
    $(function () {
        $(document).ready(function () {
            const e = document.getElementById("contact1"),
                t = document.getElementById("contact2"),
                s = document.getElementById("contact3"),
                o = document.getElementById("contact4");
            let n = window.pageYOffset;
            $("main").hasClass("contactContent") &&
                window.addEventListener("scroll", function (l) {
                    let a = window.pageYOffset;
                    (n = a), (s.style.transform = `scale(${1 + 9e-5 * n} )`), (o.style.transform = `scale(${1 + 9e-5 * n} )`), (e.style.transform = `translateY(${1 + 0.17 * n}px)`), (t.style.transform = `translateY(${1.1 + 0.14 * n}px)`);
                });
        });
    }),
    $(function () {
        $(document).ready(function () {
            let e = document.getElementById("filterButton");
            const t = document.getElementById("loungewearButton"),
                s = document.getElementById("jewerlyButton"),
                o = $("header"),
                n = $("footer");
            $(".soundButton").click(function (e) {
                $(e.target).hasClass("soundEnabled")
                    ? ($(e.target).removeClass("soundEnabled"), $(e.target).parent().children("video").prop("muted", !0))
                    : ($(e.target).addClass("soundEnabled"), $(e.target).parent().children("video").prop("muted", !1));
            }),
                t.addEventListener("click", function () {
                    o.removeClass("jewerly"), o.addClass("loungewear"), n.removeClass("footerJewerly"), n.addClass("footerLoungewear");
                }),
                s.addEventListener("click", function () {
                    o.addClass("jewerly"), o.removeClass("loungewear"), n.addClass("footerJewerly"), n.removeClass("footerLoungewear");
                }),
                $("main").hasClass(".categoryContent") &&
                    e.addEventListener("click", function (e) {
                        $(this).hasClass("active") ? ($(this).next().removeClass("show"), $(this).removeClass("active")) : ($(this).next().addClass("show"), $(this).addClass("active"));
                    });
        });
    }),
    $(function () {
        $(document).ready(function () {
            const e = $("#animateFon"),
                t = $("#animateFonSrc").attr("src"),
                s = document.getElementById("scaleIn"),
                o = document.getElementById("scaleOut"),
                n = document.getElementById("scaleOut2"),
                l = document.getElementById("top"),
                a = document.getElementById("scaleOut3"),
                i = document.getElementById("top2"),
                c = document.getElementById("top3"),
                d = document.getElementById("bottom"),
                r = document.getElementById("bottom2");
            let u = window.pageYOffset;
            $(e).css("background-image", "url(" + t + ")"),
                $("main").hasClass("aboutContent") &&
                    (window.addEventListener("scroll", function (e) {
                        let t = window.pageYOffset;
                        1.5 - 5e-4 * (u = t) > 1 && 1.5 - 5e-5 * u < 2 && (s.style.transform = `scale(${1.5 - 5e-5 * u} )`),
                            (o.style.transform = `scale(${1 + 5e-5 * u} )`),
                            (n.style.transform = `scale(${1 + 5e-5 * u} )`),
                            (l.style.top = 0.05 * u - 169 + "px");
                    }),
                    window.addEventListener("wheel", function (e) {
                        let t = window.pageYOffset;
                        5e-5 * (u = t) - 1 > 1 && 5e-5 * u - 1 < 2 && (a.style.transform = `scale(${5e-5 * u - 1} )`),
                            (i.style.bottom = 1.1 * t - u + 60 + "px"),
                            (c.style.top = 1.1 * t - u + 0.7 + "px"),
                            (d.style.top = 1.1 * t - u + 0.7 + "px"),
                            (r.style.top = 1.1 * t - u + 0.7 + "px");
                    }));
        });
    }),
    $(function () {
        $(document).ready(function () {
            let e = $(".modalTabs").children(),
                t = $("#loginContent"),
                s = $("#registerContent");
            $(e).click(function (o) {
                $(e).removeClass("active"), $(o.target).addClass("active"), "registerTab" == $(o.target).attr("id") ? ($(t).removeClass("active"), $(s).addClass("active")) : ($(t).addClass("active"), $(s).removeClass("active"));
            }),
                $(".edit").click(function (e) {
                    console.log($(e.target))
                    $(e.target).parent().css("opacity", "0"), $(e.target).parent().parent().children(".setOpen").show();
                }),
                $(".closeSet").click(function (e) {
                    $(e.target).parent().hide(), $(e.target).parent().parent().children(".methods").css("opacity", "1");
                });
        });
    }),
    screen.width > 768 &&
        $(function () {
            var e = $(".mainImg"),
                t = $("#cover"),
                s = null;
            e.click(function (e) {
                return (
                    (s = parseInt($(e.target).parents(".slick-slide").attr("data-slick-index"))),
                    $("#zoomModal").modal("show"),
                    console.log(s),
                    e.target.classList.contains("soundButton") ? null : (t.css("height", "0"), t.css("overflow", "hidden"), $("header").css("display", "none"), $("footer").css("display", "none"), !1)
                );
            }),
                $("#zoomModal").on("hidden.bs.modal", function (e) {
                    t.css("height", "auto"), t.css("overflow", "visible"), $("header").css("display", "block"), $("footer").css("display", "block"), $(".zoomSlider").slick("unslick"), $(".zoomNav").slick("unslick"), (s = null);
                }),
                $("#zoomModal").on("shown.bs.modal", function (e) {
                    $(".zoomSlider").slick({ dots: !1, infinite: !0, speed: 800, slidesToShow: 1, slidesToScroll: 1, swipe: !0, arrows: !1, focusOnSelect: !0, vertical: !0, asNavFor: $(".zoomNav") }),
                        $(".zoomNav").slick({ dots: !1, infinite: !0, speed: 800, slidesToShow: 6, slidesToScroll: 1, swipe: !1, draggable: !1, arrows: !1, vertical: !0, focusOnSelect: !0, useTransform: !1, asNavFor: $(".zoomSlider") }),
                        console.log(s),
                        $(".zoomSlider").slick("slickGoTo", s, !0),
                        $(".zoomNav").slick("slickGoTo", s, !0);
                });
        }),
    $(function () {
        var e;
        window.addEventListener(
            "scroll",
            function (t) {
                $("header").css("box-shadow", "0px 6px 6px 0 rgba(0, 0, 0, 0.3)"),
                    window.clearTimeout(e),
                    (e = setTimeout(function () {
                        $("header").css("box-shadow", "");
                    }, 500));
            },
            !1
        );
    });

    $(function() {
        $(".dropdown-menu.show").css("position", "fixed !important");
        let e = !0,
            t = $("li.burger");
        burger = $("#burger"), navOpenId = $("#navOpen"), backHeader = $("#backHeader"), navBack = $(".navBack"), collectionButton = $(".collectionThis"), categoryButton = $("#categoryButton"), collButton = $(".collButton"), collectionsOpen = $("#collectionsOpen"), categoryOpen = $("#categoryOpen"), body = $("body"), navOpenClass = $(".navOpen"), addToWish = $(".addToWish"), footerButton = $(".footerButton"), collValid = !1;
        const n = document.getElementById("loungewearButton"),
            s = document.getElementById("jewerlyButton"),
            o = $("header"),
            i = $("footer"),
            c = document.getElementById("cover-mob");
        let l = $("#filterButton");
        $(document).on("scroll", function() {
            $(window).scrollTop() > 20 ? $("header").addClass("top") : $("header").removeClass("top")
        }),  t.on("click", function() {
            $(this).children().hasClass("burgerOpen") ? (e = !0, $(this).children("div").removeClass("burgerOpen"), navOpenClass.css("transform", "translateX(-110vw)"), backHeader.removeClass("show"), body.css("overflow", "auto")) : ($(this).children("div").addClass("burgerOpen"), $("header").removeClass("top"), e = !1, navOpenId.css("transform", "translateX(0px)"), backHeader.addClass("show"), body.css("overflow", "auto"))
        }), $(collectionButton).click((e) => {
            $(e.target).next().css("transform", "translateX(0px)")
        }), categoryButton.click(() => {
            categoryOpen.toggleClass("showThis"), categoryButton.toggleClass("open")
        }), navBack.children("span").click(function() {
            $(this).parent().parent().css("transform", "translateX(-110vw)")
        }), collButton.children("span").click(function() {
            $(this).next().css("transform", "translateX(0px)"), $(this).next().children(".navBack").children().text($(this).text())
        }), backHeader.click(() => {
            navOpenClass.css("transform", "translateX(-110vw)"), backHeader.removeClass("show"), burger.removeClass("burgerOpen"), body.css("overflow", "auto")
    })
  });
