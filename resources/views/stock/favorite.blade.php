@extends('layouts.app', ['activePage' => 'favorites_stock', 'titlePage' => __('Избранные') ])

@section('content')
<div class="container pt-5">
    <div class="row pt-5">
        <div class="col-md-12">
            <div id="app">
                <stock-favorite-table :stocks="{{ $stocks }}"></stock-favorite-table>
            </div>
        </div>
    </div>
</div>

@endsection
@section('scripts')
<script src="{{ asset('/js/app.js') }}"></script>

@endsection