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
          interval: 1,
        },
        axisY: {
          prefix: "$",
          yValueFormatString: "####.####",
          title: "Price in USD",
          stripLines: [
            {
              startValue: 100,
              endValue: 105,
              color: "#d8d8d8"
            }
          ]
        },
        toolTip: {
          content: "Date: {x}<br /><strong>Price:</strong><br />Open: {y[0]}, Close: {y[3]}<br />High: {y[1]}, Low: {y[2]}"
        },
        data: [{
          type: "candlestick",
          xValueFormatString: "DD MMM YYYY",
          dataPoints: []
        }]
      },
      styleOptions: {
        width: "100%",
        height: "360px"
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
          url: "/api/liquidity-pools/chart-1h/" + this.$route.params.id,
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
          this.chart.data[0].axisY.stripLines[0].set("startValue", response.data.pool_min)
          this.chart.data[0].axisY.stripLines[0].set("endValue", response.data.pool_max)
          this.chart.title.set("text", response.data.symbol );

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
  


