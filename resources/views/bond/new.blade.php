@extends('layouts.app', ['activePage' => 'new_bond', 'titlePage' => __('Новые за последние 7 дней') ])
@section('content')
<div class="container pt-5">
    <div class="row pt-5">
        <div class="col-md-12">
            <div id="app">
                <bond-new-table :bonds="{{ $bonds }}"></bond-new-table>
            </div>
        </div>
    </div>
</div>

@endsection
@section('scripts')
<script src="{{ asset('/js/app.js') }}"></script>

@endsection