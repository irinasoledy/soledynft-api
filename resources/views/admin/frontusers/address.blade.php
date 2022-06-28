<ul>
    <li>
        <label for="country">Country</label>
        @if ($user->country)
            <input type="text" name="" value="{{ $user->country->name }}" disabled>
        @else
            <input type="text" name="" value="Not detected" disabled>
        @endif
        {{-- <select name="country" class="name filterCountries" data-id="0" id="country">
            @foreach ($countries as $onecountry)
                <option value="{{$onecountry->id}}" {{ $user->country_id == $country->id  ? 'selected' : '' }}>{{$onecountry->translation->name}}</option>
            @endforeach
        </select> --}}
    </li>
    <li>
        <label for="region">Region</label>
        <input type="text" name="region" class="name" id="region" value="{{old('region')}}">
    </li>
    <li>
        <label for="location">City</label>
        <input type="text" name="location" class="name" id="location" value="{{old('location')}}">
    </li>
    <li>
        <label for="address">Address</label>
        <input type="text" name="address" class="name" id="address" value="{{old('address')}}">
    </li>
    <li>
        <label for="apartment">Apartment</label>
        <input type="text" name="apartment" class="name" id="apartment" value="{{old('apartment')}}">
    </li>
    <li>
        <label for="code">Zip</label>
        <input type="text" name="code" class="name" id="code" value="{{old('code')}}">
    </li>
</ul>
