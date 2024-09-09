<template>
  <CanvasJSChart :options="options" :style="styleOptions" @chart-ref="chartInstance" />

</template>
<script>

import axios from 'axios'

export default {
  data() {

    return {
      chart: null,
      options: {
        animationEnabled: true,
        theme: "dark1", // "light1", "light2", "dark1", "dark2"
        exportEnabled: true,
        title: {
          text: ''
        },
        subtitles: [{
          text: "Weekly Average"
        }],
        axisX: {
          interval: 90,
          intervalType: "minute",
          labelFontSize: 10,
        },
        axisY: {
          prefix: "Р",
          yValueFormatString: "####.####",
          title: "Price in RUB",
          stripLines: [

          ]
        },
        toolTip: {
          content: "Date: {x}<br /><strong>Price:</strong><br />Open: {y[0]}, Close: {y[3]}<br />High: {y[1]}, Low: {y[2]}"
        },
        data: [{
          type: "candlestick",
          risingColor: "green",
          fallingColor: "red",
          xValueFormatString: "DD MMM YYYY HH mm",
          dataPoints: []
        }]
      },
      styleOptions: {
        width: "100%",
        height: "560px"
      }
    }
  },
  methods: {
    chartInstance(chart) {
      this.chart = chart;
      this.fetchRows();
    },
    fetchRows() {
      let self = this;
      this.loading = true;
      let rows = [];

      axios
        .request({
          method: "get",
          url: "/api/orders/chart-orders-15/" + this.$route.params.id,
          params: this.serverParams,
          paramsSerializer: (params) => {
            return qs.stringify(params);
          },
        })
        .then((response) => {
          //self.dataPoints = response.data.candles;
          response.data.candles.forEach(data => {
            //console.log(data['time']);
            this.options.data[0].dataPoints.push({ x: new Date(data["time"]), y: [data["open"], data["high"], data["low"], data["close"]] });
          });
          response.data.candles.forEach(data => {
            //console.log(data['time']);
            this.options.data[0].dataPoints.push({ x: new Date(data["time"]), y: [data["open"], data["high"], data["low"], data["close"]] });
          });

          response.data.list_stop_orders.forEach((data, index) => {
            this.chart.data[0].axisY.addTo("stripLines", { value: data[1], label: data[0], color: "#FF0000" });
          });
          response.data.list_take_profit.forEach((data, index) => {
            this.chart.data[0].axisY.addTo("stripLines", { value: data[1], label: data[0], color: "#00FF00" });
          });

          this.chart.title.set("text", response.data.symbol);

          //response.data.pool_min
          //response.data.pool_max
          this.chart.render();
          self.loading = false;
        })
        .catch((error) => {
          console.log(error);
          self.loading = false;
        });
    },
  },
  created() {
    //this.fetchRows();
  },


}
</script>