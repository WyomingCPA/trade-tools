@extends('layouts.app', ['activePage' => 'trashes_bond', 'titlePage' => __('Скрытые') ])
@section('content')
<div class="container pt-5">
    <div class="row pt-5">
        <div class="col-md-12">
            <div id="app">
                <bond-hide-table :bonds="{{ $bonds }}"></bond-hide-table>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script src="{{ asset('/js/app.js') }}"></script>
@endsection