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
      <div class="col-md-4">
        <va-card>
          <va-card-title>Trade Parser Log</va-card-title>
          <va-card-content>
            Lorem ipsum dolor sit amet, consectetur adipiscing elit.
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
      all_scripts: [],
      dataUrl: { type: String },
      loading: false,
      id_order: 0,
      serverParams: {},
      items: [],
    }
  },
  methods: {
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
          self.all_scripts = response.data.all_scripts;
          self.loading = false;
          console.log(response.data.count_all_candles);
        })
        .catch(function (error) {
          console.error(error);
        });
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
