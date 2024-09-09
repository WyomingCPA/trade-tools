<template>
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
                <template #cell(graph15min)="{ rowData }">
                    <a target="_blank" class="btn btn-primary" :href="'/admin/stocks/stock-chart-15min/' + rowData.id">View</a>
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
            { key: 'name', sortable: true },
            { key: 'ticker', sortable: true },
            { key: 'figi', sortable: true },
            { key: "graph15min", width: 80 },
            { key: 'currency', width: 80 },
            { key: 'average15day', width: 80 },
            { key: 'tutci', width: 80 },
            { key: 'tutci', width: 80 },
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
                    url: "/api/stock/favorites",
                    params: this.serverParams,
                    paramsSerializer: (params) => {
                        return qs.stringify(params);
                    },
                })
                .then((response) => {
                    self.items = response.data.stocks;
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
        unfavorite: function (event, rows) {
            var self = this;
            this.loading = true;
            axios.get("/sanctum/csrf-cookie").then((response) => {
                axios
                    .post("/api/stock/unfavorite", { selRows: self.selectedItemsEmitted })
                    .then((response) => {
                        if (response.status) {
                            console.log("Вызвали алерт");
                            this.$vaToast.init({ message: 'Success', color: 'success' })
                            this.fetchRows();
                            this.loading = false;
                        } else {
                            console.log("Не работает");
                            console.log(response.status);
                            this.loading = false;
                        }
                    });
            });
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
    