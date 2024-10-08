@php
use Carbon\Carbon;
use App\Bond;
use App\Stock;

$new_count_bond = Bond::where('created_at', '>=', Carbon::now()->subDays(7)->startOfDay())->count();
$hide_count_bond = Auth::user()->trashBond->count();
$favorite_count_bond = Auth::user()->favoritesBond->count();
$all_count_bond = Bond::all()->count();

$new_count_stock = Stock::where('created_at', '>=', Carbon::now()->subDays(7)->startOfDay())->count();

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
          <p>{{ __('Личный кабинет') }}</p>
        </a>
      </li>
      <li class="nav-item {{ ($activePage == 'bonds' || $activePage == 'user-management') ? ' active' : '' }}">
        <a class="nav-link" data-toggle="collapse" href="#bonds" aria-expanded="true">
          <p>{{ __('Облигаций') }}
            <b class="caret"></b>
          </p>
        </a>
        <div class="collapse" id="bonds">
          <ul class="nav">
            <li class="nav-item{{ $activePage == 'all_bond' ? ' active' : '' }}">
              <a class="nav-link" href="{{ route('bond.all') }}">
                <span class="sidebar-normal">{{ __('Все') }} ({{ $all_count_bond }}) </span>
              </a>
            </li>
            <li class="nav-item{{ $activePage == 'new_bond' ? ' active' : '' }}">
              <a class="nav-link" href="{{ route('bond.new') }}">
                <span class="sidebar-normal"> {{ __('Новые') }} ({{ $new_count_bond }})</span>
              </a>
            </li>
            <li class="nav-item{{ $activePage == 'favorites_bond' ? ' active' : '' }}">
              <a class="nav-link" href="{{ route('bond.favorites') }}">
                <span class="sidebar-normal">{{ __('Избранные') }} ({{ $favorite_count_bond }})</span>
              </a>
            </li>
            <li class="nav-item{{ $activePage == 'trashes_bond' ? ' active' : '' }}">
              <a class="nav-link" href="{{ route('bond.trash') }}">
                <span class="sidebar-normal">{{ __('Скрытые') }} ({{ $hide_count_bond }})</span>
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
              <a class="nav-link" href="{{ route('stock.all') }}">
                <span class="sidebar-normal">{{ __('Все') }} </span>
              </a>
            </li>
            <li class="nav-item{{ $activePage == 'rub_stocks' ? ' active' : '' }}">
              <a class="nav-link" href="{{ route('stock.rub') }}">
                <span class="sidebar-normal">{{ __('Рублевые акций') }} </span>
              </a>
            </li>
            <li class="nav-item{{ $activePage == 'usd_stocks' ? ' active' : '' }}">
              <a class="nav-link" href="{{ route('stock.usd') }}">
                <span class="sidebar-normal">{{ __('Долларовые акций') }} </span>
              </a>
            </li>
            <li class="nav-item{{ $activePage == 'new_stock' ? ' active' : '' }}">
              <a class="nav-link" href="{{ route('stock.new') }}">
                <span class="sidebar-normal"> {{ __('Новые') }} ({{ $new_count_stock }})</span>
              </a>
            </li>
            <li class="nav-item{{ $activePage == 'favorites_stock' ? ' active' : '' }}">
              <a class="nav-link" href="{{ route('stock.favorites') }}">
                <span class="sidebar-normal">{{ __('Избранные') }} </span>
              </a>
            </li>
            <li class="nav-item{{ $activePage == 'dividends_stock' ? ' active' : '' }}">
              <a class="nav-link" href="{{ route('stock.dividends') }}">
                <span class="sidebar-normal">{{ __('Акций с дивидендами') }} </span>
              </a>
            </li>
            <li class="nav-item{{ $activePage == 'profit_stock' ? ' active' : '' }}">
              <a class="nav-link" href="{{ route('stock.profit') }}">
                <span class="sidebar-normal">{{ __('Открытые сделки') }} </span>
              </a>
            </li>
          </ul>
        </div>
      </li>
      <li class="nav-item {{ ($activePage == 'etf' || $activePage == 'user-management') ? ' active' : '' }}">
        <a class="nav-link" data-toggle="collapse" href="#etf" aria-expanded="true">
          <p>{{ __('Фонды') }}
            <b class="caret"></b>
          </p>
        </a>
        <div class="collapse" id="etf">
          <ul class="nav">
            <li class="nav-item{{ $activePage == 'all_etf' ? ' active' : '' }}">
              <a class="nav-link" href="{{ route('etf.all') }}">
                <span class="sidebar-normal">{{ __('Все') }} </span>
              </a>
            </li>
            <li class="nav-item{{ $activePage == 'favorites_etf' ? ' active' : '' }}">
              <a class="nav-link" href="{{ route('etf.favorites') }}">
                <span class="sidebar-normal">{{ __('Избранные') }} </span>
              </a>
            </li>
          </ul>
        </div>
      </li>
      <li class="nav-item {{ ($activePage == 'journal' || $activePage == 'journal') ? ' active' : '' }}">
        <a class="nav-link" data-toggle="collapse" href="#journal" aria-expanded="true">
          <p>{{ __('Журнал покупок') }}
            <b class="caret"></b>
          </p>
        </a>
        <div class="collapse" id="journal">
          <ul class="nav">
            <li class="nav-item{{ $activePage == 'journal' ? ' active' : '' }}">
              <a class="nav-link" href="{{ route('journal') }}">
                <span class="sidebar-normal">{{ __('Журнал') }} </span>
              </a>
            </li>
          </ul>
        </div>
      </li>
      <li class="nav-item ">
        <a class="nav-link" href="{{ route('portfolio.index') }}">
          <p>Мой портфель</p>
        </a>
      </li>
      <li class="nav-item ">
        <a class="nav-link" href="{{ route('documentation.index') }}">
          <p>Справочник</p>
        </a>
      </li>
      <li class="nav-item {{ ($activePage == 'settings' || $activePage == 'settings') ? ' active' : '' }}">
        <a class="nav-link" data-toggle="collapse" href="#settings" aria-expanded="true">
          <p>{{ __('Settings') }}
            <b class="caret"></b>
          </p>
        </a>
        <div class="collapse" id="settings">
          <ul class="nav">
            <li class="nav-item{{ $activePage == 'all_stocks' ? ' active' : '' }}">
              <a class="nav-link" href="{{ route('stock.all') }}">
                <span class="sidebar-normal">{{ __('') }} </span>
              </a>
            </li>
          </ul>
        </div>
      </li>
      <li class="nav-item ">
      <a class="dropdown-item" href="#" onclick="event.preventDefault();location.reload();">Restart Token</a>
      </li>
    </ul>
  </div>
</div>