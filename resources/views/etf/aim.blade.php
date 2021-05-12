@extends('layouts.app', ['activePage' => 'aim_etf', 'titlePage' => __('Фонды') ])
@section('content')
<div class="container pt-5">
    <div class="row">
        <div class="col-md-12">
            <a href="{{ route('etf.aim.create') }}" class="btn btn-primary">Добавить цель</a>
        </div>
    </div>
    <div class="row pt-5">
        <div class="col-md-12">
            <form class="form" method="post" action="{{ route('etf.aim.update') }}">
                @csrf
                <table class="table">
                    <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th>Цель</th>
                            <th>Description</th>
                            <th class="text-right">Etfs</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($aims as $item)
                        <tr>
                            <td><input class="form-check-input" type="checkbox" name="selection[]" value="{{ $item->id}}"></td>
                            <td>{{ $item->aim_name }}</td>
                            <td>{{ $item->event_detail }}</td>
                            <td>
                                @foreach ($item->etfs as $etf)
                                <a target="_blank" href="https://www.tinkoff.ru/invest/etfs/{{$etf->ticker}}">{{ $etf->name }}</a></br>
                                @endforeach
                            </td>
                            <td class="td-actions text-right">
                                <button type="button" rel="tooltip" class="btn btn-success">
                                    <i class="material-icons">edit</i>
                                </button>
                                <button type="button" rel="tooltip" class="btn btn-danger">
                                    <i class="material-icons">close</i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </form>
        </div>
    </div>
</div>

@endsection
@section('scripts')

@endsection