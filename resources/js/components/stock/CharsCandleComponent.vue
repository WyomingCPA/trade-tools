<template>
  <trading-vue
    :data="chart"
    :candles="candles"
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
import each from "lodash.foreach";

export default {
  name: "MainChart",
  props: ["width", "height", "candles"],
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
            back: "#fff",
            grid: "#eee",
            text: "#333",
            candle_dw: "black",
            wick_dw: "black",
          };
    },
  },
  mounted() {
    // Shift the chart to the left
    let last = 1556031600000;
    let shift = 3600 * 1000 * 15; // 15h
    this.$refs.tvjs.goto(last + shift);
  },

  data() {
    let cand = this.candles;

    console.log(cand);
    return {
      chart: new DataCube({
        ohlcv: 
            cand
        ,
        onchart: [],
        offchart: [],
      }),
      overlays: [],
    };
  },
};
</script>
<style>
</style>