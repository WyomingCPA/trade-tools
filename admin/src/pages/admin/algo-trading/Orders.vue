<template>
    <va-card>
        <va-card-content>
            <div class="grid md:grid-cols-2 gap-6 mb-6">
                <VaAlert
                    description="Запуск бота 15 мин<code>nohup /var/www/trader/env/bin/python SuperTrend_MACD_TimeFrame_15min.py &</code>" />
            </div>
            <div class="grid md:grid-cols-2 gap-6 mb-6">
                <VaAlert
                    description="Запуск чекера стоп-ордеров<code> nohup /var/www/trader/env/bin/python check_stop_order.py &</code>" />
            </div>
            <div class="grid md:grid-cols-2 gap-6 mb-6">
                <VaAlert description="Проверка состояния процесса <code>ps -ef | grep python</code>" />
            </div>
            <div class="grid md:grid-cols-2 gap-6 mb-6">
                <div class="flex flex-wrap gap-6 mb-6">
                    <va-button color="success" @click="startScriptSuperTrend15min()">Start Script 15 min</va-button>
                    <va-button color="danger" @click="stopScriptSuperTrend15min()">Stop Script 15 min</va-button>
                </div>
                <div class="flex flex-wrap gap-6 mb-6">
                    <va-button color="danger" @click="failOrder">Неудачная сделка</va-button>
                    <va-button color="info" @click="nothingOrder">Ничего</va-button>
                    <va-button color="success" @click="successOrder">Успешная сделка</va-button>
                </div>
                <div class="grid md:grid-cols-2 gap-6 mb-6">
                    <div class="flex flex-wrap gap-6 mt-6">
                        <va-input v-model="input" placeholder="Filter..." class="w-full" />
                    </div>
                </div>

            </div>
            <va-data-table v-model="selectedItems" :loading=loading selectable selected-color="warning" :items="items" :columns="columns"
                :filter="filter" :filter-method="customFilteringFn" @filtered="filteredCount = $event.items.length"
                @selectionChange="selectedItemsEmitted = $event.currentSelectedItems">
                <template #cell(graph15min)="{ rowData }">
                    <a target="_blank" class="btn btn-primary" :href="'/admin/algo-trading/order-chart-15min/' + rowData.id">View</a>
                </template>
                <template #cell(status)="{ rowData }">
                    <VaBadge :text="rowData.status" :color="getStatusBadgeClass(rowData.status)" class="mr-2" />
                </template>
                <template #cell(actions)="{ rowData }">
                    <va-button color="danger" @click="deleteOrder(rowData.id)">Удалить</va-button>
                </template>
                <template #bodyAppend>
                    <tr>
                        <td colspan="6">
                            <div class="flex justify-center mt-4">
                                <va-pagination v-model="currentPage" :pages="pages" />
                            </div>
                        </td>
                    </tr>
                </template>
            </va-data-table>

            <va-alert class="!mt-6" color="info" outline>
                Number of filtered items:
                <va-chip>{{ count }}</va-chip>
            </va-alert>
        </va-card-content>
    </va-card>

</template>
<script>
// import the styles
import axios from "axios";
import qs from 'qs'

export default {
    name: "stock-rub",
    data() {
        const items = []
        return {
            selectedItemsEmitted: [],
            selectedItems: [],
            count: { type: Number },
            dataUrl: { type: String },
            loading: false,
            infoModal: {
                id: "info-modal",
                title: "",
                content: "",
            },
            serverParams: {
                figi: "",
            },
            items,
            columns: [
                { key: "id" },
                { key: "name-instrument" },
                { key: "figi" },
                { key: "current_price" },
                { key: "quantity" },
                { key: "spot-order-count" },
                { key: "stop-order-count" },
                { key: "created_at" },
                { key: "graph15min" },
                { key: "strategy_name" },
                { key: "status" },
                { key: 'actions', width: 80 },
            ],
        };
    },
    methods: {
        updateParams(newProps) {
            this.serverParams = Object.assign({}, this.serverParams, newProps);
        },
        onPageChange(params) {
            this.updateParams({ page: params.currentPage });
            this.fetchRows();
        },
        onSearch(params) {
            this.updateParams({ name: params });
            this.fetchRows();
        },
        selectionChanged: function (params) {
            this.selRows = params.selectedRows;
        },
        successOrder: function (event, rows) {
            var self = this;
            this.loading = true;
            axios.get("/sanctum/csrf-cookie").then((response) => {
                axios
                    .post("/api/orders/set-success", { selRows: self.selectedItemsEmitted })
                    .then((response) => {
                        if (response.status) {
                            console.log("Вызвали алерт");
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
        failOrder: function (event, rows) {
            var self = this;
            this.loading = true;
            axios.get("/sanctum/csrf-cookie").then((response) => {
                axios
                    .post("/api/orders/set-fail", { selRows: self.selectedItemsEmitted })
                    .then((response) => {
                        if (response.status) {
                            console.log("Вызвали алерт");
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
        nothingOrder: function (event, rows) {
            var self = this;
            this.loading = true;
            axios.get("/sanctum/csrf-cookie").then((response) => {
                axios
                    .post("/api/orders/set-nothing", { selRows: self.selectedItemsEmitted })
                    .then((response) => {
                        if (response.status) {
                            console.log("Вызвали алерт");
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
        deleteOrder: function (idRows) {
            var self = this;
            this.loading = true;
            axios.get("/sanctum/csrf-cookie").then((response) => {
                axios
                    .post("/api/orders/delete", { selRows: idRows })
                    .then((response) => {
                        if (response.status) {
                            console.log("Вызвали алерт");
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
        getNote(item, index, button) {
            this.infoModal.title = item.created_at;
            this.infoModal.content = item.note;
            this.idCheck = item.id;
            console.log(item.note);
            this.$root.$emit("bv::show::modal", this.infoModal.id, button);
        },
        handleOk(bvModalEvt) {
            // Prevent modal from closing
            bvModalEvt.preventDefault();
            // Trigger submit handler
            this.handleSubmit();
        },
        handleSubmit() { },
        fetchRows() {
            let self = this;
            this.loading = true;
            axios
                .request({
                    method: "post",
                    url: "/api/orders/index",
                    params: this.serverParams,
                    paramsSerializer: (params) => {
                        return qs.stringify(params);
                    },
                })
                .then((response) => {
                    self.items = response.data.orders;
                    self.count = response.data.count;
                    this.loading = false;
                })
                .catch((error) => {
                    console.log(error);
                    this.loading = false;
                });
        },
        onPerPageChange(params) {
            this.updateParams({ perPage: params.currentPerPage });
            this.fetchRows();
        },
        async startScriptSuperTrend15min() {
            let self = this;
            this.loading = true;
            axios.get("/sanctum/csrf-cookie").then((response) => {
                axios
                    .post("/api/orders/start-script-super-trend-15min", {})
                    .then((response) => {
                        if (response.status) {
                            self.loading = false;
                            this.fetchRows();
                        } else {
                            console.log("Не работает");
                            console.log(response.status);
                            self.loading = false;
                        }
                    })
                    .catch(function (error) {
                        console.log(response);
                        console.error(error);
                    });
            });
        },
        async stopScriptSuperTrend15min() {
            let self = this;
            this.loading = true;
            axios.get("/sanctum/csrf-cookie").then((response) => {
                axios
                    .post("/api/orders/stop-script-super-trend-15min", {})
                    .then((response) => {
                        if (response.status) {
                            self.loading = false;
                            this.fetchRows();
                        } else {
                            console.log("Не работает");
                            console.log(response.status);
                            self.loading = false;
                        }
                    })
                    .catch(function (error) {
                        console.log(response);
                        console.error(error);
                    });
            });
        },
        async startScriptCheckStopOrder() {
            let self = this;
            this.loading = true;
            axios.get("/sanctum/csrf-cookie").then((response) => {
                axios
                    .post("/api/orders/start-script-check-stop-order", {})
                    .then((response) => {
                        if (response.status) {
                            this.loading = false;
                            this.fetchRows();
                        } else {
                            console.log("Не работает");
                            console.log(response.status);
                            self.loading = false;
                        }
                    })
                    .catch(function (error) {
                        console.log(response);
                        console.error(error);
                    });
            });
        },
        getStatusBadgeClass(status) {
            console.log(status);
            if (status == "empty") {
                return "primary";
            } else if (status == "success") {
                return "success";
            } else if (status == "fail") {
                return "danger";
            } else if (status == "nothing") {
                return "secondary";
            } else {
                return "";
            }
        },
    },
    created() {
        this.fetchRows();
    },
    requestAdapter(data) {
        return {
            sort: data.orderBy ? data.orderBy : "name",
            direction: data.ascending ? "asc" : "desc",
            limit: data.limit ? data.limit : 5,
            page: data.page,
            name: data.query.name,
            created_by: data.query.created_by,
            type: data.query.type,
            created_at: data.query.created_at,
        };
    },
};
</script>