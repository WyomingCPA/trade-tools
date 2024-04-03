<template>
    <va-card>
        <va-card-content>
            <VaSlider :min="minSlide" :max="maxSlide" v-model="value" class="mb-6" range track-label-visible
                :track-label="processTrackLabel" @change="sliderChange()" />
        </va-card-content>
        <va-card-content>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 items-end">
                <va-form class="flex flex-col gap-6" ref="formRef">
                    <VaInput @change="inputChange()" v-model="minRange" class="mb-6" label="Min Range"
                        placeholder="min" />
                    <VaInput v-model="maxRange" class="mb-6" label="Max Range" placeholder="max" />
                    <VaInput v-model="currentPrice" class="mb-6" label="Balance" placeholder="Current Price" />
                    <va-button @click="update()"> Обновить </va-button>
                </va-form>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 items-end">
                <VaBadge :text="percentMinChange" color="danger" class="mr-2" />
                <VaBadge :text="percentMaxChange" color="success" class="mr-2" />
            </div>
        </va-card-content>
    </va-card>
    <CanvasJSChart :options="options" :style="styleOptions" @chart-ref="chartInstance" />
</template>
<script>
import axios from 'axios'

export default {
    name: 'group-create',
    components: {

    },
    data() {
        return {
            value: [10, 25],
            loading: false,
            minRange: 0,
            maxRange: 0,
            currentPrice: 0,
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
                        },
                        {
                            value: 0,
                            label: "empty",
                            color: "#0800ff",
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
    computed: {
        minSlide: function () {
            return parseInt(this.minRange) - 500;
        },
        maxSlide: function () {         
            let value = parseInt(this.maxRange) + 500
            this.calculateTest();
            return value;
        }
    },
    methods: {
        inputChange() {
            this.value = [this.minRange, this.maxRange];
        },
        sliderChange() {
            this.minRange = this.value[0];
            this.maxRange = this.value[1];
            console.log(this.value);
        },
        processTrackLabel(value, order) {
            //var newArray = [this.minRange, this.maxRange];
            //console.log(newArray);
            //value = newArray;
            return order === 0 ? `min ${value}$` : `max ${value}$`;
        },
        calculateTest()
        {
            this.percentMaxChange = (this.maxRange - this.currentPrice) / this.currentPrice * 100.0;
            this.percentMinChange = (this.currentPrice - this.minRange) / this.currentPrice * 100.0;
        },
        calculate(self) {
            self.percentMaxChange = (self.maxRange - self.currentPrice) / self.currentPrice * 100.0;
            self.percentMinChange = (self.currentPrice - self.minRange) / self.currentPrice * 100.0;

            //Test
            this.chart.data[0].axisY.stripLines[0].set("value", self.minRange)
            this.chart.data[0].axisY.stripLines[0].set("label", self.percentMinChange)
            this.chart.data[0].axisY.stripLines[1].set("value", self.maxRange)
            this.chart.data[0].axisY.stripLines[1].set("label", self.percentMaxChange)
            this.chart.data[0].axisY.stripLines[2].set("value", self.currentPrice)
            this.chart.data[0].axisY.stripLines[2].set("label", self.currentPrice)

            this.chart.render();

            console.log(self.percentMaxChange);
            console.log(self.percentMinChange);
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
                    url: "/api/liquidity-pools/chart-1h/" + this.$route.params.id,
                    params: this.serverParams,
                    paramsSerializer: (params) => {
                        return qs.stringify(params);
                    },
                })
                .then((response) => {
                    response.data.candles.forEach(data => {
                        //console.log(data['time']);
                        this.options.data[0].dataPoints.push({ x: new Date(data["time"]), y: [data["open"], data["high"], data["low"], data["close"]] });
                    });

                    this.chart.data[0].axisY.stripLines[0].set("value", response.data.pool_min)
                    this.chart.data[0].axisY.stripLines[0].set("label", response.data.pool_min)
                    this.chart.data[0].axisY.stripLines[1].set("value", response.data.pool_max)
                    this.chart.data[0].axisY.stripLines[1].set("label", response.data.pool_max)
                    this.chart.data[0].axisY.stripLines[2].set("value", self.currentPrice)
                    this.chart.data[0].axisY.stripLines[2].set("label", self.currentPrice)
                    this.chart.title.set("text", response.data.symbol);

                    this.minRange = response.data.pool_min;
                    this.maxRange = response.data.pool_max;
                    this.value = [this.minRange, this.maxRange];
                    console.log(this.value);

                    self.percentMaxChange = (self.maxRange - self.currentPrice) / self.currentPrice * 100.0;
                    self.percentMinChange = (self.currentPrice - self.minRange) / self.currentPrice * 100.0;

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
        getData() {
            let self = this
            axios
                .get('/api/cryptocurrency/edit/' + this.$route.params.id)
                .then(function (response) {
                    self.minRange = response.data.model.min;
                    self.maxRange = response.data.model.max;
                    self.currentPrice = response.data.last_candle;
                    console.log(response.data.model.min)
                })
                .catch(function (error) {
                    console.error(error)
                })
        },
        async update() {
            let self = this;
            this.loading = true;
            axios.get('/sanctum/csrf-cookie').then((response) => {
                axios
                    .post('/api/cryptocurrency/update', {
                        id: this.$route.params.id,
                        minRange: self.minRange,
                        maxRange: self.maxRange,
                        currentPrice: self.currentPrice,
                    })
                    .then((response) => {
                        if (response.status) {
                            this.$vaToast.init({ message: 'Pool обновлен', color: 'success' })
                            console.log('Pool обновлен');
                            self.loading = false;

                            this.percentMaxChange = (self.maxRange - self.currentPrice) / self.currentPrice * 100.0;
                            this.percentMinChange = (self.currentPrice - self.minRange) / self.currentPrice * 100.0;

                            //Test
                            self.chart.data[0].axisY.stripLines[0].set("value", self.minRange)
                            self.chart.data[0].axisY.stripLines[0].set("label", self.percentMinChange)
                            self.chart.data[0].axisY.stripLines[1].set("value", self.maxRange)
                            self.chart.data[0].axisY.stripLines[1].set("label", self.percentMaxChange)
                            self.chart.data[0].axisY.stripLines[2].set("value", self.currentPrice)
                            self.chart.data[0].axisY.stripLines[2].set("label", self.currentPrice)

                            this.chart.render();
                        } else {
                            console.log('Не работает')
                            console.log(response.status)
                        }
                    })
                    .catch(function (error) {
                        console.log(response)
                        console.error(error)
                    })
            })
        },
    },
    mounted: function () {
        this.getData();
    },
}
</script>