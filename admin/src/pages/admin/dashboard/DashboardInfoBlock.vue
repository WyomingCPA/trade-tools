<template>
  <div class="row row-equal">
    <div class="flex xl6 xs12 lg6">
      <div class="col-md-4 grid-margin stretch-card">
        <va-card>
          <va-card-title>Trade Parser Log</va-card-title>
          <va-card-content>
            Lorem ipsum dolor sit amet, consectetur adipiscing elit.
          </va-card-content>
        </va-card>
      </div>
    </div>
    <div class="flex xl6 xs12 lg6">
      <div class="col-md-4 grid-margin stretch-card">
        <va-card>
          <va-card-title>Сервис</va-card-title>
          <va-card-content>
            <va-list>
              <va-list-item v-for="(item, index) in all_scripts" :key="index" class="list__item">
                <va-list-item-section>
                  <va-list-item-label>
                    {{ item.name }}
                  </va-list-item-label>

                  <va-list-item-label caption>
                    Last update {{ item.updated_at }}
                  </va-list-item-label>
                </va-list-item-section>
                <va-list-item-section icon>
                  <va-badge v-if="item.is_run == 1" text="Работает" :color="getStatusBadgeColor(item.is_run)"
                    class="mr-2" />
                  <va-badge v-else text="Не работает" :color="getStatusBadgeColor(item.is_run)" class="mr-2" />
                </va-list-item-section>
              </va-list-item>
            </va-list>
            <va-list>
              <va-list-label> Действия </va-list-label>

              <va-list-item class="list__item">
                <va-list-item-section>
                  <va-icon :size="44" name="bar_chart_4_bars" color="background-tertiary" />
                </va-list-item-section>
                <va-list-item-section>
                  <va-list-item-label>
                    Общее количество свечей
                  </va-list-item-label>
                  <va-list-item-label caption>
                    {{ count_all_candles }}
                  </va-list-item-label>
                </va-list-item-section>
                <va-list-item-section icon>
                  <va-button v-on:click="deleteAllCandles" color="danger" gradient class="mr-6 mb-2">
                    Удалить все
                  </va-button>
                </va-list-item-section>
              </va-list-item>
              <va-list-item class="list__item">
                <va-list-item-section>
                  <va-icon :size="44" name="done" color="background-tertiary" />
                </va-list-item-section>

                <va-list-item-section>
                  <va-list-item-label>
                    Общее количество ордеров
                  </va-list-item-label>
                  <va-list-item-label caption>
                    {{ count_all_orders }}
                  </va-list-item-label>
                </va-list-item-section>

                <va-list-item-section icon>
                  <va-button color="danger" gradient class="mr-6 mb-2">
                    Удалить все
                  </va-button>
                </va-list-item-section>
              </va-list-item>
            </va-list>
          </va-card-content>
        </va-card>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="flex xs12 sm6 md6">
      <va-card>
        <va-card-content>
          <h2 class="va-h2 ma-0">{{ today_open_orders }}</h2>
          <p class="no-wrap">Сделки За Сегодня</p>
        </va-card-content>
      </va-card>
    </div>
    <div class="flex xs12 sm6 md6">
      <va-card>
        <va-card-content>
          <h2 class="va-h2 ma-0">{{ week_open_orders }}</h2>
          <p class="no-wrap">Сделки За Неделю</p>
        </va-card-content>
      </va-card>
    </div>
    <div class="flex xs12 sm6 md6">
      <va-card>
        <va-card-content>
          <h2 class="va-h2 ma-0">{{ month_open_orders }}</h2>
          <p class="no-wrap">Сделки За Месяц</p>
        </va-card-content>
      </va-card>
    </div>
  </div>
</template>

<script lang="ts">
import { ref } from 'vue'
import { useI18n } from 'vue-i18n'
import { VaCarousel, VaModal, VaCard, VaCardContent, VaCardTitle, VaButton, VaImage, useColors } from 'vuestic-ui'
import axios from "axios";

import { defineComponent } from 'vue'
export default defineComponent({
  data() {
    return {
      today_open_orders: { type: Number },
      week_open_orders: { type: Number },
      month_open_orders: { type: Number },
      count_all_candles: { type: Number },
      candles_last_ago: { type: String },
      count_all_orders: { type: Number },
      orders_last_ago: { type: String },
      all_scripts: [
        {
          name: "super_trend_5min",
          updated_at: "21:45:35 8 January 2023",
          is_run: 0,
        },
        {
          name: "super_trend_5min",
          updated_at: "21:45:35 8 January 2023",
          is_run: 1,
        },
        {
          name: "super_trend_5min",
          updated_at: "21:45:35 8 January 2023",
          is_run: 0,
        },
      ],
      dataUrl: { type: String },
      loading: false,
      id_order: 0,
      serverParams: {},
      items: [],
    }
  },
  methods: {
    async deleteAllCandles() {
      let self = this;
      this.loading = true;
      axios.get("/sanctum/csrf-cookie").then((response) => {
        axios
          .post("/api/dashboard/delete-all-candles", {})
          .then((response) => {
            if (response.status) {
              self.loading = false;
              this.fetchData();
            } else {
              console.log("Не работает");
              console.log(response.status);
              self.loading = false;
            }
          })
          .catch(function (error) {
            console.log(response);
            console.error(error);
          });
      });
    },
    async deleteAllOrders() {
      let self = this;
      this.loading = true;
      axios.get("/sanctum/csrf-cookie").then((response) => {
        axios
          .post("/api/dashboard/delete-all-orders", {})
          .then((response) => {
            if (response.status) {
              self.loading = false;
              this.fetchData();
            } else {
              console.log("Не работает");
              console.log(response.status);
              self.loading = false;
            }
          })
          .catch(function (error) {
            console.log(response);
            console.error(error);
          });
      });
    },
    fetchData() {
      let self = this;
      this.loading = true;
      axios
        .get("/api/dashboard/index")
        .then(function (response) {
          self.today_open_orders = response.data.today_open_orders;
          self.week_open_orders = response.data.week_open_orders;
          self.month_open_orders = response.data.month_open_orders;
          self.count_all_candles = response.data.count_all_candles;
          self.candles_last_ago = response.data.candles_last_ago;
          self.count_all_orders = response.data.count_all_orders;
          self.orders_last_ago = response.data.orders_last_ago;
          //self.all_scripts = response.data.all_scripts;
          self.loading = false;
          console.log(response.data.count_all_candles);
        })
        .catch(function (error) {
          console.error(error);
        });
    },
    getStatusBadgeColor(status) {
      console.log(status);
      if (status == "empty") {
        return "primary";
      } else if (status == 1) {
        return "success";
      } else if (status == 0) {
        return "danger";
      } else if (status == "nothing") {
        return "info";
      } else {
        return "";
      }
    },
  },
  created() {
    this.fetchData();
  },
})


</script>

<style lang="scss" scoped>
.row-separated {
  .flex+.flex {
    border-left: 1px solid var(--va-background-primary);
  }
}

.rich-theme-card-text {
  line-height: 1.5;
}

.gallery-carousel {
  width: 80vw;
  max-width: 100%;

  @media all and (max-width: 576px) {
    width: 100%;
  }
}
</style>
