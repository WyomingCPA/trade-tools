@extends('layouts.app', ['activePage' => 'create_etf', 'titlePage' => __('Добавление цели') ])
@section('content')
<div class="container pt-5">
    <div class="row">
        <form method="post" action="{{ route('etf.aim.create') }}">
            @csrf
            <div class="form-group">
                <label for="name_target">Name Target</label>
                <input type="text" class="form-control" id="name_target" name="name_target" placeholder="Название">
            </div>
            <div class="form-group">
                <label for="desk">Description</label>
                <input type="text" class="form-control" id="descritpion" name="descritpion" placeholder="Описание">
            </div>
            <button type="submit" class="btn btn-primary">Create</button>
            
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
                    @foreach ($etfs as $item)
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
        </form>
    </div>
</div>

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

</script>
@endsection