@extends('layouts.app', ['activePage' => 'all_stock', 'titlePage' => __('Все акций') ])

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
                            <form class="form" method="post" action="{{ route('stock.favorites.post') }}">
                                @csrf
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th><input name="select_all" value="1" id="example-select-all" class="form-check-input" type="checkbox"></th>
                                            <th>Название</th>
                                            <th>figi</th>
                                            <th>ticker</th>
                                            <th>isin</th>
                                            <th>Валюта</th>
                                            
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($stocks as $item)
                                        <tr>
                                            <td><input class="form-check-input" type="checkbox" name="selection[]" value="{{ $item->id}}"></td>
                                            <td>
                                                <a target="_blank" href='https://www.tinkoff.ru/invest/bonds/{{ $item->ticker }}/'>{{ $item->name }}</a>
                                            </td>
                                            <td>{{ $item->figi }}</td>
                                            <td class="table-cell">{{ $item->ticker }}</td>
                                            <td>{{ $item->isin }}</td>
                                            <td>{{ $item->currency }}</td>
                                            
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                                <div class="col-md-12">
                                    {{ $stocks->links('pagination.default') }}
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <button name='favorites' value='1' type="submit" class="btn btn-primary btn-link btn-lg">В избранное</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>

</script>
@endsection
@section('scripts')
<script>
    $(document).ready(function() {
        // Handle click on "Select all" control
        $('#example-select-all').on('click', function(e) {
            var table = $(e.target).closest('table');
            $('td input:checkbox', table).prop('checked', this.checked);
        });
    });
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