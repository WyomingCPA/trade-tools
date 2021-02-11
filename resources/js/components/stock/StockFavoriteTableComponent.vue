<template>
  <div>
    <vue-good-table
      @on-selected-rows-change="selectionChanged"
      :theme="nocturnal"
      :columns="columns"
      :rows="stocks"
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
            :href="'https://www.tinkoff.ru/invest/stocks/' + props.row.ticker"
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
        <span v-else-if="props.column.field === 'last_price'">
          {{ props.row.last_price }}
        </span>
        <span v-else-if="props.column.field === 'adx'">
          {{ props.row.adx }}
        </span>
        <span v-else-if="props.column.field === 'average15day'">
          <a
            target="_blank"
            class="btn btn-primary"
            :href="'emachart/' + props.row.id"
            >{{ props.row.average15day }}</a
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
  props: ["stocks"],
  name: "stock-favorite-table",
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
          label: "AVG",
          field: "average15day",
          type: "number",
        },
        {
          label: "ADX",
          field: "adx",
        },
      ],
    };
  },
};
</script>