<script>
import { Line } from "vue-chartjs";
import axios from "axios";

export default {
  extends: Line,
  props: ["datas"],
  data() {
    return {
      datacollection: {
        labels: this.datas.reverse().map((row) => row.time),
        datasets: [
          {
            label: "# of Votes",
            data: this.datas.reverse().map((row) => row.close),
            borderColor: ["rgba(255,99,132,1)"],
            borderWidth: 0,
            fill: false,
          },
        ],
      },
      options: {
        maintainAspectRatio: false,
        radius: 0,
        legend: {
          display: false,
        },
        scales: {
          yAxes: [
            {
              ticks: {
                beginAtZero: false,
                display: false,
              },
              gridLines: {
                display: false,
              },
            },
            
          ],
          xAxes: [
            {
              ticks: {
                beginAtZero: false,
                display: false,
              },
              gridLines: {
                display: false,
              },
            },
          ],
        },
        elements: {
          point: {
            radius: 0,
          },
        },
      },
    };
  },
  methods: {},

  mounted() {
    console.log(this.datas.map((row) => row.close.toFixed(5)));
    console.log(this.datas.map((row) => row.time));
    this.renderChart(this.datacollection, this.options);
  },
};
</script>
