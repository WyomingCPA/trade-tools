<template>
  <div class="small">
    <line-chart :chart-data="datacollection" :width="300" :height="300" ></line-chart>
  </div>
</template>

<script>
import LineChart from "./LineChart.js";
import each from "lodash.foreach";

export default {
  components: {
    LineChart,
  },
  data() {
    return {
      datacollection: null,
    };
  },
  mounted() {
    this.fillData();
  },
  methods: {
    fillData() {
      let url = window.location.href;
      let lastParam = url.split("/").slice(-1)[0];
      let uri = lastParam;

      let Time = new Array();
      let Close = new Array();
      axios.get(uri).then((response) => {
        let data = response.data;
        if (data) {
          Object.keys(data).forEach((element) => {
            Time.push(element);
          });
          Object.values(data).forEach((element) => {
            Close.push(element);
          });

          this.datacollection = {
            labels: Time,
            datasets: [
              {
                label: "5 min",
                backgroundColor: "transparent",
                borderColor: "rgba(1, 116, 188, 0.50)",
                pointBackgroundColor: "rgba(171, 71, 188, 1)",
                data: Close,
              },
            ],
          };
        } else {
          console.log("No data");
        }
      });
    },
  },
};
</script>

