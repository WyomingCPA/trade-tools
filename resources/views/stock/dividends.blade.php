@extends('layouts.app', ['activePage' => 'dividends', 'titlePage' => __('Акций с дивидендами') ])
@section('content')
<div class="container pt-5">
    <div class="row pt-5">
        <div class="col-md-12">
            <div id="app">
                <stock-dividends-table :stocks="{{ $stocks }}"></stock-dividends-table>
            </div>
        </div>
    </div>
</div>

@endsection
@section('scripts')
<script src="{{ asset('/js/app.js') }}"></script>

@endsection