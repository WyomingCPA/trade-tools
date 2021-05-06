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
        <span v-else-if="props.column.field === 'ticker'">
          {{ props.row.ticker }}
        </span>
        <span v-else-if="props.column.field === 'nominal'">
          {{ props.row.nominal }}
        </span>
        <span v-else-if="props.column.field === 'currency'">
          {{ props.row.currency }}
        </span>
        <span v-else-if="props.column.field === 'cci_hour'">
          {{ props.row.cci_hour }}
        </span>
        <span v-else-if="props.column.field === 'ema_hour'">
          {{ props.row.ema_hour }}
        </span>
        <span v-else-if="props.column.field === 'rsi_hour'">
          {{ props.row.rsi_hour }}
        </span>
        <span v-else-if="props.column.field === '1h'">
          <a
            target="_blank"
            class="btn btn-primary"
            :href="'chart-1h/' + props.row.id"
            >Смотреть график</a
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
  },
  data() {
    return {
      columns: [
        {
          label: "Name",
          field: "name",
        },
        {
          label: "ticker",
          field: "ticker",
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
          label: "EMA 1H",
          field: "ema_hour",
        },
        {
          label: "RSI 1H",
          field: "rsi_hour",
        },
        {
          label: "1H",
          field: "1h",
          type: "number",
        },
      ],
    };
  },
};
</script>