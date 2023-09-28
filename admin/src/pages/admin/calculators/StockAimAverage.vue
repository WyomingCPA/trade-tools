<template>
    <va-card>
        <va-card-title>Stock Aim Average</va-card-title>
        <va-card-content>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 items-end">
                <va-input v-model.number="GeneralPriceStock" type="number" step="any" label="Общая цена инструмента в портфеле" counter />
                <va-input v-model.number="GeneralCountStock" type="number" step="any" counter label="Общее кол-во инструмента в портфеле" />
                <va-input v-model.number="AimAveragePrice" type="number" step="any" counter label="Aim average(max) price" />
                <va-input v-model.number="ThisPriceStock" type="number" step="any" counter label="Текущая цена инструмента" />
                <va-input v-model.number="Result" type="number" step="any" counter label="Count Result" />
                <va-button @click="handleCalculate"> Рассчитать </va-button>
            </div>
        </va-card-content>
    </va-card>

</template>
<script>
export default {
    name: "stock-aim-average-calculator",
    data() {
        return {
            GeneralPriceStock: 5.56,
            GeneralCountStock: 549,
            ThisPriceStock: 5.55,
            AimAveragePrice: 5.50,
            Result: 0,
        };
    },

    methods: {
        handleCalculate(evt) {
            evt.preventDefault();
            let priceStock = parseFloat(this.GeneralPriceStock).toFixed(2);
            
            let count = parseFloat(1).toFixed(2);
            console.log(count);
            while (priceStock >= this.AimAveragePrice || count == 200) {
                //parseFloat((0.1 + 0.2+0.7).toFixed(10));
                let firstAmountBougt = parseFloat(this.GeneralPriceStock * this.GeneralCountStock).toFixed(2); 
                let TotalAmountBought = parseFloat(count * this.ThisPriceStock + firstAmountBougt).toFixed(2);
                let totalCount = parseFloat(count + this.GeneralCountStock).toFixed(2);
                priceStock = parseFloat(TotalAmountBought / totalCount).toFixed(2);
                console.log(priceStock);
                count++;
            }
            this.Result = count;
        },
    },
};
</script>