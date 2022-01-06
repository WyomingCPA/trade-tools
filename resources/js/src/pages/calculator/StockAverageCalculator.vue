<template>
  <div>
    <b-card title="Average Down Calculator">
      <b-form @submit="handleCalculate">
        <b-form-row>
          <b-col :sm="3"><label class="mr-1">Кол-во акций</label></b-col>
          <b-col :sm="3"><label class="mr-1">Цена покупки</label></b-col>
          <b-col :sm="3"><label class="mr-1">Сумма покупки</label></b-col>
        </b-form-row>
        <b-form-row>
          <label class="mr-1">1:</label>
          <b-col :sm="3"
            ><b-form-input v-model.number="countShares1" type="number"
          /></b-col>
          <b-col :sm="3"
            ><b-form-input
              v-model.number="purcharesPrice1"
              type="number"
              step="any"
          /></b-col>
          <b-col :sm="3"
            ><b-form-input v-model.number="summPrice1" type="number" step="any"
          /></b-col>
        </b-form-row>
        <b-form-row class="mt-3">
          <label class="mr-1">2:</label>
          <b-col :sm="3"
            ><b-form-input v-model.number="countShares2" type="number"
          /></b-col>
          <b-col :sm="3"
            ><b-form-input
              v-model.number="purcharesPrice2"
              type="number"
              step="any"
          /></b-col>
          <b-col :sm="3"
            ><b-form-input v-model.number="summPrice2" type="number" step="any"
          /></b-col>
        </b-form-row>
        <b-form-row class="mt-3">
          <label class="mr-1">3:</label>
          <b-col :sm="3"
            ><b-form-input v-model.number="countShares3" type="number"
          /></b-col>
          <b-col :sm="3"
            ><b-form-input
              v-model.number="purcharesPrice3"
              type="number"
              step="any"
          /></b-col>
          <b-col :sm="3"
            ><b-form-input v-model.number="summPrice3" type="number" step="any"
          /></b-col>
        </b-form-row>
        <b-form-row class="mt-3">
          <label class="mr-1">4:</label>
          <b-col :sm="3"
            ><b-form-input v-model.number="countShares4" type="number"
          /></b-col>
          <b-col :sm="3"
            ><b-form-input
              v-model.number="purcharesPrice4"
              type="number"
              step="any"
          /></b-col>
          <b-col :sm="3"
            ><b-form-input v-model.number="summPrice4" type="number" step="any"
          /></b-col>
        </b-form-row>
        <b-form-row class="mt-3">
          <label class="mr-1">5:</label>
          <b-col :sm="3"
            ><b-form-input v-model.number="countShares5" type="number"
          /></b-col>
          <b-col :sm="3"
            ><b-form-input
              v-model.number="purcharesPrice5"
              type="number"
              step="any"
          /></b-col>
          <b-col :sm="3"
            ><b-form-input v-model.number="summPrice5" type="number" step="any"
          /></b-col>
        </b-form-row>
        <b-form-row class="mt-3">
          <label class="mr-0">Result:</label>
          <b-col :sm="3"
            ><b-form-input
              v-model.number="TotalSharesBought"
              type="number"
              step="any"
          /></b-col>
          <b-col :sm="3"
            ><b-form-input
              v-model.number="AverageCost"
              type="number"
              step="any"
          /></b-col>
          <b-col :sm="3"
            ><b-form-input
              v-model.number="PercentPriceChange"
              type="number"
              step="any"
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
  name: "stock-average-calculator",
  data() {
    return {
      countShares1: 0,
      countShares2: 0,
      countShares3: 0,
      countShares4: 0,
      countShares5: 0,
      resultShares: 0,
      purcharesPrice1: 0,
      purcharesPrice2: 0,
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
      this.AverageCost = this.TotalAmountBought / this.TotalSharesBought;

      //Рассчет суммы для колонки Summ Price
      this.summPrice1 = this.countShares1 * this.purcharesPrice1;
      this.summPrice2 = this.countShares2 * this.purcharesPrice2;
      this.summPrice3 = this.countShares3 * this.purcharesPrice3;
      this.summPrice4 = this.countShares4 * this.purcharesPrice4;
      this.summPrice5 = this.countShares5 * this.purcharesPrice5;
      //Рассчет процентов изменений цены, от начальной к усредненной. 
      let decreaseValue = 0;
      decreaseValue = this.AverageCost - this.purcharesPrice1;
      this.PercentPriceChange = (decreaseValue / this.purcharesPrice1) * 100;
      console.log(decreaseValue);
    },
  },
};
</script>