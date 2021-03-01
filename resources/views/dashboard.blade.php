@extends('layouts.app', ['activePage' => 'dashboard', 'titlePage' => __('Личный кабинет')])

@section('content')
<div class="content">
  <div class="container-fluid">
    <div class="row">

      @foreach ($port->getAllCurrencies() as $item)
      <div class="col-lg-2 col-md-4 col-sm-4">
        <div class="card card-stats">
          <div class="card-header card-header-success card-header-icon">
            <p class="card-category">{{ $item->getCurrency() }}</p>
            <h3 class="card-title">{{ $item->getBalance() }}</h3>
          </div>
          <div class="card-footer">
            <div class="stats">
              <i class="material-icons">date_range</i> {{ $time }}
            </div>
          </div>
        </div>
      </div>
      @endforeach
      <div class="col-lg-4 col-md-5 col-sm-5">
        <div class="card">
          <div class="card-body">
            <table class="table">
              <thead>
                <tr>
                  <th>Название</th>
                  <th>Тип</th>
                  <th>Лоты</th>
                  <th>Ожидаемая доходность</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($port->getAllinstruments() as $item)
                <tr>
                  <td>{{ $item->getName() }}</td>
                  <td>{{ $item->getInstrumentType() }}</td>
                  <td>{{ $item->getLots() }}</td>
                  <td>{{ $item->getExpectedYieldValue() }}</td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <div class="row pt-5">
      <div class="col-md-12">
        <div id="app">
          <dashboard-operations :operations="{{ $operations }}"></dashboard-operations>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
@section('scripts')
<script src="{{ asset('/js/app.js') }}"></script>

@endsection
@push('js')
<script>
  $(document).ready(function() {
    // Javascript method's body can be found in assets/js/demos.js
    md.initDashboardPageCharts();
  });
</script>
@endpush