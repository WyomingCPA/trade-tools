@extends('layouts.app', ['activePage' => 'usd_stock', 'titlePage' => __('Долларовые акций') ])
@section('content')
<div class="container pt-5">
    <div class="row pt-5">
        <div class="col-md-12">
            <div id="app">
                <stock-all-table :stocks="{{ $stocks }}"></stock-all-table>
            </div>
        </div>
    </div>
</div>

@endsection
@section('scripts')
<script src="{{ asset('/js/app.js') }}"></script>

@endsection