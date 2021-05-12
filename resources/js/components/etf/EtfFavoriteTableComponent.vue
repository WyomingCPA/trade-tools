<template>
  <div>
    <vue-good-table
      @on-selected-rows-change="selectionChanged"
      :theme="nocturnal"
      :columns="columns"
      :rows="etfs"
      :sort-options="{
        enabled: true,
      }"
      :line-numbers="true"
      :pagination-options="{
        enabled: true,
        mode: 'records',
        perPage: 100,
        position: 'top',
        perPageDropdown: [200, 300, 500],
        dropdownAllowAll: false,
        setCurrentPage: 1,
        nextLabel: 'next',
        prevLabel: 'prev',
        rowsPerPageLabel: 'Rows per page',
        ofLabel: 'of',
        pageLabel: 'page', // for 'pages' mode
        allLabel: 'All',
      }"
      :search-options="{ enabled: true }"
      :select-options="{
        enabled: true,
      }"
    >
      <div slot="selected-row-actions">
        <button v-on:click="unfavorite">Убрать из избранного</button>
      </div>
      <template slot="table-row" slot-scope="props">
        <span v-if="props.column.field === 'name'">
          <a
            target="_blank"
            :href="'https://www.tinkoff.ru/invest/etfs/' + props.row.ticker"
            >{{ props.row.name }}</a
          >
        </span>
        <span v-else-if="props.column.field === 'aime'">
          {{ props.row.aim }}
        </span>
        <span v-else-if="props.column.field === 'nominal'">
          {{ props.row.nominal }}
        </span>
        <span v-else-if="props.column.field === 'currency'">
          {{ props.row.currency }}
        </span>
        <span class="wrap" v-else-if="props.column.field === 'cci_hour'">
          <span :class="getCellCciClass(props.row.cci_hour)">
            {{ props.row.cci_hour }}
          </span>
        </span>
        <span class="wrap" v-else-if="props.column.field === 'cci_day'">
          <span :class="getCellCciClass(props.row.cci_day)">
            {{ props.row.cci_day }}
          </span>
        </span>
        <span class="wrap" v-else-if="props.column.field === 'ema_hour'">
          <span :class="getCellEmaClass(props.row.ema_hour)">
            {{ props.row.ema_hour }}
          </span>
        </span>
        <span class="wrap" v-else-if="props.column.field === 'ema_day'">
          <span :class="getCellEmaClass(props.row.ema_day)">
            {{ props.row.ema_day }}
          </span>
        </span>
        <span class="wrap" v-else-if="props.column.field === 'rsi_hour'">
          <span :class="getCellRsiClass(props.row.rsi_hour)">
            {{ props.row.rsi_hour }}
          </span>
        </span>
        <span class="wrap" v-else-if="props.column.field === 'rsi_day'">
          <span :class="getCellRsiClass(props.row.rsi_day)">
            {{ props.row.rsi_day }}
          </span>
        </span>
        <span v-else-if="props.column.field === '1h'">
          <a
            target="_blank"
            class="btn btn-primary"
            :href="'chart-1h/' + props.row.id"
            >График</a
          >
        </span>
        <span v-else-if="props.column.field === '1d'">
          <a
            target="_blank"
            class="btn btn-primary"
            :href="'chart-1d/' + props.row.id"
            >График</a
          >
        </span>
      </template>
    </vue-good-table>
  </div>
</template>

<script>
import VueGoodTablePlugin from "vue-good-table";

// import the styles
import "vue-good-table/dist/vue-good-table.css";
import each from "lodash.foreach";
Vue.use(VueGoodTablePlugin);

export default {
  props: ["etfs"],
  name: "etf-favorite-table",
  methods: {
    selectionChanged: function (params) {
      this.selRows = params.selectedRows;
    },
    unfavorite: function (event, rows) {
      var self = this;
      axios.post("unfavorite", { selRows: this.selRows }).then((response) => {
        window.location.href = "favorites";
      });
    },
    getCellCciClass(cci_hour) {
      if (cci_hour <= -100) {
        return "is-green";
      } else if (cci_hour >= 100) {
        return "is-red";
      } else {
        return "";
      }
    },
    getCellEmaClass(indicator) {
      if (indicator == "buy") {
        return "is-green";
      } else if (indicator == "sell") {
        return "is-red";
      } else {
        return "";
      }
    },
    getCellRsiClass(indicator) {
      if (indicator <= 30) {
        return "is-green";
      } else if (indicator >= 70) {
        return "is-red";
      } else {
        return "";
      }
    },
  },

  data() {
    return {
      columns: [
        {
          label: "Name",
          field: "name",
        },
        {
          label: "Цели",
          field: "aime",
        },
        {
          label: "Валюта",
          field: "currency",
        },
        {
          label: "CCI 1H",
          field: "cci_hour",
        },
        {
          label: "CCI 1D",
          field: "cci_day",
        },
        {
          label: "EMA 1H",
          field: "ema_hour",
        },
        {
          label: "EMA 1D",
          field: "ema_day",
        },
        {
          label: "RSI 1H",
          field: "rsi_hour",
        },
        {
          label: "RSI 1D",
          field: "rsi_day",
        },
        {
          label: "1H",
          field: "1h",
        },
        {
          label: "1D",
          field: "1d",
        },
      ],
    };
  },
};
</script>
<style>
.wrap {
  display: block;
  position: relative;
  width: 100%;
}
.is-green {
  background: green;
  color: white;
  position: absolute;
  left: -0.75rem;
  right: -0.75rem;
  top: -0.75rem;
  bottom: -1.9rem;
  padding: 0.75rem;
}
.is-red {
  background: red;
  color: white;
  position: absolute;
  left: -0.75rem;
  right: -0.75rem;
  top: -0.75rem;
  bottom: -1.9rem;
  padding: 0.75rem;
}
</style>