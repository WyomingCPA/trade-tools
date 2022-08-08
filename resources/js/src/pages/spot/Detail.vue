<template>
  <super-trend-table v-bind:items="items" v-if="type === 'supertrend'"></super-trend-table>
  <macd-table v-bind:items="items" v-else-if="type === 'macd'"></macd-table>
</template>
<script>
// import the styles
import axios from "axios";
import "vue-good-table/dist/vue-good-table.css";
import SuperTrendTable from "./Components/SuperTrendTable.vue";
import MacdTable from "./Components/MacdTable.vue";

var qs = require("qs");

export default {
  name: "stop-orders",
  components: {
    SuperTrendTable,
    MacdTable,
  },
  data() {
    return {
      count: { type: Number },
      dataUrl: { type: String },
      type: { type: String },
      loading: false,
      id_order: 0,
      serverParams: {},
      items: [],
    };
  },
  methods: {
    fetchRows() {
      let self = this;
      this.loading = true;
      this.id_order = this.$route.params.id;
      axios
        .get("/api/orders/spot-detil/" + this.$route.params.id)
        .then(function (response) {
          self.items = response.data.data;
          self.type = response.data.type;
          console.log(self.items);
          self.loading = false;
        })
        .catch(function (error) {
          console.error(error);
        });
    },
  },
  created() {
    this.fetchRows();
  },
};
</script>