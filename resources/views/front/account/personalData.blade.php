@extends('front.app')
@section('content')
@include('front.partials.header')
<main class="clientArea">
    <div class="container">
        <div class="row">

            <div class="col-12">
                <div class="user">
                    <p>{{ trans('vars.General.hello') }}, {{ $userdata->name }}</p>
                    <p>{{ trans('vars.Cabinet.welcomeTo') }} {{ trans('vars.Cabinet.yourPersonalData') }}</p>
                </div>
            </div>
            <div class="col-lg-auto col-md-12">
                <div class="navArea" id="navArea">
                    <div id="pageSelected">
                        {{ trans('vars.Cabinet.yourPersonalData') }}
                        <svg
                            width="12px"
                            height="6px"
                            viewBox="0 0 12 6"
                            version="1.1"
                            xmlns="http://www.w3.org/2000/svg"
                            xmlns:xlink="http://www.w3.org/1999/xlink"
                            >
                            <g id="AnaPopova-Site" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <g
                                    id="Cabinet_Mob._375-cos"
                                    transform="translate(-325.000000, -156.000000)"
                                    fill="#B22D00"
                                    fill-rule="nonzero"
                                    >
                                    <polygon
                                        id="Shape"
                                        transform="translate(331.000000, 159.000000) scale(1, -1) translate(-331.000000, -159.000000) "
                                        points="331 156 325 162 330.716323 162 337 162"
                                        ></polygon>
                                </g>
                            </g>
                        </svg>
                    </div>
                    @include('front.account.accountMenu')
                </div>
            </div>
            <div class="col-lg col-md-12">
                <div class="personalData">
                    <div class="row">
                        <div class="col-12">
                            <p class="name">{{ $userdata->name }} {{ $userdata->surname }}</p>
                            <p>{{ $userdata->email }}</p>
                            <p>{{ $userdata->phone }}</p>
                        </div>
                        <div class="col-12">
                            <button data-toggle="modal" data-target="#userData">{{ trans('vars.Cabinet.modify') }}</button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <p>{{ trans('vars.FormFields.pass') }}</p>
                            <p>********</p>
                        </div>
                        <div class="col-12">
                            <button data-toggle="modal" data-target="#changePasswords">{{ trans('vars.Cabinet.modify') }}</button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <p>{{ trans('vars.Cabinet.shipiingAddress') }}</p>
                            <p></p>
                        </div>
                        <div class="col-12">
                            @if ($userdata->address)
                            <button data-toggle="modal" data-target="#changeAddress">{{ trans('vars.General.modify') }}</button>
                            @else
                            <button data-toggle="modal" data-target="#addAddress">{{ trans('vars.Cabinet.FillYourAddress') }}</button>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            {{-- <button onclick="location.href='{{ url('/'.$lang->lang.'/account/remove-account') }}';">{{ trans('vars.Cabinet.removeAccount') }}</button> --}}
                            <button data-toggle="modal" data-target="#removeAccount">
                                {{ trans('vars.Cabinet.removeAccount') }}
                            </button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <button onclick="location.href='{{ url('/'. $lang->lang .'/'. $site . '/logout') }}';">{{ trans('vars.Cabinet.logout') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<div class="modals">
    {{-- Modals --}}

    <div class="modal" id="removeAccount">
        <div class="modal-dialog">
            <div class="modal-content">
                <form class="modalContent">
                    <div class="closeModal" data-dismiss="modal">
                        <img src="/fronts/img/icons/plusIconBlack.svg" alt="" />
                    </div>
                    <div class="modalTitle">
                        {{ trans('vars.Cabinet.accountDeletionQuestion') }}
                    </div>
                    <div class="col-12">
                        <input type="button" class="butt buttView"  onclick="location.href='{{ url('/'.$lang->lang.'/account/remove-account') }}';" value="{{ trans('vars.TehButtons.btnYes') }}" />
                        <input type="button" class="butt buttView" data-dismiss="modal" value="{{ trans('vars.TehButtons.btnNo') }}" />
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal" id="userData">
        <div class="modal-dialog">
            <div class="modal-content">
                <form class="modalContent" action="{{ url('/'.$lang->lang.'/account/savePersonalData') }}" method="post">
                    {{ csrf_field() }}
                    <div class="closeModal" data-dismiss="modal">
                        <img src="/fronts/img/icons/plusIconBlack.svg" alt="" />
                    </div>
                    <div class="modalTitle">
                        Change Data
                    </div>
                    <div class="modalBody">
                        <div class="inputGroup">
                            <label for="name">Name</label>
                            <input type="text" placeholder="Name" name="name" required value="{{ $userdata->name }}" />
                        </div>
                        <div class="inputGroup">
                            <label for="name">E-mail</label>
                            <input type="email" placeholder="Email" name="email" required value="{{ $userdata->email }}" />
                        </div>
                        <div class="inputGroup">
                            <label for="name">Phone</label>
                            <div class="phoneContainer">
                                <div id="codSelected">
                                    <img src="./img/prod/flag.svg" alt="" />
                                    <span>+373</span>
                                    <svg width="10px" height="6px" viewBox="0 0 10 6" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                        <g id="AnaPopova-Site" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                            <g id="Checout_375--Step1" transform="translate(-97.000000, -502.000000)" fill="#B22D00" fill-rule="nonzero">
                                                <g id="Shipping-information" transform="translate(9.000000, 268.000000)">
                                                    <g id="Contact" transform="translate(3.000000, 45.000000)">
                                                        <g id="TELEFON" transform="translate(0.000000, 147.000000)">
                                                            <polygon id="Shape" transform="translate(90.000000, 45.000000) scale(1, -1) translate(-90.000000, -45.000000) " points="90 42 85 48 89.7636023 48 95 48"></polygon>
                                                        </g>
                                                    </g>
                                                </g>
                                            </g>
                                        </g>
                                    </svg>
                                </div>
                                <input type="number" placeholder="Telefon" name="phone" required value="{{ $userdata->phone }}" />
                            </div>
                        </div>
                        <input type="submit" value="{{ trans('vars.FormFields.save') }}" />
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal" id="changePasswords">
        <div class="modal-dialog">
            <div class="modal-content">
                <form class="modalContent" action="{{ url('/'.$lang->lang.'/account/changePass') }}" method="post">
                    {{ csrf_field() }}
                    <div class="closeModal" data-dismiss="modal">
                        <img src="/fronts/img/icons/plusIconBlack.svg" alt="" />
                    </div>
                    <div class="modalTitle">
                        Change Password
                    </div>
                    <div class="modalBody">
                        <div class="inputGroup">
                            <label for="name">New password</label>
                            <input type="password" placeholder="new password" name="newpass" required/>
                        </div>
                        <div class="inputGroup">
                            <label for="name">New password</label>
                            <input type="password" placeholder="new password again" name="repeatnewpass" required/>
                        </div>
                        <input type="submit" value="{{ trans('vars.FormFields.save') }}" />
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal" id="changePasswords_">
        <div class="modal-dialog">
            <div class="modal-content">
                <form class="modalContent" action="{{ url('/'.$lang->lang.'/account/changePass') }}" method="post">
                    {{ csrf_field() }}
                    <a class="closeModal" data-dismiss="modal">
                        <div class="icon"></div>
                    </a>
                    <div class="modalTitle">
                        <span>Modifica parola</span>
                    </div>
                    <div class="col-md-12">
                        <label for="name">new password</label>
                        <input type="password" placeholder="new password" name="newpass" required/>
                        <label for="name">new password again</label>
                        <input type="password" placeholder="new password again" name="repeatnewpass" required/>
                    </div>
                    <div class="col-12">
                        <input type="submit" class="butt buttView" value="{{ trans('vars.FormFields.save') }}" />
                    </div>
                </form>
            </div>
        </div>
    </div>
    @if (!is_null($userdata->address))
    <div class="modal" id="changeAddress">
        <div class="modal-dialog">
            <div class="modal-content">
                <form class="modalContent" action="{{ url('/'.$lang->lang.'/account/saveAddress/'.$userdata->address->id) }}" method="post">
                    {{ csrf_field() }}
                    <div class="closeModal" data-dismiss="modal">
                        <img src="/fronts/img/icons/plusIconBlack.svg" alt="" />
                    </div>
                    <div class="modalTitle">
                        <span>{{ trans('vars.General.modifyAddress') }}</span>
                    </div>
                    <div class="col-md-12">
                        <div class="inputGroup">
                          <label for="country">Country</label>
                          <div class="selectContainer">
                            <select name="country_id" class="js-example-basic-single">
                            @foreach ($countries as $key => $countryItem) @if ($userdata->address)
                            <option value="{{ $countryItem->id }}" data-image="/images/flags/16x16/{{ $countryItem->flag }}" {{ $countryItem->id == $userdata->address->country ? 'selected' : '' }}> {{ $countryItem->name }}
                            </option>
                            @else
                            <option value="{{ $countryItem->id }}" data-image="/images/flags/16x16/{{ $countryItem->flag }}" {{ $country->id == $countryItem->id ? 'selected' : ''}}> {{ $countryItem->name }}
                            </option>
                            @endif @endforeach
                            </select>
                            <span><svg width="10px" height="6px" viewBox="0 0 10 6" version="1.1" xmlns="http://www.w3.org/2000/svg"><g id="AnaPopova-Site" stroke="none" strokewidth="1" fill="none" fillrule="evenodd"><g id="Cos._APL---" transform="translate(-1592.000000, -545.000000)" fill="#42261D" fillrule="nonzero"><g id="Order-summery" transform="translate(1233.000000, 423.000000)"><g id="Ship" transform="translate(15.000000, 108.000000)"><polygon id="Shape" transform="translate(349.000000, 17.000000) scale(1, -1) translate(-349.000000, -17.000000) " points="349 14 344 20 348.763602 20 354 20"></polygon></g></g></g></g></svg></span>
                          </div>
                        </div>
                        <div class="inputGroup">
                          <label for="region">Region</label>
                          <input type="text" placeholder="Region" name="region" value="{{ $userdata->address->region }}" required/>
                        </div>
                        <div class="inputGroup">
                          <label for="city">City</label>
                          <input type="text" placeholder="City" name="city" value="{{ $userdata->address->location }}" required/>
                        </div>
                        <div class="inputGroup">
                          <label for="address">Address</label>
                          <input type="text" placeholder="Address" name="address" value="{{ $userdata->address->address }}" required/>
                        </div>
                        <div class="inputGroup">
                          <label for="homenumber">Home number</label>
                          <input type="number" placeholder="Home number" name="homenumber" value="{{ $userdata->address->homenumber }}" />
                        </div>
                        <div class="inputGroup">
                          <label for="code">Zip code</label>
                          <input type="number" placeholder="Zip code" name="code" value="{{ $userdata->address->code }}" required/>
                        </div>
                    </div>
                    <div class="col-12">
                        <input type="submit" class="butt buttView" value="{{ trans('vars.FormFields.save') }}" />
                    </div>
                </form>
            </div>
        </div>
    </div>
    @else
    <div class="modal" id="addAddress">
        <div class="modal-dialog">
            <div class="modal-content">
                <form class="modalContent" action="{{ url('/'.$lang->lang.'/account/addAddress/') }}" method="post">
                    {{ csrf_field() }}
                    <div class="closeModal" data-dismiss="modal">
                        <img src="/fronts/img/icons/plusIconBlack.svg" alt="" />
                    </div>
                    <div class="modalTitle">
                        <span>{{ trans('vars.General.modifyAddress') }}</span>
                    </div>
                    <div class="col-md-12">
                        <div class="inputGroup">
                          <label for="country">Country</label>
                          <div class="selectContainer">
                            <select class="" name="country_id">
                            @foreach ($countries as $key => $country)
                            <option value="{{ $country->id }}" {{ $country->id == $_COOKIE['country_id'] ? 'selected' : '' }}>{{ $country->name }}</option>
                            @endforeach
                            </select>
                            <span><svg width="10px" height="6px" viewBox="0 0 10 6" version="1.1" xmlns="http://www.w3.org/2000/svg"><g id="AnaPopova-Site" stroke="none" strokewidth="1" fill="none" fillrule="evenodd"><g id="Cos._APL---" transform="translate(-1592.000000, -545.000000)" fill="#42261D" fillrule="nonzero"><g id="Order-summery" transform="translate(1233.000000, 423.000000)"><g id="Ship" transform="translate(15.000000, 108.000000)"><polygon id="Shape" transform="translate(349.000000, 17.000000) scale(1, -1) translate(-349.000000, -17.000000) " points="349 14 344 20 348.763602 20 354 20"></polygon></g></g></g></g></svg></span>
                          </div>
                        </div>
                        <div class="inputGroup">
                          <label for="region">Region</label>
                          <input type="text" placeholder="Region" name="region" value="" required/>
                        </div>
                        <div class="inputGroup">
                          <label for="city">City</label>
                          <input type="text" placeholder="City" name="city" value="" required/>
                        </div>
                        <div class="inputGroup">
                          <label for="address">Address</label>
                          <input type="text" placeholder="Address" name="address" value="" required/>
                        </div>
                        <div class="inputGroup">
                          <label for="homenumber">Home number</label>
                          <input type="number" placeholder="Home number" name="homenumber" value="" />
                        </div>
                        <div class="inputGroup">
                          <label for="code">Zip code</label>
                          <input type="number" placeholder="Zip code" name="code" value="" required/>
                        </div>
                    </div>
                    <div class="col-12">
                        <input type="submit" class="butt buttView" value="{{ trans('vars.FormFields.save') }}" />
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>
@include('front.partials.footer')
@stop
