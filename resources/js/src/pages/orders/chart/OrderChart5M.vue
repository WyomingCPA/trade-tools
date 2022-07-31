<template>
  <trading-vue
    :chart-config="{ DEFAULT_LEN: 200 }"
    :data="chart"
    :orders="orders"
    :rsi_data="rsi_data"
    :candles="candles"
    :take_profit1="list_take_profit1"
    :take_profit2="list_take_profit2"
    :stop_orders1="list_stop_orders1"
    :stop_orders2="list_stop_orders2"
    :width="width"
    :height="height"
    title-txt="5M"
    ref="tvjs"
    :toolbar="true"
    :overlays="overlays"
    :color-back="colors.back"
    :color-grid="colors.grid"
    :color-text="colors.text"
    :color-title="colors.tvTitle"
  >
  </trading-vue>
</template>
<script>
import { TradingVue, DataCube } from "trading-vue-js";
import Overlays from "tvjs-overlays";
import axios from "axios";
import each from "lodash.foreach";

export default {
  name: "OrderChart",
  props: [
    "candles",
    "rsi_data",
    "title",
    "orders",
    "take_profit1",
    "take_profit2",
    "stop_orders1",
    "stop_orders2",
  ],
  components: { TradingVue },
  computed: {
    colors() {
      return this.night
        ? {
            back: "#121827",
            grid: "#3e3e3e",
            text: "#35a776",
            cross: "#dd64ef",
            candle_dw: "#e54077",
            wick_dw: "#e54077",
          }
        : {
            back: "#121827",
            grid: "#3e3e3e",
            text: "#35a776",
            cross: "#dd64ef",
            candle_dw: "#e54077",
            wick_dw: "#e54077",
          };
    },
  },
  methods: {
    onResize(event) {
      this.width = window.innerWidth - 700;
      this.height = window.innerHeight - 250;
    },
    fetchData() {
      console.log(this.$route.params.id);
      let self = this;
      this.id_order = this.$route.params.id;
      axios
        .get("/api/orders/chart-orders/" + this.$route.params.id)
        .then(function (response) {
          self.candles = response.data.candles;
          console.log(response.data.candles);
        })
        .catch(function (error) {
          console.error(error);
        });
    },
  },
  mounted() {
    window.addEventListener("resize", this.onResize);
    this.onResize();
  },
  created() {},
  beforeDestroy() {
    window.removeEventListener("resize", this.onResize);
  },
  data() {
    let cand = this.candles;
    let order = this.orders;
    let profit1 = this.take_profit1;
    let profit2 = this.take_profit2;
    let stop1 = this.stop_orders1;
    let stop2 = this.stop_orders2;

    let rsi_data = this.rsi_data;
    let title = this.title;

    console.log(order);
    return {
      cand: null,
      width: window.innerWidth,
      height: window.innerHeight,
      chart: new DataCube({
        chart: {
          data: cand,
          indexBased: true,
          tf: "5m",
        },
        onchart: [
          {
            name: "Data sections",
            type: "Trades",
            data: order,
            settings: {
              legend: true,
            },
          },
          {
            name: "TakeProfit",
            type: "Segment",
            data: [],
            settings: {
              p1: profit1,
              p2: profit2,
              lineWidth: 3,
              color: "#34a853",
            },
          },
          {
            name: "StopLoss",
            type: "Segment",
            data: [],
            settings: {
              p1: stop1,
              p2: stop2,
              lineWidth: 3,
              color: "#a83434",
            },
          },
        ],
        offchart: [
          {
            name: "RSI",
            type: "RSI",
            data: rsi_data,
            settings: {
              backColor: "#9b9ba316",
              bandColor: "#666",
            },
          },
          {
            name: "MACD",
            type: "MACD",
            data: [],
            settings: {
              histColors: ["#35a776", "#79e0b3", "#e54150", "#ea969e"],
              timezone: 3,
            },
          },
        ],
      }),
      overlays: [Overlays[("MACD", "RSI")]],
    };
  },
};
</script>
<style>
</style>