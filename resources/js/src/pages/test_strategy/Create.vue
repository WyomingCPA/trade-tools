<template>
  <b-card title="Создание теста">
    <b-form @submit.prevent="create">
      <div class="row">
        <b-form-group
          label="Start date:"
          label-for="start-period"
          description=""
          class="mb-0 col-sm-4"
        >
          <b-form-datepicker
            id="start-period"
            v-model="startDate"
            class="mb-2"
            trim
          ></b-form-datepicker>
        </b-form-group>
        <b-form-group
          label="End date:"
          label-for="end-period"
          description=""
          class="mb-0 col-sm-4"
        >
          <b-form-datepicker
            id="end-period"
            v-model="endDate"
          ></b-form-datepicker>
        </b-form-group>
      </div>
      <div class="row">
        <b-form-group
          label="Select tools:"
          label-for="select-tools"
          class="mt-3 col-sm-4"
        >
          <b-form-select
            id="select-tools"
            v-model="selectedFigi"
            :options="optionsFigi"
          ></b-form-select>
        </b-form-group>
        <b-form-group
          label="Select TimeFrame:"
          label-for="select-timeframe"
          class="mt-3 col-sm-4"
        >
          <b-form-select
            id="select-timeframe"
            v-model="selectedTimeFrame"
            :options="optionsTimeFrame"
          ></b-form-select>
        </b-form-group>
        <b-form-group
          label="Select Strategy:"
          label-for="select-strategy"
          class="mt-3 col-sm-4"
        >
          <b-form-select
            id="select-strategy"
            v-model="selectedStrategy"
            :options="optionsStrategy"
          ></b-form-select>
        </b-form-group>
      </div>
      <div class="d-flex">
        <b-button type="submit" variant="success" class="mr-2"
          >Создать</b-button
        >
      </div>
    </b-form>
  </b-card>
</template>
<script>
import axios from "axios";

export default {
  components: {},
  data() {
    return {
      startDate: "",
      endDate: "",
      selectedFigi: null,
      selectedTimeFrame: null,
      selectedStrategy: null,
      optionsFigi: [],
      optionsTimeFrame: [
        { value: "5min", text: "5 min" },
        { value: "15min", text: "15 min" },
        { value: "1hours", text: "1 hour" },
      ],
      optionsStrategy: [
        { value: 'SuperTrend+MACD_TimeFrame_5min', text: "SuperTrend+MACD TimeFrame 5min" },
        { value: 'RSI+MACD_TimeFrame_5min', text: "RSI+MACD TimeFrame 5min" },
        { value: 'SuperTrend+MACD_TimeFrame_15min', text: "SuperTrend+MACD TimeFrame 15min" },
      ],
    };
  },
  methods: {
    fetchData() {
      let self = this;
      axios
        .get("/api/test-strategy/create")
        .then(function (response) {
          self.optionsFigi = response.data.tools;
          self.optionsFigi = self.optionsFigi.map(function (tools) {
            return { value: tools.figi, text: tools.name };
          });
        })
        .catch(function (error) {
          console.error(error);
        });
    },
    async create() {
      //console.log(this.startDate);
      axios.get("/sanctum/csrf-cookie").then((response) => {
        axios
          .post("/api/test-strategy/store", {
            start_period: this.startDate,
            end_period: this.endDate,
            figi: this.selectedFigi,
            time_frame: this.selectedTimeFrame,
            strategy_name: this.selectedStrategy,
          })
          .then((response) => {
            if (response.status) {
              console.log("Вызвали алерт");
              this.$router.push({ path: '/test-strategy' });
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
  },
  mounted: function () {
    this.fetchData();
  },
};
</script>