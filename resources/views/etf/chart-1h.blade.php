@extends('layouts.app', ['activePage' => 'all_etf', 'titlePage' => __('EMA') ])
@section('content')
<div class="container pt-5">
    <div class="row pt-5">
        <div class="col-md-12">
            <div id="app" style="width: 731px; height: 1050px;">
                <etf-chars 
                :candles="{{ json_encode($candles) }}" 
                :rsi_data="{{ json_encode($rsi_data) }}" 
                ></etf-chars>
            </div>
        </div>
    </div>
</div>

@endsection
@section('scripts')
<script src="{{ asset('/js/app.js') }}"></script>

@endsection