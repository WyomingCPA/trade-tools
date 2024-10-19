<template setup>
    <va-card v-for="item in items" :key="item.id">
        <va-card-title>{{ item.symbol }}</va-card-title>
        <va-card-content>
            <PoolChart :candles="item.candles" :pool_min="item.pool_min" :pool_max="item.pool_max" :symbol="item.symbol" />
        </va-card-content>
    </va-card>
</template>
<script>
import PoolChart from './components/PoolChart.vue'
import { array } from '@amcharts/amcharts5'
import axios from 'axios'
import debounce from 'lodash/debounce.js'
import qs from 'qs'

export default {
    name: 'ListPoolsChart',
    components: {PoolChart},
    data() {
        const items = [];
        const input = '';
        return {
            loading: false,
            items,
            input,
            filter: input,
            isDebounceInput: false,
            isCustomFilteringFn: false,
            filteredCount: items.length,
            selectedItemsEmitted: [],
            listPrice: Array,
            serverParams: {
                name: "",
            },
        }
    },
    methods: {
        formatRange(stringObject) {
            //firstImage = JSON.parse(stringObject);
            if (stringObject != null) {
                let cellRange = stringObject.min + '-' + stringObject.max;
                return cellRange;
            }
            else {
                let cellRange = 'Не задано';
                return cellRange;
            }

        },
        filterExact(source) {
            if (this.filter === '') {
                return true
            }
            return source?.toString?.() === this.filter
        },

        updateFilter(filter) {
            this.filter = filter
        },

        debouncedUpdateFilter: debounce(function (filter) {
            this.updateFilter(filter)
        }, 600),

        filterPrice: function (param) {
            this.filter = param
            this.input = param
        },

        fetchRows() {
            let self = this;
            this.loading = true;
            axios
                .request({
                    method: "get",
                    url: "/api/cryptocurrency/list-chart-pools-data",
                    params: this.serverParams,
                    paramsSerializer: (params) => {
                        return qs.stringify(params);
                    },
                })
                .then((response) => {
                    self.items = response.data.list_chart;
                    self.loading = false;
                })
                .catch((error) => {
                    console.log(error);
                    self.loading = false;
                });
        },
        resetInfoModal() {
            this.infoModal.title = ''
            this.infoModal.content = ''
        },

    },
    computed: {
        customFilteringFn() {
            return this.isCustomFilteringFn ? this.filterExact : undefined
        },
    },
    watch: {
        input(newValue) {
            if (this.isDebounceInput) {
                this.debouncedUpdateFilter(newValue)
            } else {
                this.updateFilter(newValue)
            }
        },
    },

    created() {
        this.fetchRows();
    },
}

</script>