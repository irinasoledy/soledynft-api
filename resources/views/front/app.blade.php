<!DOCTYPE html>
<html   lang="{{ @$lang->lang }}"
        currency="{{ @$currency->abbr }}"
        currency-rate="{{ @$currency->rate }}"
        main-currency={{ @$mainCurrency->abbr }}
        device="{{ isMobile() ? 'sm' : 'md' }}">
    <head>
        {{-- <meta name="robots" content="noindex, nofollow"/> --}}
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <title>{{ @$seoData['title'] }}</title>
        <meta name="description" content="{{ @$seoData['description'] }}">
        <meta name="keywords" content="{{ @$seoData['keywords'] }}">
        <meta name="_token" content="{{ csrf_token() }}">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.2/animate.min.css">
        <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css" />
        <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" />
        <link rel="stylesheet" type="text/css" href="{{ asset('fronts_mobile/css/app_mobile.css?'.uniqid()) }}" />

        <link rel="stylesheet" type="text/css" href="{{ asset('fronts/css/futures.css?'.uniqid()) }}" />
        <link rel="shortcut icon" href="/favicon.png" type="image/x-icon">


        @if ($site == 'bijoux')
            <link rel="stylesheet" type="text/css" href="{{ asset('fronts_mobile/css/bijoux-mobile.css?'.uniqid()) }}" />
        @else
            <link rel="stylesheet" type="text/css" href="{{ asset('fronts_mobile/css/loungewear-mobile.css?'.uniqid()) }}" />
        @endif

        <!-- Google Tag Manager -->
        <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
        new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
        j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
        'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','GTM-NQSCG3N');</script>
        <!-- End Google Tag Manager -->

        @yield('microdataFacebook')
        @yield('microdataGoogle')

        @include('front.facebookPixel')
    </head>
    <body class="scroll-stop">
        <!-- Google Tag Manager (noscript) -->
         <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NQSCG3N"
         height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
         <!-- End Google Tag Manager (noscript) -->

        <div id="cover-mob">
            <div class="sniper-load">
                <div class="sniper-load-inner">
                    <div class="loader"></div>
                </div>
            </div>

            <google-events-mob :products="{{ @$productList }}" :list="'{{ @$list }}'" :cookie="{{ @$_COOKIE['cookie_policy'] ?? 'false' }}"></google-events-mob>

            @yield('content')
            @include('front.partials.modals')
        </div>

        <script
            src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
            integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
            crossorigin="anonymous"
            ></script>
        <script
            src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
            integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1"
            crossorigin="anonymous"
            ></script>
        <script
            src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
            integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"
            crossorigin="anonymous"
            ></script>
        <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
        <script src="/{{ $lang->lang }}/js/lang.js?{{ uniqid('', true) }}"></script>
        <script src="{{ asset('fronts_mobile/js/bundle.js?'.uniqid()) }}"></script>
        <script src="{{ asset('fronts_mobile/js/app_mobile.js?'.uniqid()) }}"></script>

        <script type='text/javascript'>
         window.__lo_site_id = 254634;

         (function() {
          var wa = document.createElement('script'); wa.type = 'text/javascript'; wa.async = true;
          wa.src = 'https://d10lpsik1i8c69.cloudfront.net/w.js';
          var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(wa, s);
         })();
        </script>

        <script>
            $(window).on('load',function(){
                $('#myModal').modal('show');
                $('#session-modal').modal('show');
            });
            window.onbeforeunload = function () {
              window.scrollTo(0, 0);
            }
        </script>

        <style media="screen">
            header #wish span, header #cart span{
                left: 18px;
            }
        </style>

        @yield('purchase-event')
    </body>
</html>
