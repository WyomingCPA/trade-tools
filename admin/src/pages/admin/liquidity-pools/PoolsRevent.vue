<template>
    <div class="row">
        <div class="flex xs12 sm6 md6">
            <va-card>
                <va-card-content>
                    <h2 class="va-h2 ma-0">{{ today_open_orders }}</h2>
                    <p class="no-wrap">Статистика за Сегодня</p>
                </va-card-content>
            </va-card>
        </div>
        <div class="flex xs12 sm6 md6">
            <va-card>
                <va-card-content>
                    <h2 class="va-h2 ma-0">{{ week_open_orders }}</h2>
                    <p class="no-wrap">Статистика за Неделю</p>
                </va-card-content>
            </va-card>
        </div>
        <div class="flex xs12 sm6 md6">
            <va-card>
                <va-card-content>
                    <h2 class="va-h2 ma-0">{{ month_open_orders }}</h2>
                    <p class="no-wrap">Статистика за Месяц</p>
                </va-card-content>
            </va-card>
        </div>
    </div>
    <va-card>
        <va-card-content>
            <div class="grid md:grid-cols-2 gap-6 mb-6">
                <va-input v-model="input" placeholder="Filter..." class="w-full" />
            </div>
            <div class="row">
                <div class="col">
                    <va-button @click="unfavorite">
                        Убрать из избранного
                    </va-button>
                </div>

            </div>
            <va-data-table :items="items" :columns="columns" :filter="filter" :filter-method="customFilteringFn"
                @filtered="filteredCount = $event.items.length" :loading=loading selectable selected-color="warning"
                @selectionChange="selectedItemsEmitted = $event.currentSelectedItems">

                <template #cell(name)="{ rowData }">
                    <a class="link" target="_blank" :href="'https://www.tinkoff.ru/invest/stocks/' + rowData.ticker">{{
                        rowData.name
                    }}</a>
                </template>
                <template #cell(chart_1h)="{ rowData }">
                    <a target="_blank" class="btn btn-primary" :href="'chart-1h/' + rowData.id">График</a>
                </template>
                <template #cell(chart_1d)="{ rowData }">
                    <a target="_blank" class="btn btn-primary" :href="'chart-1d/' + rowData.id">График</a>
                </template>
                <template #cell(pools_range)="{ rowData }">
                    <span>{{ formatRange(rowData.pools_range) }}</span>
                </template>

                <template #cell(actions)="{ rowData }">
                    <a target="_blank" :href="'edit/' + rowData.id">Редактировать</a>
                </template>
            </va-data-table>

            <va-alert class="!mt-6" color="info" outline>
                Number of filtered items:
                <va-chip>{{ filteredCount }}</va-chip>
            </va-alert>
        </va-card-content>
    </va-card>
</template>
<script>
import { array } from '@amcharts/amcharts5'
import axios from 'axios'
import debounce from 'lodash/debounce.js'
import qs from 'qs'

export default {
    name: 'StockFavorite',
    components: {},
    data() {
        const items = [];
        const input = '';
        const columns = [
            { key: 'id', sortable: true },
            { key: 'name', sortable: true },
            { key: 'balances', width: 80 },
            { key: 'uncollected', width: 80 },
            { key: 'created_at', width: 80 },

        ]
        return {
            loading: false,
            items,
            columns,
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
                    url: "/api/cryptocurrency/pools",
                    params: this.serverParams,
                    paramsSerializer: (params) => {
                        return qs.stringify(params);
                    },
                })
                .then((response) => {
                    self.items = response.data.pools;
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
<style lang="scss" scoped>
.table-crud {
    --va-form-element-default-width: 0;

    .va-input {
        display: block;
    }

    &__slot {
        th {
            vertical-align: middle;
        }
    }
}

.modal-crud {
    .va-input {
        display: block;
    }
}

.link {
    color: #f5f5f5;
}
</style>