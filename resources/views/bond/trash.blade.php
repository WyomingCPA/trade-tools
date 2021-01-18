@extends('layouts.app', ['activePage' => 'trash_bond', 'titlePage' => __('Скрытые') ])

@section('content')
<div class="content">
    <div class="container-fluid">
        <div class="container-fluid">
            @foreach (['danger', 'warning', 'success', 'info'] as $key)
            @if(Session::has($key))
            <p class="alert alert-{{ $key }}">{{ Session::get($key) }}</p>
            @endif
            @endforeach
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <h3 class="card-header text-center font-weight-bold text-uppercase py-4"></h3>
                        <div class="card-body">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Название</th>
                                        <th>figi</th>
                                        <th>ticker</th>
                                        <th>isin</th>
                                        <th>Номинал</th>
                                        <th>Валюта</th>
                                        <th>Последняя цена</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($bonds as $item)
                                    <tr>
                                        <td></td>
                                        <td>
                                            <a target="_blank" href='https://www.tinkoff.ru/invest/bonds/{{ $item->ticker }}/'>{{ $item->name }}</a>
                                        </td>
                                        <td>{{ $item->figi }}</td>
                                        <td class="table-cell">{{ $item->ticker }}</td>
                                        <td>{{ $item->isin }}</td>
                                        <td>{{ $item->faceValue }}</td>

                                        <td>{{ $item->currency }}</td>
                                        <td>{{ $item->lastPrice->last()->close ?? 'No' }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="col-md-12">
                                {{ $bonds->links('pagination.default') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection