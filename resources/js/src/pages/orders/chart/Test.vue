<template>
  <test
    v-if="load"
    :candles="cand"
    :orders="order"
    :take_profit1="list_take_profit1"
    :take_profit2="list_take_profit2"
    :stop_orders1="list_stop_orders1"
    :stop_orders2="list_stop_orders2"
  />
</template>
<script>
import axios from "axios";


import OrderChart5M from "./OrderChart5M.vue";

export default {
  name: "Test",
  components: {
    test: OrderChart5M,
  },

  methods: {

    fetchData() {
      console.log(this.$route.params.id);
      let self = this;
      this.id_order = this.$route.params.id;
      axios
        .get("/api/orders/chart-orders/" + this.$route.params.id)
        .then(function (response) {
          self.cand = response.data.candles;
          self.order = response.data.order;
          self.list_take_profit1 = response.data.list_take_profit1;
          self.list_take_profit2 = response.data.list_take_profit2;
          self.list_stop_orders1 = response.data.list_stop_orders1;
          self.list_stop_orders2 = response.data.list_stop_orders2;
          self.load = true;
          console.log(self.order);
        })
        .catch(function (error) {
          console.error(error);
        });
    },
  },

  created() {
    this.fetchData();
  },

  data() {
    return {
      load: false,
      order: [],
      cand: [],
      list_take_profit1: [],
      list_take_profit2: [],
      list_stop_orders1: [],
      list_stop_orders2: [],
    };
  },
};
</script>
