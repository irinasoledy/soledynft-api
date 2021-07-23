@extends('front.app')
@section('content')
@include('front.partials.header')
<main class="contactContent cartClass">
    <div class="contactBanner">
      <img src="/images/contactBanner.jpg" alt="">
      <div class="test">
        <div class="container">
          <div class="innerTest">
            <h3>{{ trans('vars.Contacts.contactUs') }}</h3>
            <p><p>{{ trans('vars.Contacts.ContactsSubtitle') }}</p></p>
          </div>
        </div>
      </div>
    </div>
    <div class="container">
        <div class="row">

            <div class="col-12">
                <h3>{{ trans('vars.Contacts.contactUs') }}</h3>
            </div>
        </div>
    </div>
    <section class="map-contact">
      <div class="container">
        <div class="row">
          <div class="col-lg-5 col-md-6 col-12">
            <div class="hours-plan">
              <h5>{{ trans('vars.ContactsAndForms.graficLucruTitle') }}</h5>
              <p>{{ trans('vars.ContactsAndForms.graficLucruSubTitle') }}</p>
              <ul>
                <li><span>{{ trans('vars.ContactsAndForms.monday') }}</span><span>{{ trans('vars.ContactsAndForms.mondayFridayHours') }}</span></li>
                <li><span>{{ trans('vars.ContactsAndForms.tuesday') }}</span><span>{{ trans('vars.ContactsAndForms.mondayFridayHours') }}</span></li>
                <li><span>{{ trans('vars.ContactsAndForms.wednesday') }}</span><span>{{ trans('vars.ContactsAndForms.mondayFridayHours') }}</span></li>
                <li><span>{{ trans('vars.ContactsAndForms.thursday') }}</span><span>{{ trans('vars.ContactsAndForms.mondayFridayHours') }}</span></li>
                <li><span>{{ trans('vars.ContactsAndForms.friday') }}</span><span>{{ trans('vars.ContactsAndForms.mondayFridayHours') }}</span></li>
                <li><span>{{ trans('vars.ContactsAndForms.saturday') }}</span><span>{{ trans('vars.ContactsAndForms.saturdayHours') }}</span></li>
                <li><span>{{ trans('vars.ContactsAndForms.sunday') }}</span><span>{{ trans('vars.ContactsAndForms.closed') }}</span></li>
              </ul>
            </div>
          </div>
        </div>
      </div>
      <div class="mapouter">
      <div class="gmap_canvas">


        <iframe
          id="gmap_canvas"
          src="https://maps.google.com/maps?q=Moldova%2C%20Chisinau%2C%20str.%20Puskin%205B&t=&z=13&ie=UTF8&iwloc=&output=embed"
          frameborder="0"
          scrolling="no"
          marginheight="0"
          marginwidth="0"
        ></iframe>
        <a href="https://www.embedgooglemap.net"></a>
      </div>
      <style>
        .mapouter {
          text-align: right;
          height: 100%;
          width: 100%;
        }
        .gmap_canvas {
          overflow: hidden;
          background: none !important;
          height: 100%;
          width: 100%;
        }
      </style>
    </div>
    </section>
    <section class="formcontact" id="formcontact">
        <div class="container">
            <div class="row">
                <div class="col-12">
                  <div class="workSchedule">
                    <p>
                      {{ trans('vars.Contacts.schedule') }}:
                    </p>
                    <p>
                      {{ trans('vars.Contacts.scheduleInfoWeekdays') }}
                    </p>
                    <p>
                      {{ trans('vars.Contacts.scheduleInfoWeekEnds') }}
                    </p>
                  </div>
                    <form action="{{ url('/'.$lang->lang.'/homewear/contact-feed-back') }}" method="post">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <h3>{{ trans('vars.Contacts.contactUs') }}</h3>
                        <p>{{ trans('vars.Contacts.fillTheForm') }}:</p>
                        <label>{{ trans('vars.FormFields.fieldFullName') }}</label>
                        <input type="text" name="name" required/>
                        <label>{{ trans('vars.FormFields.fieldphone') }}</label>
                        <input name="phone" type="number" required />
                        <label for="email">{{ trans('vars.FormFields.fieldEmail') }}</label>
                        <input name="email" type="email" required />
                        <label>{{ trans('vars.FormFields.contactPopupMessage') }}:</label>
                        <textarea rows="3" name="message" required></textarea>
                        {{-- <div class="g-recaptcha" data-sitekey="your_site_key"></div> --}}
                        <button type="submit" class="butt">
                            <span>{{ trans('vars.FormFields.send') }} <b></b><b></b><b></b></span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>
</main>
@include('front.partials.footer')
@stop
