<template>
  <div>
    <b-card title="Узнать лотность инструмента">
      <b-form @submit="handleCalculate">
        <b-form-row> </b-form-row>
        <b-form-row>
          <b-col :sm="2"
            ><label class="mr-1">Figi инструмента:</label
            ><b-form-input v-model="Figi" type="text"
          /></b-col>
        </b-form-row>
        <b-form-row>
          <b-col :sm="2"
            ><label class="mr-1">Введите нужную сумму:</label
            ><b-form-input
              v-model.number="SummCurrency"
              type="number"
              step="any"
          /></b-col>
        </b-form-row>
        <b-form-row class="mt-3">
          <b-col :sm="10"
            ><b-btn type="submit" class="btn-success float-md-right"
              >Рассчитать</b-btn
            ></b-col
          >
        </b-form-row>
        <b-form-row class="mt-3">
          <b-col :sm="10"><p>Количество доступных лотов: {{ Result }}</p></b-col
          >
        </b-form-row>
      </b-form>
    </b-card>
  </div>
</template>
<script>
import axios from "axios";

export default {
  name: "stock-lots-calculation",
  data() {
    return {
      Figi: "",
      SummCurrency: 0,
      Result: 0,
    };
  },

  methods: {
    getData() {
      let self = this;
      this.id_idea = this.$route.params.id;
      axios
        .get("/api/ideas/get-idea/" + this.$route.params.id)
        .then(function (response) {
          self.Figi = response.data.figi;
          console.log(response.data.figi);
        })
        .catch(function (error) {
          console.error(error);
        });
    },
    async handleCalculate(evt) {
      evt.preventDefault();
      var self = this;
      axios.get("/sanctum/csrf-cookie").then((response) => {
        axios
          .post("/api/ideas/calculate-lots", {
            figi: this.Figi,
            summ: this.SummCurrency,
          })
          .then((response) => {
            if (response.status) {
              self.Result = response.data.max_lots;
              console.log(response.data.max_lots);
            } else {
              console.log("Не работает");
              console.log(response.status);
            }
          });
      });
    },
  },
  created() {
    this.getData();
  },
};
</script>