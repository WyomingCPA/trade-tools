@extends('layouts.app', ['activePage' => 'profit_stock', 'titlePage' => __('Обновить стопы')])
@section('content')
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-4 col-md-5 col-sm-5">
                <div class="card">
                    <div class="card-body">
                        <form class="form" method="post" action="{{ route('stock.profit.edit') }}">
                            @csrf
                            <div class="form-outline">
                                <input type="number" id="takeProfit" class="form-control" value="{{ $take_profit }}"/>
                                <label class="form-label" for="takeProfit">Take Profit</label>
                                <input type="number" id="stopLoss" class="form-control" value="{{ $stop_loss }}"/>
                                <label class="form-label" for="stopLoss">Stop Loss</label>
                                <input name="id" type="hidden" value="{{ $id }}">
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <button name='save' value='1' type="submit" class="btn btn-primary btn-link btn-lg">Save</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection