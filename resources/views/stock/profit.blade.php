@extends('layouts.app', ['activePage' => 'profit_stock', 'titlePage' => __('Открытые сделки')])
@section('content')
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <h3 class="card-header text-center font-weight-bold text-uppercase py-4"></h3>
                    <div class="card-body">
                        <form class="form" method="post" action="{{ route('stock.favorites.post') }}">
                            @csrf
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>order_id</th>
                                        <th>figi</th>
                                        <th>price</th>
                                        <th>take_profit</th>
                                        <th>stop_loss</th>
                                        <th>action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($profits as $item)
                                    <tr>
                                        <td>
                                            {{ $item->order_id }}
                                        </td>
                                        <td>{{ $item->figi }}</td>
                                        <td class="table-cell">{{ $item->price }}</td>
                                        <td>{{ $item->take_profit }}</td>
                                        <td>{{ $item->stop_loss }}</td>
                                        <td>
                                            <a href="profit/update/{{ $item->id }} " class="btn btn-primary">Редактировать стопы</a>
                                            <a href="profit/sell" class="btn btn-primary">Продать</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection