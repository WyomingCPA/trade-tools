@extends('layouts.app', ['activePage' => 'action', 'titlePage' => __('Действие')])
@section('content')
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-4 col-md-5 col-sm-5">
                <div class="card">
                    <div class="card-body">
                        <form class="form" method="post" action="{{ route('stock.action.order') }}">
                            @csrf
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Название</th>
                                        <th>Цена</th>
                                        <th>Максимальный лот</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td></td>
                                        <td>{{ $price }}</td>
                                        <td>{{ $max_lots }}</td>
                                    </tr>
                                </tbody>
                            </table>
                            <input name="id" type="hidden" value="{{ $id }}">
                            <input name="price" type="hidden" value="{{ $price }}">
                            <input name="max_lots" type="hidden" value="{{ $max_lots }}">
                            <div class="row">
                                <div class="col-md-12">
                                    <button name='order' value='1' type="submit" class="btn btn-primary btn-link btn-lg">Order</button>
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