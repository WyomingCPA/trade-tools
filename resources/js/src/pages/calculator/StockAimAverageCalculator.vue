<template>
  <div>
    <b-card title="Average Down Calculator">
      <b-form @submit="handleCalculate">
        <b-form-row> </b-form-row>
        <b-form-row>
          <b-col :sm="2"
            ><label class="mr-1">General Price Stock:</label
            ><b-form-input
              v-model.number="GeneralPriceStock"
              type="number"
              step="any"
          /></b-col>
          <b-col :sm="2"
            ><label class="mr-1">General Count Stock:</label
            ><b-form-input
              v-model.number="GeneralCountStock"
              type="number"
              step="any"
          /></b-col>
          <b-col :sm="2"
            ><label class="mr-1">Aim average(max) price:</label
            ><b-form-input
              v-model.number="AimAveragePrice"
              type="number"
              step="any"
          /></b-col>
          <b-col :sm="2"
            ><label class="mr-1">This stock price:</label
            ><b-form-input
              v-model.number="ThisPriceStock"
              type="number"
              step="any"
          /></b-col>
          <b-col :sm="2"
            ><label class="mr-1">Count Result:</label
            ><b-form-input v-model.number="Result" type="number" step="any"
          /></b-col>
        </b-form-row>
        <b-form-row class="mt-3">
          <b-col :sm="10"
            ><b-btn type="submit" class="btn-success float-md-right"
              >Calculate</b-btn
            ></b-col
          >
        </b-form-row>
      </b-form>
    </b-card>
  </div>
</template>
<script>
export default {
  name: "stock-aim-average-calculator",
  data() {
    return {
      GeneralPriceStock: 0,
      GeneralCountStock: 0,
      ThisPriceStock: 0,
      AimAveragePrice: 0,
      Result: 0,
    };
  },

  methods: {
    handleCalculate(evt) {
      evt.preventDefault();
      let priceStock = this.GeneralPriceStock;
      let count = 1;
      while (priceStock >= this.AimAveragePrice || count == 200) {
        let firstAmountBougt = this.GeneralPriceStock * this.GeneralCountStock;
        let TotalAmountBought = count * this.ThisPriceStock + firstAmountBougt;
        let totalCount = count + this.GeneralCountStock;
        priceStock = TotalAmountBought / totalCount;
        console.log(priceStock);
        count++;
      }
      this.Result = count;
    },
  },
};
</script>