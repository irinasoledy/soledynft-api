@extends('admin.google.app')
@section('content')

<div class="wrapp">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th> Brand ID </th>
                <th> Brand Title</th>
            </tr>
        </thead>
        @foreach ($brands as $key => $brand)
            <tr>
                <td>
                    {{ $brand->id }}
                </td>
                <td>
                    {{ $brand->translation->name }}
                </td>
            </tr>
        @endforeach
    </table>
</div>

@stop
