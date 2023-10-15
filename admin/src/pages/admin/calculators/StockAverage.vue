<template>
    <va-card>
        <va-card-title>Stock Aim Average</va-card-title>
        <va-card-content>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 items-end">
                <va-input inputClass="mb-6" v-model.number="countShares1" type="number" step="any" label="Кол-во акций" />
                <va-input inputClass="mb-6" v-model.number="purcharesPrice1" type="number" step="any"
                    label="Цена покупки" />
                <va-input inputClass="mb-6" v-model.number="summPrice1" type="number" step="any" label="Сумма покупки" />
            </div>
            <div class="flex-row">
                <va-input v-model.number="countShares2" type="number" step="any" label="Кол-во акций" counter />
                <va-input v-model.number="purcharesPrice2" type="number" step="any" counter label="Цена покупки" />
                <va-input v-model.number="summPrice2" type="number" step="any" counter label="Сумма покупки" />
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 items-end">
                <va-input v-model.number="countShares3" type="number" step="any" label="Кол-во акций" counter />
                <va-input v-model.number="purcharesPrice3" type="number" step="any" counter label="Цена покупки" />
                <va-input v-model.number="summPrice3" type="number" step="any" counter label="Сумма покупки" />
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 items-end">
                <va-input v-model.number="countShares4" type="number" step="any" label="Кол-во акций" counter />
                <va-input v-model.number="purcharesPrice4" type="number" step="any" counter label="Цена покупки" />
                <va-input v-model.number="summPrice4" type="number" step="any" counter label="Сумма покупки" />
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 items-end">
                <va-input v-model.number="countShares5" type="number" step="any" label="Кол-во акций" counter />
                <va-input v-model.number="purcharesPrice5" type="number" step="any" counter label="Цена покупки" />
                <va-input v-model.number="summPrice5" type="number" step="any" counter label="Сумма покупки" />
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 items-end">
                <va-input v-model.number="TotalSharesBought" type="number" step="any" label="Result:" counter />
                <va-input v-model.number="AverageCost" type="number" step="any" counter label="Price Average Result:" />
                <va-input v-model.number="PercentPriceChange" type="number" step="any" counter
                    label="% different result:" />
            </div>

            <va-button @click="handleCalculate"> Рассчитать </va-button>

            <va-data-table :items="items" :columns="columns" @filtered="filteredCount = $event.items.length"
                :loading=loading selectable selected-color="warning">
                <template #cell(actions)="{ row, isExpanded }">
                    <va-button @click="row.toggleRowDetails()" :icon="isExpanded ? 'va-arrow-up' : 'va-arrow-down'"
                        preset="secondary" class="w-full">
                        {{ isExpanded ? 'Hide' : 'More info' }}
                    </va-button>
                </template>
                <template #expandableRow="{ rowData }">
                    <div class="flex gap-2">
                        <va-avatar :src="`https://randomuser.me/api/portraits/men/${rowData.id}.jpg`" />
                        <div class="pl-2">
                            <div class="flex gap-1">
                                <span>{{ rowData.name }}</span>
                                <span class="va-link">@{{ rowData.username }}</span>
                            </div>
                            <div class="flex items-center">
                                <va-icon size="small" name="phone" color="secondary" class="mr-2" />
                                <span>{{ rowData.phone }}</span>
                            </div>
                            <div class="flex items-center">
                                <va-icon size="small" name="email" color="secondary" class="mr-2" />
                                <span>{{ rowData.email }}</span>
                            </div>
                            <div class="flex items-center">
                                <va-icon size="small" name="language" color="secondary" class="mr-2" />
                                <span class="va-link">{{ rowData.website }}</span>
                            </div>
                        </div>
                    </div>
                </template>
            </va-data-table>
        </va-card-content>
    </va-card>
</template>
  
<script>
import axios from 'axios'
import qs from 'qs'
//Пример https://praveenmp.github.io/stockaveragecalculator/
//https://www.omnicalculator.com/math/weighted-average
export default {
    name: "stock-average-calculator",
    data() {
        const items = [];
        const columns = [
            { key: 'id', sortable: true },
            { key: 'created_at', width: 80 },
            { key: "actions", width: 80 },
        ]
        return {
            countShares1: 509,
            countShares2: 50,
            countShares3: 0,
            countShares4: 0,
            countShares5: 0,
            resultShares: 0,
            purcharesPrice1: 6.15,
            purcharesPrice2: 6.09,
            purcharesPrice3: 0,
            purcharesPrice4: 0,
            purcharesPrice5: 0,
            summPrice1: 0,
            summPrice2: 0,
            summPrice3: 0,
            summPrice4: 0,
            summPrice5: 0,
            TotalShares: 0,
            AverageCost: 0,
            TotalSharesBought: 0,
            TotalAmountBought: 0,
            PercentPriceChange: 0,
            columns,
            items,
        };
    },

    methods: {
        handleCalculate(evt) {
            evt.preventDefault();
            //step 1, Total Shares Bought = Shares Bought(1st) + Shares Bought(2nd) + Shares Bought(3rd) + .... Shares Bought (nth)
            this.TotalSharesBought =
                this.countShares1 +
                this.countShares2 +
                this.countShares3 +
                this.countShares4 +
                this.countShares5;
            //step 2, Total Amount Bought = Shares Bought*Purchased Price(1st) + Shares Bought*Purchased Price(2nd) + Shares Bought*Purchased Price(3rd) + .... Shares Bought*Purchased Price(nth)
            this.TotalAmountBought =
                this.countShares1 * this.purcharesPrice1 +
                this.countShares2 * this.purcharesPrice2 +
                this.countShares3 * this.purcharesPrice3 +
                this.countShares4 * this.purcharesPrice4 +
                this.countShares5 * this.purcharesPrice5;
            //step 3, Stock Average Price = Total Amount Bought / Total Shares Bought
            this.AverageCost = (this.TotalAmountBought / this.TotalSharesBought).toFixed(2);

            //Рассчет суммы для колонки Summ Price
            this.summPrice1 = this.countShares1 * this.purcharesPrice1;
            this.summPrice2 = this.countShares2 * this.purcharesPrice2;
            this.summPrice3 = this.countShares3 * this.purcharesPrice3;
            this.summPrice4 = this.countShares4 * this.purcharesPrice4;
            this.summPrice5 = this.countShares5 * this.purcharesPrice5;
            //Рассчет процентов изменений цены, от начальной к усредненной. 
            let decreaseValue = 0;
            decreaseValue = this.AverageCost - this.purcharesPrice1;
            this.PercentPriceChange = ((decreaseValue / this.purcharesPrice1) * 100).toFixed(2);
            this.create();
            console.log(decreaseValue);

        },
        async create() {
            let self = this;
            axios.get("/sanctum/csrf-cookie").then((response) => {
                axios
                    .post("/api/calculate/store", {
                        type: "stock_average",
                        countShares1: self.countShares1,
                        countShares2: self.countShares2,
                        countShares3: self.countShares3,
                        countShares4: self.countShares4,
                        countShares5: self.countShares5,
                        resultShares: self.resultShares,
                        purcharesPrice1: self.purcharesPrice1,
                        purcharesPrice2: self.purcharesPrice2,
                        purcharesPrice3: self.purcharesPrice3,
                        purcharesPrice4: self.purcharesPrice4,
                        purcharesPrice5: self.purcharesPrice5,
                        summPrice1: self.summPrice1,
                        summPrice2: self.summPrice2,
                        summPrice3: self.summPrice3,
                        summPrice4: self.summPrice4,
                        summPrice5: self.summPrice5,
                        TotalShares: self.TotalShares,
                        AverageCost: self.AverageCost,
                        TotalSharesBought: self.TotalSharesBought,
                        TotalAmountBought: self.TotalAmountBought,
                        PercentPriceChange: self.PercentPriceChange,
                    })
                    .then((response) => {
                        if (response.status) {
                            console.log("Вызвали алерт");
                        } else {
                            console.log("Не работает");
                            console.log(response.status);
                        }
                    })
                    .catch(function (error) {
                        console.log(response);
                        console.error(error);
                    });
            });
        },
        fetchRows() {
            let self = this;
            this.loading = true;
            axios
                .request({
                    method: "get",
                    url: "/api/calculate/stock-average",
                    params: this.serverParams,
                    paramsSerializer: (params) => {
                        return qs.stringify(params);
                    },
                })
                .then((response) => {
                    self.items = response.data.calc_models;
                    self.loading = false;
                })
                .catch((error) => {
                    console.log(error);
                    self.loading = false;
                });
        },
    },
    created() {
        this.fetchRows();
    },
};
</script>