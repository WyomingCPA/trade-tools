<template>
  <div>
    <vue-good-table
      @on-page-change="onPageChange"
      @on-per-page-change="onPerPageChange"
      @on-selected-rows-change="selectionChanged"
      @on-search="onSearch"
      :isLoading.sync="isLoading"
      :totalRows="count"
      :theme="nocturnal"
      :columns="columns"
      :rows="stocks"
      mode="remote"
      :sort-options="{
        enabled: true,
      }"
      :line-numbers="true"
      :pagination-options="{
        enabled: true,
        mode: 'records',
        perPage: 20,
        position: 'top',
        perPageDropdown: null,
        dropdownAllowAll: false,
        setCurrentPage: 1,
        nextLabel: 'next',
        prevLabel: 'prev',
        rowsPerPageLabel: 'Rows per page',
        ofLabel: 'of',
        pageLabel: 'page', // for 'pages' mode
        allLabel: 'All',
        chunk: 5,
      }"
      :search-options="{ enabled: true }"
      :select-options="{
        enabled: true,
      }"
    >
      <div slot="selected-row-actions">
        <button v-on:click="favorite">Favorite</button>
      </div>
      <div slot="selected-row-actions">
        <button v-on:click="hide">Hide</button>
      </div>
      <div slot="selected-row-actions">
        <button v-on:click="set_dividends">Set Dividends</button>
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

var qs = require("qs");

export default {
  props: {
    count: { type: Number },
    stocks: { type: Array },
    dataUrl: { type: String },
  },
  name: "stock-all-table",
  methods: {
    updateParams(newProps) {
      this.serverParams = Object.assign({}, this.serverParams, newProps);
    },
    onPageChange(params) {
      this.updateParams({ page: params.currentPage });
      this.fetchRows();
    },
    selectionChanged: function (params) {
      this.selRows = params.selectedRows;
    },
    onSearch(params) {
      this.updateParams({ name: params });
      this.fetchRows();
    },
    hide: function (event, rows) {
      var self = this;
      axios.post("trash", { selRows: this.selRows }).then((response) => {
        window.location.href = "all";
      });
    },
    favorite: function (event, rows) {
      var self = this;
      axios.post("favorite", { selRows: this.selRows }).then((response) => {
        window.location.href = "all";
      });
    },
    set_dividends: function (event, rows) {
      var self = this;
      axios
        .post("set-dividends", { selRows: this.selRows })
        .then((response) => {
          window.location.href = "dividends";
        });
    },
    fetchRows() {
      axios
        .request({
          method: "post",
          url: this.dataUrl,
          params: this.serverParams,
          paramsSerializer: (params) => {
            return qs.stringify(params);
          },
        })
        .then((response) => {
          this.stocks = response.data.stocks;
          console.log(response.data.stocks);
          this.totalRecords = response.count;
        })
        .catch((error) => {
          console.log(error);
        });
    },
    created() {
      this.fetchRows();
    },
    onPerPageChange(params) {
      this.updateParams({ perPage: params.currentPerPage });
      this.fetchRows();
    },
  },
  data() {
    return {
      rows: [],
      isLoading: false,
      serverParams: {
        name: "",
      },
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
          label: "Последняя цена",
          field: "last_price",
          type: "number",
        },
      ],
    };
  },
  requestAdapter(data) {
    return {
      sort: data.orderBy ? data.orderBy : "name",
      direction: data.ascending ? "asc" : "desc",
      limit: data.limit ? data.limit : 5,
      page: data.page,
      name: data.query.name,
      created_by: data.query.created_by,
      type: data.query.type,
      created_at: data.query.created_at,
    };
  },
};
</script>