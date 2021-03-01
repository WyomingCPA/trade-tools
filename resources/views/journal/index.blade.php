@extends('layouts.app', ['activePage' => 'journal', 'titlePage' => __('Журнал') ])
@section('content')
<div class="container pt-5">
    <div class="row pt-5">
        <div class="col-md-12">
            <div id="app">
                <journal :orders="{{ $orders }}"></journal>
            </div>
        </div>
    </div>
</div>

@endsection
@section('scripts')
<script src="{{ asset('/js/app.js') }}"></script>

@endsection