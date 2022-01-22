<template>
  <trading-vue :data="this.$data"></trading-vue>
</template>
<script>

import TradingVue from "trading-vue-js";
import axios from "axios";

export default {
  name: "app",
  components: { TradingVue },
  data() {
    return {
      ohlcv: [],
    };
  },
  methods: {
    fetchRows() {
      let self = this;
      this.loading = true;
      var itemId = this.$route.params.id;
      axios
        .request({
          method: "post",
          url: "/api/stock/indicator-tutci/" + itemId,
          params: this.serverParams,
          paramsSerializer: (params) => {
            return qs.stringify(params);
          },
        })
        .then((response) => {
          self.ohlcv = response.data.candles;
          console.log(response.data);
          self.count = response.data.count;
          self.loading = false;
        })
        .catch((error) => {
          console.log(error);
          self.loading = false;
        });
    },
  },
  created() {
    this.fetchRows();
  },
};
</script>