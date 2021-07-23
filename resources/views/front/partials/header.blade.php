<header class="loungewear">
    {{--
      <div class="tabHeader">
          <a class="buttHeader" id="jewerlyButton" href="{{ url('/'.$lang->lang.'/bijoux') }}">
              <svg width="24px" height="12px" viewBox="0 0 24 12" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" > <g id="Symbols" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"> <g id="Header--Jewelry" transform="translate(-1538.000000, -54.000000)" fill="#5C591A"> <g id="Header" transform="translate(5.000000, -129.000000)"> <g id="Loungewear"> <g transform="translate(981.000000, 129.000000)"> <g id="Button-cos" transform="translate(541.000000, 29.000000)"> <g id="left-arrow" transform="translate(23.000000, 31.000000) scale(-1, 1) translate(-23.000000, -31.000000) translate(11.000000, 25.000000)" > <path d="M23.0625007,5.03225758 L3.20823416,5.03225758 L6.49716922,1.65367503 C6.86415332,1.27664256 6.86555957,0.663916412 6.50030984,0.285093618 C6.13506011,-0.0937775623 5.54143555,-0.0951807889 5.17445145,0.281803294 L0.275830079,5.31406419 C0.275501954,5.31435451 0.275267579,5.31469322 0.274986329,5.31498354 C-0.0910602746,5.69201601 -0.0922321487,6.30672603 0.274892579,6.68501657 C0.275220704,6.68530689 0.275455079,6.6856456 0.275736329,6.68593593 L5.1743577,11.7181968 C5.54129493,12.0951325 6.13491949,12.0938261 6.50021609,11.7149065 C6.86546582,11.3360837 6.86405957,10.7233575 6.49707547,10.3463251 L3.20823416,6.96774254 L23.0625007,6.96774254 C23.5802816,6.96774254 24,6.53448423 24,6 C24,5.46551588 23.5802816,5.03225758 23.0625007,5.03225758 Z" id="Path" ></path> </g> </g> </g> </g> </g> </g> </g> </svg>
              {{ trans('vars.General.BijouxBoutique') }}
          </a>
          <a class="buttHeader" id="loungewearButton" href="{{ url('/'.$lang->lang.'/homewear') }}">
              <svg width="24px" height="12px" viewBox="0 0 24 12" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" > <g id="Symbols" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"> <g id="Header--Jewelry" transform="translate(-1538.000000, -54.000000)" fill="#B22D00"> <g id="Header" transform="translate(5.000000, -129.000000)"> <g id="Loungewear"> <g transform="translate(981.000000, 129.000000)"> <g id="Button-cos" transform="translate(541.000000, 29.000000)"> <g id="left-arrow" transform="translate(23.000000, 31.000000) scale(-1, 1) translate(-23.000000, -31.000000) translate(11.000000, 25.000000)" > <path d="M23.0625007,5.03225758 L3.20823416,5.03225758 L6.49716922,1.65367503 C6.86415332,1.27664256 6.86555957,0.663916412 6.50030984,0.285093618 C6.13506011,-0.0937775623 5.54143555,-0.0951807889 5.17445145,0.281803294 L0.275830079,5.31406419 C0.275501954,5.31435451 0.275267579,5.31469322 0.274986329,5.31498354 C-0.0910602746,5.69201601 -0.0922321487,6.30672603 0.274892579,6.68501657 C0.275220704,6.68530689 0.275455079,6.6856456 0.275736329,6.68593593 L5.1743577,11.7181968 C5.54129493,12.0951325 6.13491949,12.0938261 6.50021609,11.7149065 C6.86546582,11.3360837 6.86405957,10.7233575 6.49707547,10.3463251 L3.20823416,6.96774254 L23.0625007,6.96774254 C23.5802816,6.96774254 24,6.53448423 24,6 C24,5.46551588 23.5802816,5.03225758 23.0625007,5.03225758 Z" id="Path" ></path> </g> </g> </g> </g> </g> </g> </g> </svg>
              {{ trans('vars.General.HomewearStore') }}
          </a>
      </div>

      --}}
    <ul class="nav">
        <li class="burger">
            <div id="burger">
                <div class="iconBar"></div>
            </div>
        </li>
        <li class="logoCenter"><a href="{{ url('/'.$lang->lang.'/') }}"><img src="/fronts/img/icons/logonew1.png" alt="" /></a></li>
        <li>
            <ul class="desk-settings">
              @if (Auth::guard('persons')->user())
                  <a  href="{{ url('/'.$lang->lang.'/account/personal-data') }}" id="avatar">
              @else
                  <a href="" id="avatar" data-toggle="modal" data-target="#auth">
              @endif
                  <img src="/fronts/img/icons-settings/avatar.svg" alt="">
              </a>
              <settings-btn-mob
                      :countries="{{ $countries }}"
                      :country="{{ $country }}"
                      :currencies="{{ $currencies }}"
                      :currency="{{ $currency }}"
                      :lang="{{ $lang }}"
                      :langs="{{ $langs }}"
                  ></settings-btn-mob>
              <cart-counter-mob class="animated" id="cart" :items="{{ $cartProducts }}" site="loungewear">
              </cart-counter-mob>

            </ul>
        </li>
    </ul>
    <div class="navOpen" id="navOpen">
        <ul class="settings">
            {{-- <li class="widthSettings" data-toggle="modal" data-target="#userSettings">
                <p>
                    {{ $currency->abbr }} / {{ $lang->lang }} / <img src="/images/flags/24x24/{{ $country->flag }}" alt="icon" />
                </p>
                <p>|</p>
                <a data-toggle="modal" data-target="#userSettings">{{ trans('vars.TehButtons.change') }}</a>
            </li> --}}
            <settings-btn-mob
                    :countries="{{ $countries }}"
                    :country="{{ $country }}"
                    :currencies="{{ $currencies }}"
                    :currency="{{ $currency }}"
                    :lang="{{ $lang }}"
                    :langs="{{ $langs }}"
                ></settings-btn-mob>
        </ul>

        <div class="navCollection">
            <span id="collectionButton" class="collectionThis">Bijuterii Catalog</span>
            <ul class="navOpen" id="collectionsOpen">
                <li class="navBack"><span>Bijuterii Catalog</span></li>

                @if ($categoriesMenuJewelry->count() > 0)
                    <li class="collButton">
                        <a href="{{ url('/'.$lang->lang.'/bijoux/catalog/all') }}">
                            <span>Toate Produsele</span>
                        </a>
                    </li>
                    @foreach ($categoriesMenuJewelry as $key => $category)
                        <li class="collButton">
                            <a href="{{ url('/'.$lang->lang.'/bijoux/catalog/'. $category->alias) }}">
                                <span>{{ $category->translation->name }}</span>
                            </a>
                        </li>
                    @endforeach
                @endif
            </ul>
        </div>

        <div class="navCollection">
            <span id="collectionButton" class="collectionThis">Colectii de Bijuterii</span>
            <ul class="navOpen" id="collectionsOpen">
                <li class="navBack"><span>Colectii de Bijuterii</span></li>
                @if ($collectionsMenuJewelry->count() > 0)
                    @foreach ($collectionsMenuJewelry as $key => $collection)
                        <li class="collButton">
                            <a href="{{ url('/'.$lang->lang.'/bijoux/collection/'. $collection->alias) }}">
                                <span>{{ $collection->translation->name }}</span>
                            </a>
                        </li>
                    @endforeach
                @endif
            </ul>
        </div>

        {{-- <div class="navCollection">
            <span id="collectionButton" class="collectionThis">Homewear Catalog</span>
            <ul class="navOpen" id="collectionsOpen">
                <li class="navBack"><span>Homewear Catalog</span></li>
                @if ($categoriesMenuLoungewear->count() > 0)
                    <li class="collButton">
                        <a href="{{ url('/'.$lang->lang.'/homewear/catalog/all') }}">
                            <span>Toate Produsele</span>
                        </a>
                    </li>
                    @foreach ($categoriesMenuLoungewear as $key => $category)
                        <li class="collButton">
                            <a href="{{ url('/'.$lang->lang.'/homewear/catalog/'. $category->alias) }}">
                                <span>{{ $category->translation->name }}</span>
                            </a>
                        </li>
                    @endforeach
                @endif
            </ul>
        </div> --}}

        {{-- <div class="navCollection">
            <span id="collectionButton"  class="collectionThis">Colectii Homewear</span>
            <ul class="navOpen" id="collectionsOpen">
                <li class="navBack"><span>Colectii Homewear</span></li>

                @if ($collectionsMenuLoungewear->count() > 0)
                    @foreach ($collectionsMenuLoungewear as $key => $collection)
                        <li class="collButton">
                            <a href="{{ url('/'.$lang->lang.'/homewear/collection/'. $collection->alias) }}">
                                <span>{{ $collection->translation->name }}</span>
                            </a>
                        </li>
                    @endforeach
                @endif
            </ul>
        </div> --}}

        {{-- <a href="{{ url('/'.$lang->lang.'/promos') }}">{{ trans('vars.PagesNames.pagePromotions') }}</a> --}}
        <a href="{{ url('/'.$lang->lang.'/new') }}">{{ trans('vars.HeaderFooter.newIn') }}</a>
        <a href="{{ url('/'.$lang->lang.'/sale') }}">{{ trans('vars.HeaderFooter.outlet') }}</a>
        <a href="{{ url($lang->lang.'/about/')}}">{{ trans('vars.PagesNames.pageAboutUs') }}</a>

        @if (Auth::guard('persons')->user())
            <a  href="{{ url('/'.$lang->lang.'/account/personal-data') }}" id="avatar">
        @else
            <a href="" id="avatar" data-toggle="modal" data-target="#auth">
        @endif
            Account
        </a>
        <div class="navCollection">
            <span id="categoryButton">
                {{ trans('vars.General.helpInformation') }}
                <svg width="6px" height="14px" viewBox="0 0 6 14" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"> <title>Shape</title> <desc>Created with Sketch.</desc> <g id="AnaPopova-Site" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"> <g id="Burger_375---new" transform="translate(-310.000000, -173.000000)" fill="#42261D" fill-rule="nonzero"> <path d="M316,179.999496 C315.994424,179.76069 315.929214,179.61673 315.845738,179.513407 L311.263173,173.291465 C311.046736,172.9548 310.556192,172.901525 310.249549,173.174905 C309.942871,173.448285 309.926585,173.959445 310.172633,174.263643 L313.818842,179.221754 C314.053173,179.555878 314.170339,179.815125 314.170339,179.999496 C314.170339,180.183867 314.053173,180.443114 313.818842,180.777238 L310.172633,185.735345 C309.893521,186.042592 309.972834,186.58149 310.267456,186.870577 C310.494222,187.093127 311.098625,187.022197 311.263173,186.707523 L315.845738,180.485585 C315.965078,180.324437 316.001411,180.204626 316,179.999496 Z" id="Shape"></path> </g> </g> </svg>
            </span>
            <ul class="navOpen navOneCollection" id="categoryOpen">
                <li>
                    <span><a href="{{ url('/'.$lang->lang.'/contacts') }}">{{ trans('vars.Contacts.contactsTitle') }}</a></span>
                </li>
                <li>
                    <span><a href="{{ url('/'.$lang->lang.'/livrare-achitare-retur') }}">{{ trans('vars.DetailsProductSet.shippingPaymentReturns') }}</a></span>
                </li>
                <li>
                    <span><a href="{{ url('/'.$lang->lang.'/size-guide') }}">{{ trans('vars.PagesNames.pageSizeGuide') }}</a></span>
                </li>
            </ul>
        </div>
        <div class="networks">
            <p>{{ trans('vars.HeaderFooter.followUs') }}:</p>
            <ul class="dspflex">
                <li>
                    <a href="{{ trans('vars.Contacts.instagram') }}">
                        <svg width="18px" height="18px" viewBox="0 0 18 18" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" > <g id="Symbols" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"> <g id="Footer" transform="translate(-1548.000000, -431.000000)" fill="#B22D00" fill-rule="nonzero" > <g id="social"> <g transform="translate(1410.000000, 427.000000)"> <g id="instagram-social-network-logo-of-photo-camera" transform="translate(64.000000, 4.000000)" > <path d="M76.3103887,0 L89.6897961,0 C90.9604082,0 92,0.940705375 92,2.31018019 L92,15.6898198 C92,17.0592946 90.9604082,18 89.6897961,18 L76.3103887,18 C75.0394069,18 74,17.0592946 74,15.6898198 L74,2.31018019 C74,0.940705375 75.0394069,0 76.3103887,0 L76.3103887,0 Z M87.8865291,2 C87.398665,2 87,2.40944718 87,2.91045938 L87,5.08954062 C87,5.59034519 87.398665,6 87.8865291,6 L90.1130663,6 C90.6009304,6 91,5.59034519 91,5.08954062 L91,2.91045938 C91,2.40944718 90.6009304,2 90.1130663,2 L87.8865291,2 L87.8865291,2 Z M89.9996295,8 L88.4115078,8 C88.5617606,8.4706196 88.6430935,8.96914225 88.6430935,9.48490436 C88.6430935,12.3640726 86.1315936,14.6979761 83.0340894,14.6979761 C79.9367705,14.6979761 77.4256412,12.3640726 77.4256412,9.48490436 C77.4256412,8.96878679 77.5067888,8.47044187 77.6572268,8 L76,8 L76,15.3118432 C76,15.6902228 76.3227377,16 76.7173597,16 L89.2826403,16 C89.6772623,16 90,15.6904006 90,15.3118432 L90,8 L89.9996295,8 Z M83.4998211,6 C81.5670467,6 80,7.56690234 80,9.5 C80,11.4330977 81.5670467,13 83.4998211,13 C85.4327744,13 87,11.4330977 87,9.5 C87,7.56690234 85.4329533,6 83.4998211,6 Z" id="Shape" ></path> </g> </g> </g> </g> </g> </svg>
                    </a>
                </li>
                <li>
                    <a href="/">
                        <svg width="10px" height="19px" viewBox="0 0 10 19" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" > <g id="Symbols" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"> <g id="Footer" transform="translate(-1732.000000, -428.000000)" fill="#B22D00" fill-rule="nonzero" > <g id="social"> <g transform="translate(1410.000000, 427.000000)"> <path d="M330.187221,4.15465514 C328.766054,4.15465514 328.490905,4.82104815 328.490905,5.79901691 L328.490905,7.95536596 L331.880335,7.95536596 L331.879135,11.3329419 L328.490905,11.3329419 L328.490905,20 L324.955596,20 L324.955596,11.3329419 L322,11.3329419 L322,7.95536596 L324.955596,7.95536596 L324.955596,5.46473443 C324.955596,2.57406965 326.745162,1 329.358574,1 L332,1.00414645 L331.9998,4.15386534 L330.187221,4.15465514 Z" id="Shape-Copy" transform="translate(327.000000, 10.500000) rotate(-360.000000) translate(-327.000000, -10.500000) " ></path> </g> </g> </g> </g> </svg>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</header>
