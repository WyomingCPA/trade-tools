<template>
    <va-card>
        <va-card-title>Stock Aim Average</va-card-title>
        <va-card-content>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 items-end">
                <div class="row">
                    <va-input v-model.number="countShares1" type="number" step="any"
                        label="Кол-во акций" counter />
                    <va-input v-model.number="purcharesPrice1" type="number" step="any" counter
                        label="Цена покупки" />
                    <va-input v-model.number="summPrice1" type="number" step="any" counter
                        label="Сумма покупки" />
                </div>
                <div class="row">
                    <va-input v-model.number="countShares2" type="number" step="any"
                        label="Кол-во акций" counter />
                    <va-input v-model.number="purcharesPrice2" type="number" step="any" counter
                        label="Цена покупки" />
                    <va-input v-model.number="summPrice2" type="number" step="any" counter
                        label="Сумма покупки" />
                </div>
                <div class="row">
                    <va-input v-model.number="countShares3" type="number" step="any"
                        label="Кол-во акций" counter />
                    <va-input v-model.number="purcharesPrice3" type="number" step="any" counter
                        label="Цена покупки" />
                    <va-input v-model.number="summPrice3" type="number" step="any" counter
                        label="Сумма покупки" />
                </div>
                <div class="row">
                    <va-input v-model.number="countShares4" type="number" step="any"
                        label="Кол-во акций" counter />
                    <va-input v-model.number="purcharesPrice4" type="number" step="any" counter
                        label="Цена покупки" />
                    <va-input v-model.number="summPrice4" type="number" step="any" counter
                        label="Сумма покупки" />
                </div>
                <div class="row">
                    <va-input v-model.number="countShares5" type="number" step="any"
                        label="Кол-во акций" counter />
                    <va-input v-model.number="purcharesPrice5" type="number" step="any" counter
                        label="Цена покупки" />
                    <va-input v-model.number="summPrice5" type="number" step="any" counter
                        label="Сумма покупки" />
                </div>
                <div class="row">
                    <va-input v-model.number="TotalSharesBought" type="number" step="any"
                        label="Result:" counter />
                    <va-input v-model.number="AverageCost" type="number" step="any" counter
                        label="Price Average Result:" />
                    <va-input v-model.number="PercentPriceChange" type="number" step="any" counter
                        label="% different result:" />
                </div>
                <va-button @click="handleCalculate"> Рассчитать </va-button>
            </div>
        </va-card-content>
    </va-card>

</template>
  
<script>
//Пример https://praveenmp.github.io/stockaveragecalculator/
//https://www.omnicalculator.com/math/weighted-average
export default {
    name: "stock-average-calculator",
    data() {
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
            console.log(decreaseValue);
        },
    },
};
</script>