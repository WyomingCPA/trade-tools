<template>
    <VaCard>
        <VaCardTitle>Title</VaCardTitle>
        <VaCardContent>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 items-end">
                <va-form class="flex flex-col gap-6" ref="formRef">
                    <VaInput v-model="minRange" class="mb-6" label="Min" mask="numeral" placeholder="" />
                    <VaInput v-model="maxRange" class="mb-6" label="Max" mask="numeral" placeholder="" />
                    <VaInput v-model="thisPrice" class="mb-6" label="Price" mask="numeral" placeholder="" />
                    <va-button @click="calculate()"> Рассчитать </va-button>
                </va-form>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 items-end">
                <VaBadge :text="percentMinChange" color="danger" class="mr-2" />
                <VaBadge :text="percentMaxChange" color="success" class="mr-2" />
            </div>
        </VaCardContent>
    </VaCard>
    <CanvasJSChart :options="options" :style="styleOptions" @chart-ref="chartInstance" />
</template>  
<script>

import axios from 'axios'

export default {
    data() {

        return {
            minRange: 0,
            maxRange: 0,
            thisPrice: 107,
            percentMaxChange: 0,
            percentMinChange: 0,

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
                            value: 2600,
                            label: "empty",
                            color: "#FF0000",
                        },
                        {
                            value: 2700,
                            label: "empty",
                            color: "#00FF00",
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
        calculate() {
            this.percentMaxChange = (this.maxRange - this.thisPrice) / this.thisPrice * 100.0;
            this.percentMinChange = (this.thisPrice - this.minRange) / this.thisPrice * 100.0;

            //Test
            this.chart.data[0].axisY.stripLines[0].set("value", this.minRange)
            this.chart.data[0].axisY.stripLines[0].set("label", this.percentMinChange)
            this.chart.data[0].axisY.stripLines[1].set("value", this.maxRange)
            this.chart.data[0].axisY.stripLines[1].set("label", this.percentMaxChange)

            this.chart.render();

            console.log(this.percentMaxChange);
            console.log(this.percentMinChange);
        },
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
                    url: "/api/liquidity-pools/chart-1h/" + 241,
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

                    this.chart.data[0].axisY.stripLines[0].set("value", response.data.pool_min)
                    this.chart.data[0].axisY.stripLines[0].set("label", response.data.pool_min)
                    this.chart.data[0].axisY.stripLines[1].set("value", response.data.pool_max)
                    this.chart.data[0].axisY.stripLines[1].set("label", response.data.pool_max)
                    this.chart.title.set("text", response.data.symbol);

                    this.minRange = response.data.pool_min;
                    this.maxRange = response.data.pool_max;

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