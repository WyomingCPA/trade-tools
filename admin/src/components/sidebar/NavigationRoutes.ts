export interface INavigationRoute {
  name: string
  displayName: string
  meta: { icon: string }
  children?: INavigationRoute[]
}

export default {
  root: {
    name: '/',
    displayName: 'navigationRoutes.home',
  },
  routes: [
    {
      name: 'dashboard',
      displayName: 'menu.dashboard',
      meta: {
        icon: 'vuestic-iconset-dashboard',
      },
    },
    {
      name: 'algo-trading',
      displayName: 'menu.algo-trading',
      meta: {
        icon: 'show_chart',
      },
      disabled: true,
      children: [
        {
          name: 'orders',
          displayName: 'menu.algo-trading-orders',
        },
        {
          name: 'bots',
          displayName: 'menu.algo-tradingy-bots',
        },
        {
          name: 'signals',
          displayName: 'menu.algo-trading-calls',
        },
        {
          name: 'strategy',
          displayName: 'menu.algo-trading-strategy',
        },
      ],
    },
    {
      name: 'test-strategy',
      displayName: 'menu.test-strategy',
      meta: {
        icon: 'warning',
      },
      disabled: true,
      children: [
        {
          name: 'test-strategy-create',
          displayName: 'menu.test-strategy-create',
        },
        {
          name: 'test-strategy-list',
          displayName: 'menu.test-strategy-list',
        },
      ],
    },
    {
      name: 'stocks',
      displayName: 'menu.stocks',
      meta: {
        icon: 'account_balance',
      },
      disabled: true,
      children: [
        {
          name: 'stock-all',
          displayName: 'menu.stock-all',
        },
        {
          name: 'stock-favorite',
          displayName: 'menu.stock-favorite',
        },
        {
          name: 'stock-rus',
          displayName: 'menu.stock-rus',
        },
      ],
    },
    {
      name: 'etf',
      displayName: 'menu.etf',
      meta: {
        icon: 'work',
      },
      disabled: true,
      children: [
        {
          name: 'etf-all',
          displayName: 'menu.etf-all',
        },
        {
          name: 'etf-favorite',
          displayName: 'menu.etf-favorite',
        },
      ],
    },
    {
      name: 'cryptocurrency',
      displayName: 'menu.cryptocurrency',
      meta: {
        icon: 'work',
      },
      disabled: true,
      children: [
        {
          name: 'cryptocurrency-all',
          displayName: 'menu.cryptocurrency-all',
        },
        {
          name: 'cryptocurrency-favorite',
          displayName: 'menu.cryptocurrency-favorite',
        },
        {
          name: 'liquidity-pools',
          displayName: 'menu.liquidity-pools',
        },
        {
          name: 'calculators-pools',
          displayName: 'menu.calculators-pools',
        },
      ],
    },
    {
      name: 'bonds',
      displayName: 'menu.bonds',
      meta: {
        icon: 'percent',
      },
      disabled: true,
      children: [
        {
          name: 'bonds-all',
          displayName: 'menu.bonds-all',
        },
        {
          name: 'bonds-favorite',
          displayName: 'menu.bonds-favorite',
        },
        {
          name: 'bonds-new',
          displayName: 'menu.bonds-new',
        },
        {
          name: 'bonds-hide',
          displayName: 'menu.bonds-hide',
        },
      ],
    },
    {
      name: 'futures',
      displayName: 'menu.futures',
      meta: {
        icon: 'av_timer',
      },
      disabled: true,
      children: [
        {
          name: 'futures-all',
          displayName: 'menu.futures-all',
        },
        {
          name: 'futures-favorite',
          displayName: 'menu.futures-favorite',
        },
      ],
    },
    {
      name: 'calculators',
      displayName: 'menu.calculators',
      meta: {
        icon: 'developer_board',
      },
      disabled: true,
      children: [
        {
          name: 'calculators-stock-average',
          displayName: 'menu.calculators-stock-average',
        },
        {
          name: 'calculators-stock-aim-average',
          displayName: 'menu.calculators-stock-aim-average',
        },
        {
          name: 'calculators-count-lots',
          displayName: 'menu.calculators-count-lots',
        },
      ],
    },
    {
      name: 'trade-ideas',
      displayName: 'menu.trade-ideas',
      meta: {
        icon: 'perm_identity',
      },
      disabled: true,
      children: [
        {
          name: 'trade-ideas-list',
          displayName: 'menu.trade-ideas-list',
        },
        {
          name: 'trade-ideas-add',
          displayName: 'menu.trade-ideas-add',
        },
      ],
    },
    {
      name: 'settings',
      displayName: 'menu.settings',
      meta: {
        icon: 'settings',
      },
      disabled: true,
      children: [
        {
          name: 'settings-external-services',
          displayName: 'menu.settings-external-services',
        },
        {
          name: 'settings-global-log',
          displayName: 'menu.settings-global-log',
        },
      ],
    },
    {
      name: 'my-finance',
      displayName: 'menu.my-finance',
      meta: {
        icon: 'vuestic-iconset-statistics',
      },
      disabled: true,
      children: [
        {
          name: 'my-finance-all',
          displayName: 'menu.my-finance-all',
        },
        {
          name: 'my-finance-add',
          displayName: 'menu.my-finance-add',
        },
      ],
    },
    {
      name: 'bots',
      displayName: 'menu.bots',
      meta: {
        icon: 'vuestic-iconset-statistics',
      },
      disabled: true,
      children: [
        {
          name: 'bots-index',
          displayName: 'menu.bots-index',
        },
      ],
    },

  ] as INavigationRoute[],
}
