@php
use Carbon\Carbon;
use App\Bond;
$new_count = Bond::where('created_at', '>=', Carbon::now()->subDays(1)->startOfDay())->count();

@endphp
<div class="sidebar" data-color="orange" data-background-color="white" data-image="{{ asset('material') }}/img/sidebar-1.jpg">
  <!--
      Tip 1: You can change the color of the sidebar using: data-color="purple | azure | green | orange | danger"

      Tip 2: you can also add an image using data-image tag
  -->
  <div class="logo">
    <a href="{{ route('home') }}" class="simple-text logo-normal">
      {{ __('Trade Tools') }}
    </a>
  </div>
  <div class="sidebar-wrapper">
    <ul class="nav">
      <li class="nav-item{{ $activePage == 'dashboard' ? ' active' : '' }}">
        <a class="nav-link" href="{{ route('home') }}">
          <i class="material-icons">dashboard</i>
          <p>{{ __('Dashboard') }}</p>
        </a>
      </li>
      <li class="nav-item {{ ($activePage == 'bonds' || $activePage == 'user-management') ? ' active' : '' }}">
        <a class="nav-link" data-toggle="collapse" href="#bonds" aria-expanded="true">
         
          <p>{{ __('Облигаций') }}
            <b class="caret"></b>
          </p>
        </a>
        <div class="collapse show" id="bonds">
          <ul class="nav">
            <li class="nav-item{{ $activePage == 'all_bond' ? ' active' : '' }}">
              <a class="nav-link" href="{{ route('bond.all') }}">
                <span class="sidebar-mini"> All </span>
                <span class="sidebar-normal">{{ __('Все') }} </span>
              </a>
            </li>
            <li class="nav-item{{ $activePage == 'new_bond' ? ' active' : '' }}">
              <a class="nav-link" href="{{ route('bond.new') }}">
                <span class="sidebar-mini"> New7</span>
                <span class="sidebar-normal"> {{ __('Новые') }} ({{ $new_count }})</span>
              </a>
            </li>
          </ul>
        </div>
      </li>

      <li class="nav-item {{ ($activePage == 'stocks' || $activePage == 'user-management') ? ' active' : '' }}">
        <a class="nav-link" data-toggle="collapse" href="#stocks" aria-expanded="true">
          
          <p>{{ __('Акций') }}
            <b class="caret"></b>
          </p>
        </a>
        <div class="collapse" id="stocks">
          <ul class="nav">
            <li class="nav-item{{ $activePage == 'all_stocks' ? ' active' : '' }}">
              <a class="nav-link" href="{{ route('bond.all') }}">
                <span class="sidebar-mini"> All </span>
                <span class="sidebar-normal">{{ __('Все') }} </span>
              </a>
            </li>
            <li class="nav-item{{ $activePage == 'new_bond' ? ' active' : '' }}">
              <a class="nav-link" href="{{ route('bond.new') }}">
                <span class="sidebar-mini"> New7</span>
                <span class="sidebar-normal"> {{ __('Новые') }} ({{ $new_count }})</span>
              </a>
            </li>
          </ul>
        </div>
      </li>

      <li class="nav-item{{ $activePage == 'table' ? ' active' : '' }}">
        <a class="nav-link" href="{{ route('table') }}">
          <i class="material-icons">content_paste</i>
          <p>{{ __('No Work') }}</p>
        </a>
      </li>
    </ul>
  </div>
</div>