<template>
    <CanvasJSChart :options="options" :style="styleOptions" @chart-ref="chartInstance" />
</template>
<script>

import axios from 'axios'

export default {
    props: ['candles', 'pool_min', 'pool_max', 'symbol'],
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

            this.candles.forEach(data => {
                //console.log(data['time']);
                this.options.data[0].dataPoints.push({ x: new Date(data["time"]), y: [data["open"], data["high"], data["low"], data["close"]] });
            });
            this.chart.data[0].axisY.addTo("stripLines", { value: this.pool_min, label: 'min', color: "#FF0000" });
            this.chart.data[0].axisY.addTo("stripLines", { value: this.pool_max, label: 'max', color: "#00FF00" });
            this.chart.title.set("text", this.symbol);
            //this.chart.data[0].axisY.stripLines[0].set("value", this.pool_min);
            //this.chart.data[0].axisY.addTo("stripLines", { value: data[1], label: data[0], color: "#FF0000" });

            //this.pool_min.forEach((data, index) => {
            //    this.chart.data[0].axisY.addTo("stripLines", { value: data[1], label: data[0], color: "#FF0000" });
            //});
            //this.pool_max.forEach((data, index) => {
            //    this.chart.data[0].axisY.addTo("stripLines", { value: data[1], label: data[0], color: "#00FF00" });
            //});


        },
    },
    created() {
        //this.fetchRows();
    },


}
</script>