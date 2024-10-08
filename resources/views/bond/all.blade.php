@extends('layouts.app', ['activePage' => 'all_bond', 'titlePage' => __('Облигаций') ])
@section('content')
<div class="container pt-5">
    <div class="row pt-5">
        <div class="col-md-12">
            <div id="app">
                <bond-all-table :bonds="{{ $bonds }}"></bond-all-table>
            </div>
        </div>
    </div>
</div>

@endsection
@section('scripts')
<script src="{{ asset('/js/app.js') }}"></script>

@endsection