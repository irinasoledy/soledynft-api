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
    $(document).on("scroll", function() {
        $(window).scrollTop() > 20 ? $("header").addClass("top") : $("header").removeClass("top")
    }), filterCat = $(".filterCat"), filterContainer = $(".filterContainer"), collValid || c.addEventListener("touchstart", function(e) {
        touchstartY = e.changedTouches[0].screenY
    }, !1), t.on("click", function() {
        $(this).children().hasClass("burgerOpen") ? (e = !0, $(this).children("div").removeClass("burgerOpen"), navOpenClass.css("transform", "translateX(-110vw)"), backHeader.removeClass("show"), body.css("overflow", "auto")) : ($(this).children("div").addClass("burgerOpen"), $("header").removeClass("top"), e = !1, navOpenId.css("transform", "translateX(0px)"), backHeader.addClass("show"), body.css("overflow", "hidden"))
    }), $(collectionButton).click((e) => {
        console.log(e.target)
        $(e.target).next().css("transform", "translateX(0px)")
    }), categoryButton.click(() => {
        categoryOpen.toggleClass("showThis"), categoryButton.toggleClass("open")
    }), navBack.children("span").click(function() {
        $(this).parent().parent().css("transform", "translateX(-110vw)")
    }), collButton.children("span").click(function() {
        $(this).next().css("transform", "translateX(0px)"), $(this).next().children(".navBack").children().text($(this).text())
    }), backHeader.click(() => {
        navOpenClass.css("transform", "translateX(-110vw)"), backHeader.removeClass("show"), burger.removeClass("burgerOpen"), body.css("overflow", "auto")
    }), footerButton.click(function() {
        $(this).parent().children("li").toggleClass("show")
    }), filterCat.on("click", ".filterButton", function() {
        $(this).hasClass("active") ? ($(this).removeClass("active"), $(this).next().removeClass("show")) : ($(this).addClass("active"), $(this).next().addClass("show"))
    }), filterContainer.on("click", "#closeFilter", function() {
        document.getElementById("filterInner").classList.remove("show"), document.getElementById("filterButton").classList.remove("active")
    }), $(".addToWish").click(function() {
        $(this).toggleClass("addedToWish")
    }), $(".addToWishProduct").click(function() {
        $(this).toggleClass("addedToWish")
    }), $("._descriptionInner").children(".title").click(function() {
        $(this).toggleClass("minus"), $(this).next().toggleClass("show")
    }), $("select.qty").on("change", function() {
        $(this).parents("._description").find(".qtyBox").text(this.value)
    }), $(".paymentMethod").click(function() {
        $(".paymentMethod").removeClass("selected"), $(this).addClass("selected")
    }), $("#backNavArea").click(function() {
        $("#pageSelected").toggleClass("show"), $("#navAreaMenu").toggleClass("open"), $("#backNavArea").toggleClass("open"),$("body").toggleClass("overHidden")
    })
}), $(function() {
    let e = $(".sliderCollectionHome");
    $(document).ready(function() {
        let t = .01 * window.innerHeight;
        document.documentElement.style.setProperty("--vh", `${t}px`), $(".bannerSlider").slick({
            dots: !1,
            infinite: !0,
            speed: 800,
            slidesToShow: 1,
            slidesToScroll: 1,
            arrows: !0
        }), $(e).slick({
            dots: !1,
            infinite: !0,
            speed: 800,
            slidesToShow: 1,
            slidesToScroll: 1,
            variableWidth: !0,
            arrows: 1
        }), $(".additional").slick({
            dots: !1,
            infinite: !0,
            speed: 800,
            slidesToShow: 1,
            slidesToScroll: 1,
            variableWidth: !0
        })
    })
}), setTimeout(function() {
    $(function() {
        let e = $(".sliderOneProduct");
        if (e.slick({
                infinite: !1,
                speed: 800,
                slidesToShow: 1,
                slidesToScroll: 1,
                vertical: !0,
                verticalSwiping: !0,
                arrows: !1,
                dots: !0,
                rows: 0,
                touchMove: !1
            }), e.find(".slick-dots").css("top", `calc(40% - ${15*e.find(".slick-dots").children("li").length/2}px)`), $("main").hasClass("oneProductContent")) {
            $(".fuuckSlider").slick({
                dots: !1,
                infinite: 1,
                speed: 800,
                slidesToShow: 1,
                slidesToScroll: 1,
                arrows: !1,
                rows: 0,
                touchMove: !1
            });
            var t, n, s, o = window.innerHeight - 195,
                i = document.querySelectorAll("._description"),
                c = document.querySelectorAll(".innerContainer"),
                l = !0,
                r = $(".sliderOneProduct").find(".sld"),
                a = $(".lookItem"),
                d = !1,
                u = 0;
            for (let e = 0; e < $(".sliderLook").length; e++) $(".sliderLook").eq(e).slick({
                dots: !1,
                infinite: !0,
                speed: 800,
                slidesToShow: 1,
                slidesToScroll: 1,
                variableWidth: !0,
                swipe: !0
            });
            for (let e = 0; e < i.length; e++) i[e].classList.add(`fuuck${e}`), c[e].classList.add(`bag${e}`), $(i[e]).on("touchstart", function(t) {
                "touchstart" === t.type ? (n = t.touches[0].clientY - u, s = t.touches[0].clientY) : n = t.clientY - u, d = !$(`.fuuck${e}`).hasClass("innerActivated")
            }), $(i[e]).on("touchend", function(n) {
                t < -50 ? ($(".fuuckSlider").slick("slickSetOption", "swipe", !1), t = -o, $(`.fuuck${e}`).addClass("innerActivated"), $(".oneProductContent").addClass("noArrows"), p(t, $(`.fuuck${e}`))) : t > -150 && ($(".fuuckSlider").slick("slickSetOption", "swipe", !0), u = 0, p(t = 0, $(`.fuuck${e}`)), h()), d = !1
            }), $(i[e]).on("touchmove", function(o) {
                0 == $(`.bag${e}`).scrollTop() && 1 == l && 0 !== $(o.target).parents("._description.innerActivated").length && (d = o.touches[0].clientY > s), d && (o.preventDefault(), "touchmove" === o.type ? o.touches[0].clientY - n > -500 && o.touches[0].clientY - n < 0 && (t = o.touches[0].clientY - n) : h(), $(".sliderOneProduct").slick("slickSetOption", "swipe", !0), u = t, p(t, $(`.fuuck${e}`)))
            });

            function h() {
                $("._description").removeClass("innerActivated"), $(".fuuckSlider").slick("slickSetOption", "swipe", !0), t = 0, u = 0, $(".oneProductContent").removeClass("noArrows"), p(t, $("._description")), $(".innerContainer").scrollTop(0)
            }

            function p(e, t) {
                t.css("transform", `translateY(${e}px)`)
            }
            $(r).on("touchstart", function(e) {
                "touchstart" === e.type ? (n = e.touches[0].clientY - u, s = e.touches[0].clientY, d = !0) : n = e.clientY - u
            }), $(r).on("touchmove", function(e) {
                console.log($(this).attr("data-slick-index") == $(this).parent().children("div:last-child").attr("data-slick-index")), e.touches[0].clientY < s - 30 && $(this).attr("data-slick-index") == $(this).parent().children("div:last-child").attr("data-slick-index") ? ($(".sliderOneProduct").slick("slickSetOption", "swipe", !1), t = e.touches[0].clientY - n, d = !0, u = t) : ($(".sliderOneProduct").slick("slickSetOption", "swipe", !0), d = !1)
            }), $(r).on("touchend", function(e) {
                d && (t = -o, $(this).parents(".oneProduct").find("._description").addClass("innerActivated"), $(".oneProductContent").addClass("noArrows"), p(t, $(this).parents(".oneProduct").find("._description"))), d = !1
            }), $(a).on("touchstart", function(e) {
                l = !1
            }), $(a).on("touchend", function(e) {
                l = !0
            }), $(".closeInner").click(() => h()), $("._sizeCheck").children("input").change(e => {
                $(".sizeContainerProduct").css("bottom", "-100%"), $(e.target).parents(".productInner.oneProduct").find(".sizeButton").addClass("_sizeChecked")
            }), $(document).on("click", "#sizeValidation", function(e) {
                var n = $(e.target).parents(".buttons").next().find(".item");
                for (let e = 0; e <= n.length; e++)
                    if (0 == $(n[e]).find("input:checked").length) {
                        t = -o, $(description).addClass("innerActivated"), p(t, description), $(n[e]).find(".chooseSize").addClass("heartBeat");
                        break
                    }
            })
        }
    })
}, 500), window.addEventListener("resize", () => {
    let e = .01 * window.innerHeight;
    document.documentElement.style.setProperty("--vh", `${e}px`)
}), $(function() {
    setTimeout(function() {
        $("#sniper").fadeOut()
    }, 500)
}),
 $(function() {
   if(screen.width < 768) {
     $("#pageSelected").click(function() {
         $(this).toggleClass("show"), $("#navAreaMenu").toggleClass("open"), $("#backNavArea").toggleClass("open"),$("body").toggleClass("overHidden")
     })

   }
 }), $("main").hasClass("oneProductContent") && ($("#wish").children("span").css("top", "-13px"), $("#cart").children("span").css("top", "-13px")), $(function() {
    $(document).ready(function() {
        $(document).on("click", ".selSize", function(e) {
            let t = $(e.target).parents(".selSize").children("input").val();
            $(".dropdown-menu").removeClass("show"), $(e.target).parents(".sizeContainer").children("button").text(t), $(e.target).parents(".sizeContainer").children("button").css("text-transform", "uppercase")
        });
        $(".sizeContainer").on("click", "span", e => {
            const t = e.target.closest("div").querySelectorAll("span");
            for (let e of t) e.classList.remove("selected");
            e.target.classList.add("selected")
        })
    })
});

(function(){
  if(document.getElementById('filter')) {
    const top = filterButtonsContainer.offsetTop;

    // get filter buttons fixed on top on scroll page
    window.onscroll = function(ev) {

     if ((window.innerHeight + window.pageYOffset - top ) >= screen.height) {
       document.getElementById('filterButtonsContainer').classList.add('fixedTop');
     } else {
       document.getElementById('filterButtonsContainer').classList.remove('fixedTop');
     }

    };

    function openFilter() {
      document.getElementById('filter').setAttribute('style','opacity: 1; transform: translateY(0)');
      document.querySelector('body').classList.add('hidden');
    }

    function openSort() {
      document.getElementById('sort').setAttribute('style','opacity: 1; transform: translateY(0)');
      document.querySelector('body').classList.add('hidden');
    }

    function closeFilter() {
      document.getElementById('filter').setAttribute('style','opacity: 0; transform: translateY(100vh)');
      document.querySelector('body').classList.remove('hidden');
    }

    function closeSort() {
      document.getElementById('sort').setAttribute('style','opacity: 0; transform: translateY(100vh)');
      document.querySelector('body').classList.remove('hidden');
    }

    function showMore(e) {
      const item = e.parentNode.querySelectorAll('.filter__item[data-show]');

      console.log(e)

      if(e.getAttribute('data-status') === 'true') {
        item.forEach(e => e.setAttribute('data-show', 'false'));
        e.setAttribute('data-status', 'false');
        e.textContent = '+ Show more'
      } else {
        item.forEach(e => e.setAttribute('data-show', 'true'));
        e.setAttribute('data-status', 'true');
        e.textContent = '- Show less'
      }
    }

    // open filter
    document.addEventListener('click', (e) => e.target === document.getElementById('filterButton') && openFilter());

    //open sort
    document.addEventListener('click', (e) => e.target === document.getElementById('sortButton') && openSort());

    //close filter
    document.addEventListener('click', (e) => e.target === document.getElementById('closeFilter') && closeFilter());

    //close sort
    document.addEventListener('click', (e) => e.target === document.getElementById('closeSort') && closeSort());

    // Reset Filter form

    document.addEventListener('click', (e) => e.target === document.getElementById('clearFormFilter') && document.getElementById('formFilter').reset());

    // Show more items
    document.addEventListener('click', (e) => e.target.classList.contains('filter__more') && showMore(e.target))


    // close menu on click outside menu

    document.addEventListener("click", (e) => {
      let isClickInside = document.getElementById('filter').contains(e.target),
          isClickInsideSort = document.getElementById('sort').contains(e.target);

      if(e.target !== document.getElementById('sortButton') && !isClickInsideSort) {
        closeSort();
      }

      if (e.target !== document.getElementById('filterButton') && !isClickInside) {
        closeFilter();
      }
    })

  }
})();
