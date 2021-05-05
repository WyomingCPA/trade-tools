@extends('layouts.app', ['activePage' => 'all_etf', 'titlePage' => __('Фонды') ])
@section('content')
<div class="container pt-5">
    <div class="row pt-5">
        <div class="col-md-12">
            <div id="app">
                <etf-all-table :etfs="{{ $etfs }}"></etf-all-table>
            </div>
        </div>
    </div>
</div>

@endsection
@section('scripts')
<script src="{{ asset('/js/app.js') }}"></script>

@endsection