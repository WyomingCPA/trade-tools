<template>
  <trading-vue
    :data="chart"
    :candles="candles"
    :rsi_data="rsi_data"
    :width="width"
    :height="height"
    title-txt=""
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

import each from "lodash.foreach";

export default {
  name: "EtfChart",
  props: ["candles", "rsi_data"],

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
  },
  mounted() {
    window.addEventListener("resize", this.onResize);
    this.onResize();
  },
  beforeDestroy() {
    window.removeEventListener("resize", this.onResize);
  },
  data() {
    let cand = this.candles;
    let rsi_data = this.rsi_data;

    console.log(cand);
    return {
      width: window.innerWidth,
      height: window.innerHeight,
      chart: new DataCube({
        chart: {
          data: cand,
          indexBased: true,
          tf: "1h",
        },
        onchart: [
          {
            name: "EMA, 5",
            type: "EMA",
            data: [],
            settings: {
              color: "#47f80d",
              length: 5,
              lineWidth: 3,
            },
          },
          {
            name: "EMA, 8",
            type: "EMA",
            data: [],
            settings: {
              color: "#FF8C00",
              length: 8,
              lineWidth: 3,
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
            name: "CCI",
            type: "CCI",
            data: [],
            settings: {
              upper: 100,
              lower: -100,
            },
          },
        ],
      }),
      overlays: [Overlays[("EMA", "RSI", "CCI")]],
    };
  },
};
</script>
<style>
</style>