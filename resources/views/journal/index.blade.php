@extends('layouts.app', ['activePage' => 'journal', 'titlePage' => __('Журнал') ])
@section('content')
<div class="container pt-5">
    <div class="row">
        <div class="col-md-3">
            <div class="card card-stats">
                <div class="card-header card-header-success card-header-icon">
                    <h3 class="card-title">{{ $profit_day}} </h3>
                </div>
                <div class="card-footer">
                    <div class="stats">
                        Доход с операций за день
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-stats">
                <div class="card-header card-header-success card-header-icon">
                    <h3 class="card-title">{{ $profit_month }}</h3>
                </div>
                <div class="card-footer">
                    <div class="stats">
                        Доход с операций за месяц
                    </div>
                </div>
            </div>
        </div>
    </div>
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