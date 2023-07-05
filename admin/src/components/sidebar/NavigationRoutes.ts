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
      name: 'algoritm-trading',
      displayName: 'menu.algoritm-trading',
      meta: {
        icon: 'show_chart',
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
          name: 'stock-all',
          displayName: 'menu.stock-all',
        },
        {
          name: 'stock-favorite',
          displayName: 'menu.stock-favorite',
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
          name: 'stock-all',
          displayName: 'menu.stock-all',
        },
        {
          name: 'stock-favorite',
          displayName: 'menu.stock-favorite',
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
          name: 'stock-all',
          displayName: 'menu.stock-all',
        },
        {
          name: 'stock-favorite',
          displayName: 'menu.stock-favorite',
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
          name: 'stock-all',
          displayName: 'menu.stock-all',
        },
        {
          name: 'stock-favorite',
          displayName: 'menu.stock-favorite',
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
          name: 'stock-all',
          displayName: 'menu.stock-all',
        },
        {
          name: 'stock-favorite',
          displayName: 'menu.stock-favorite',
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
          name: 'stock-all',
          displayName: 'menu.stock-all',
        },
        {
          name: 'stock-favorite',
          displayName: 'menu.stock-favorite',
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
          name: 'stock-all',
          displayName: 'menu.stock-all',
        },
        {
          name: 'stock-favorite',
          displayName: 'menu.stock-favorite',
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
          name: 'stock-all',
          displayName: 'menu.stock-all',
        },
        {
          name: 'stock-favorite',
          displayName: 'menu.stock-favorite',
        },
      ],
    },

  ] as INavigationRoute[],
}
