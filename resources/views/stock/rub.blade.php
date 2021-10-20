@extends('layouts.app', ['activePage' => 'rub_stock', 'titlePage' => __('Рублевые акций') ])
@section('content')
<div class="container pt-5">
    <div class="row pt-5">
        <div class="col-md-12">
            <div id="app">
                <stock-all-table data-url="rub" :stocks="{{ $stocks }}" :count="{{ $count }}"></stock-all-table>
            </div>
        </div>
    </div>
</div>

@endsection
@section('scripts')
<script src="{{ asset('/js/app.js') }}"></script>

@endsection