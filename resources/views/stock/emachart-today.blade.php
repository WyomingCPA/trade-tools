@extends('layouts.app', ['activePage' => 'all_stock', 'titlePage' => __('EMA') ])
@section('content')
<div class="container pt-5">
    <div class="row pt-5">
        <div class="col-md-12">
            <div id="app" style="width: 731px; height: 1050px;">
                <chars :candles="{{ json_encode($candles) }}" :rsi_data="{{ json_encode($rsi_data) }}" :ema_indicators="{{ json_encode($ema_indicators) }}"></chars>
            </div>
        </div>
    </div>
    <div class="row pt-5">
        <div class="col-md-12">
            <div class="card">
                <h3 class="card-header text-center font-weight-bold text-uppercase py-4"></h3>
                <div class="card-body">
                    <form class="form" method="post" action="{{ route('stock.favorites.post') }}">
                        @csrf
                        <table class="table">
                            <thead>
                                <tr>
                                    <th><input name="select_all" value="1" id="example-select-all" class="form-check-input" type="checkbox"></th>
                                    <th>Инструмент</th>
                                    <th>Event</th>
                                    <th>Send Telegramm</th>
                                    <th>Обновлено</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($event as $item)
                                <tr>
                                    <td><input class="form-check-input" type="checkbox" name="selection[]" value="{{ $item->id}}"></td>
                                    <td>
                                        <a target="_blank" href="https://www.tinkoff.ru/invest/stocks/{{$item->stock->ticker}}">{{ $item->stock->name}}</a>
                                    </td>
                                    <td>{{ $item->action }}</td>
                                    <td class="table-cell">{{ $item->send_telegramm }}</td>
                                    <td>{{ $item->updated_at }}</td>
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

@endsection
@section('scripts')
<script src="{{ asset('/js/app.js') }}"></script>
<script>
    document.querySelectorAll(".table-cell").forEach(function(elm) {
        elm.addEventListener("click", function(e) {
            e.target.style.backgroundColor = 'red';
            var copyText = e.target.textContent;
            const el = document.createElement('textarea');
            el.value = copyText;
            document.body.appendChild(el);
            el.select();
            document.execCommand('copy');
            document.body.removeChild(el);
            /* Alert the copied text */
        });

    })
</script>
@endsection