<template>
  <section class="app-sidebar">
    <nav class="sidebar sidebar-offcanvas" id="sidebar">
      <div
        class="
          sidebar-brand-wrapper
          d-none d-lg-flex
          align-items-center
          justify-content-center
          fixed-top
          text-center
        "
      >
        <router-link class="sidebar-brand brand-logo" to="/">
          <span class="navbar-brand mb-0 h1">Trade-Tools</span>
        </router-link>
        <router-link class="sidebar-brand brand-logo-mini" to="/">
          <img src="@/assets/images/logo-mini.svg" alt="logo" />
        </router-link>
      </div>
      <ul class="nav">
        <li class="nav-item account-dropdown">
          <a class="nav-link" v-b-toggle="'account-dropdown'">
            <img
              class="img-xs rounded-circle"
              src="@/assets/images/faces-clipart/pic-1.png"
              alt=""
            />
            <p class="mb-0 text-light">Trader</p>
            <i class="menu-arrow"></i>
          </a>
          <b-collapse accordion="sidebar-accordion" id="account-dropdown">
            <ul class="nav flex-column sub-menu pl-0">
              <li class="nav-item">
                <a class="nav-link pl-5" href="#">
                  <span class="menu-icon">
                    <i class="mdi mdi-account"></i>
                  </span>
                  <span class="menu-title">Profile</span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link pl-5" href="#">
                  <span class="menu-icon">
                    <i class="mdi mdi-email"></i>
                  </span>
                  <span class="menu-title">Inbox</span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link pl-5" href="#">
                  <span class="menu-icon">
                    <i class="mdi mdi-wrench"></i>
                  </span>
                  <span class="menu-title">Settings</span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link pl-5" href="#" @click.prevent="logout">
                  <span class="menu-icon">
                    <i class="mdi mdi-power"></i>
                  </span>
                  <span class="menu-title">Logout</span>
                </a>
              </li>
            </ul>
          </b-collapse>
        </li>
        <li class="nav-item nav-category">
          <span class="nav-link">Navigation</span>
        </li>
        <li class="nav-item menu-items" v-on:click="collapseAll">
          <router-link class="nav-link" to="/">
            <span class="menu-icon">
              <i class="mdi mdi-speedometer"></i>
            </span>
            <span class="menu-title">Dashboard</span>
          </router-link>
        </li>
        <li class="nav-item menu-items">
          <span
            class="nav-link"
            v-b-toggle="'algo-trading'"
            :class="{ active: subIsActive('/orders') }"
          >
            <span class="menu-icon">
              <i class="mdi mdi-chart-line-variant"></i>
            </span>
            <span class="menu-title">Algorithmic trading</span>
            <i class="menu-arrow"></i>
          </span>
          <b-collapse accordion="sidebar-accordion" id="algo-trading">
            <ul class="nav flex-column sub-menu">
              <li class="nav-item">
                <router-link class="nav-link" to="/orders"
                  >Список Ордеров</router-link
                >
              </li>
              <li class="nav-item">
                <router-link class="nav-link" to="/test-strategy/create/"
                  >Список ботов</router-link
                >
              </li>
              <li class="nav-item">
                <router-link class="nav-link" to="/test-strategy/create/"
                  >Список сигналов</router-link
                >
              </li>
              <li class="nav-item">
                <router-link class="nav-link" to="/test-strategy/create/"
                  >Список стратегий</router-link
                >
              </li>
            </ul>
          </b-collapse>
        </li>
        <li class="nav-item menu-items">
          <span
            class="nav-link"
            v-b-toggle="'test-strategy'"
            :class="{ active: subIsActive('/test-strategy') }"
          >
            <span class="menu-icon">
              <i class="mdi mdi-alert"></i>
            </span>
            <span class="menu-title">Test Strategy</span>
            <i class="menu-arrow"></i>
          </span>
          <b-collapse accordion="sidebar-accordion" id="test-strategy">
            <ul class="nav flex-column sub-menu">
              <li class="nav-item">
                <router-link class="nav-link" to="/test-strategy/"
                  >Список тестов</router-link
                >
              </li>
              <li class="nav-item">
                <router-link class="nav-link" to="/test-strategy/create/"
                  >Создать тест</router-link
                >
              </li>
            </ul>
          </b-collapse>
        </li>
        <li class="nav-item menu-items">
          <span
            class="nav-link"
            v-b-toggle="'stock'"
            :class="{ active: subIsActive('/stock') }"
          >
            <span class="menu-icon">
              <i class="mdi mdi-bank"></i>
            </span>
            <span class="menu-title">Акций</span>
            <i class="menu-arrow"></i>
          </span>
          <b-collapse accordion="sidebar-accordion" id="stock">
            <ul class="nav flex-column sub-menu">
              <li class="nav-item">
                <router-link class="nav-link" to="/stock/all/"
                  >Все акций</router-link
                >
              </li>
              <li class="nav-item">
                <router-link class="nav-link" to="/stock/favorite/"
                  >Избранные акций</router-link
                >
              </li>
              <li class="nav-item">
                <router-link class="nav-link" to="/stock/dividends/"
                  >Акций с дивидендами
                </router-link>
              </li>
              <li class="nav-item">
                <router-link class="nav-link" to="/stock/usd/"
                  >Акций USD
                </router-link>
              </li>
              <li class="nav-item">
                <router-link class="nav-link" to="/stock/rub/"
                  >Акций RUB
                </router-link>
              </li>
            </ul>
          </b-collapse>
        </li>
        <li class="nav-item menu-items">
          <span
            class="nav-link"
            v-b-toggle="'etf'"
            :class="{ active: subIsActive('/etf') }"
          >
            <span class="menu-icon">
              <i class="mdi mdi-database"></i>
            </span>
            <span class="menu-title">Фонды</span>
            <i class="menu-arrow"></i>
          </span>
          <b-collapse accordion="sidebar-accordion" id="etf">
            <ul class="nav flex-column sub-menu">
              <li class="nav-item">
                <router-link class="nav-link" to="/etf/all/"
                  >Все фонды</router-link
                >
              </li>
              <li class="nav-item">
                <router-link class="nav-link" to="/etf/favorite/"
                  >Избранные фонды</router-link
                >
              </li>
            </ul>
          </b-collapse>
        </li>
        <li class="nav-item menu-items">
          <span
            class="nav-link"
            v-b-toggle="'bond'"
            :class="{ active: subIsActive('/bond') }"
          >
            <span class="menu-icon">
              <i class="mdi mdi-cash-multiple"></i>
            </span>
            <span class="menu-title">Облигаций</span>
            <i class="menu-arrow"></i>
          </span>
          <b-collapse accordion="sidebar-accordion" id="bond">
            <ul class="nav flex-column sub-menu">
              <li class="nav-item">
                <router-link class="nav-link" to="/bond/all/">Все</router-link>
              </li>
              <li class="nav-item">
                <router-link class="nav-link" to="/bond/favorite/"
                  >Избранные</router-link
                >
              </li>
              <li class="nav-item">
                <router-link class="nav-link" to="/bond/new/"
                  >Новые</router-link
                >
              </li>
              <li class="nav-item">
                <router-link class="nav-link" to="/bond/hide/"
                  >Скрытые</router-link
                >
              </li>
            </ul>
          </b-collapse>
        </li>
        <li class="nav-item menu-items">
          <span
            class="nav-link"
            v-b-toggle="'futures'"
            :class="{ active: subIsActive('/futures') }"
          >
            <span class="menu-icon">
              <i class="mdi mdi-av-timer"></i>
            </span>
            <span class="menu-title">Фьючерсы</span>
            <i class="menu-arrow"></i>
          </span>
          <b-collapse accordion="sidebar-accordion" id="futures">
            <ul class="nav flex-column sub-menu">
              <li class="nav-item">
                <router-link class="nav-link" to="/futures/index/"
                  >Все</router-link
                >
              </li>
              <li class="nav-item">
                <router-link class="nav-link" to="/futures/favorite/"
                  >Избранные</router-link
                >
              </li>
            </ul>
          </b-collapse>
        </li>
        <li class="nav-item menu-items">
          <span
            class="nav-link"
            v-b-toggle="'calculator'"
            :class="{ active: subIsActive('/calculator') }"
          >
            <span class="menu-icon">
              <i class="mdi mdi-calculator"></i>
            </span>
            <span class="menu-title">Калькуляторы</span>
            <i class="menu-arrow"></i>
          </span>
          <b-collapse accordion="sidebar-accordion" id="calculator">
            <ul class="nav flex-column sub-menu">
              <li class="nav-item">
                <router-link
                  class="nav-link"
                  to="/calculator/stock-average-calculator/"
                  >Stock Average Calculator</router-link
                >
              </li>
              <li class="nav-item">
                <router-link
                  class="nav-link"
                  to="/calculator/stock-aim-average-calculator/"
                  >Stock Aim Average Calculator</router-link
                >
              </li>
              <li class="nav-item">
                <router-link
                  class="nav-link"
                  to="/calculator/stock-lots-calculation/0"
                  >Узнать лотность инструмента</router-link
                >
              </li>
            </ul>
          </b-collapse>
        </li>
        <li class="nav-item menu-items">
          <span
            class="nav-link"
            v-b-toggle="'trade-ideas'"
            :class="{ active: subIsActive('/trade-ideas') }"
          >
            <span class="menu-icon">
              <i class="mdi mdi-credit-card"></i>
            </span>
            <span class="menu-title">Торговые идей</span>
            <i class="menu-arrow"></i>
          </span>
          <b-collapse accordion="sidebar-accordion" id="trade-ideas">
            <ul class="nav flex-column sub-menu">
              <li class="nav-item">
                <router-link class="nav-link" to="/trade-ideas/index/"
                  >Список</router-link
                >
              </li>
              <li class="nav-item">
                <router-link class="nav-link" to="/trade-ideas/create/"
                  >Добавить</router-link
                >
              </li>
            </ul>
          </b-collapse>
        </li>
        <li class="nav-item menu-items">
          <span
            class="nav-link"
            v-b-toggle="'settings'"
            :class="{ active: subIsActive('/settings') }"
          >
            <span class="menu-icon">
              <i class="mdi mdi-settings"></i>
            </span>
            <span class="menu-title">Settings</span>
            <i class="menu-arrow"></i>
          </span>
          <b-collapse accordion="sidebar-accordion" id="settings">
            <ul class="nav flex-column sub-menu">
              <li class="nav-item">
                <router-link class="nav-link" to="/settings/service/"
                  >Внешние сервисы</router-link
                >
              </li>
            </ul>
          </b-collapse>
        </li>
        <li class="nav-item menu-items">
          <span
            class="nav-link"
            v-b-toggle="'finance'"
            :class="{ active: subIsActive('/finance') }"
          >
            <span class="menu-icon">
              <i class="mdi mdi-credit-card"></i>
            </span>
            <span class="menu-title">Мой Финансы</span>
            <i class="menu-arrow"></i>
          </span>
          <b-collapse accordion="sidebar-accordion" id="finance">
            <ul class="nav flex-column sub-menu">
              <li class="nav-item">
                <router-link class="nav-link" to="/finance/all/"
                  >Все</router-link
                >
              </li>
              <li class="nav-item">
                <router-link class="nav-link" to="/finance/create/"
                  >Добавить</router-link
                >
              </li>
            </ul>
          </b-collapse>
        </li>
      </ul>
    </nav>
  </section>
</template>

<script>
import axios from "axios";
export default {
  name: "sidebar",
  data() {
    return {
      collapses: [{ show: false }, { show: false }, { show: false }],
    };
  },

  mounted() {
    const body = document.querySelector("body");
    // add class 'hover-open' to sidebar navitem while hover in sidebar-icon-only menu
    document.querySelectorAll(".sidebar .nav-item").forEach(function (el) {
      el.addEventListener("mouseover", function () {
        if (body.classList.contains("sidebar-icon-only")) {
          el.classList.add("hover-open");
        }
      });
      el.addEventListener("mouseout", function () {
        if (body.classList.contains("sidebar-icon-only")) {
          el.classList.remove("hover-open");
        }
      });
    });
  },
  methods: {
    collapseAll() {
      var exp_element = document.getElementsByClassName("show");
      if (exp_element.length > 0) {
        var elm_id = exp_element[0].id;
        this.$root.$emit("bv::toggle::collapse", elm_id);
      }
    },
    subIsActive(input) {
      const paths = Array.isArray(input) ? input : [input];
      return paths.some((path) => {
        return this.$route.path.indexOf(path) === 0; // current path starts with this path string
      });
    },
    logout(evt) {
      axios
        .get("api/logout")
        .then((response) => {
          localStorage.removeItem("auth_token");

          // remove any other authenticated user data you put in local storage

          // Assuming that you set this earlier for subsequent Ajax request at some point like so:
          // axios.defaults.headers.common['Authorization'] = 'Bearer ' + auth_token ;
          delete axios.defaults.headers.common["Authorization"];

          // If using 'vue-router' redirect to login page
          this.$router.go("/login");
        })
        .catch((error) => {
          // If the api request failed then you still might want to remove
          // the same data from localStorage anyways
          // perhaps this code should go in a finally method instead of then and catch
          // methods to avoid duplication.
          localStorage.removeItem("auth_token");
          delete axios.defaults.headers.common["Authorization"];
          this.$router.go("/login");
        });
    },
  },
  watch: {
    $route() {
      document.querySelector("#sidebar").classList.toggle("active");
    },
  },
};
</script>