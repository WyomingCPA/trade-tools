<template>
  <div>
    <vue-good-table
      @on-selected-rows-change="selectionChanged"
      :isLoading="loading"
      theme="nocturnal"
      :columns="columns"
      :rows="items"
      :sort-options="{
        enabled: true,
        multipleColumns: true,
        initialSortBy: [
          { field: 'is_portfolio', type: 'desc' },
          { field: 'cci_day', type: 'asc' },
        ],
      }"
      :line-numbers="true"
      :pagination-options="{
        enabled: true,
        mode: 'records',
        perPage: 15,
        position: 'top',
        perPageDropdown: [10, 30, 10],
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
        <span v-else-if="props.column.field === 'is_portfolio'">
          {{ props.row.is_portfolio }}
        </span>
      </template>
    </vue-good-table>
  </div>
</template>
<script>
import axios from "axios";
// import the styles
import "vue-good-table/dist/vue-good-table.css";

export default {
  name: "stock-dividends",
  data() {
    return {
      loading: false,
      items: [
        {
          name: "",
          ticker: "",
          currency: "",
          last_price: "",
        },
      ],
      columns: [
        {
          label: "Name",
          field: "name",
        },
        {
          label: "Валюта",
          field: "currency",
          filterOptions: {
            styleClass: "class2", // class to be added to the parent th element
            enabled: true, // enable filter for this column
            placeholder: "Currency", // placeholder for filter input
            filterDropdownItems: ["RUB", "USD", "EUR"], // dropdown (with selected values) instead of text input
            filterFn: this.filterCurrency, //custom filter function that
            trigger: "enter", //only trigger on enter not on keyup
          },
        },
        {
          label: "CCI 1H",
          field: "cci_hour",
          type: "number",
          filterOptions: {
            styleClass: "class2", // class to be added to the parent th element
            enabled: true, // enable filter for this column
            placeholder: "Фильтр <= x", // placeholder for filter input
            filterDropdownItems: [-200, -100, -50, 0], // dropdown (with selected values) instead of text input
            filterFn: this.filterHourCCI, //custom filter function that
            trigger: "enter", //only trigger on enter not on keyup
          },
        },
        {
          label: "CCI 1D",
          field: "cci_day",
          type: "number",
          filterOptions: {
            styleClass: "class2", // class to be added to the parent th element
            enabled: true, // enable filter for this column
            placeholder: "Фильтр <= x", // placeholder for filter input
            filterDropdownItems: [-200, -100, -50, 0], // dropdown (with selected values) instead of text input
            filterFn: this.filterDayCCI, //custom filter function that
            trigger: "enter", //only trigger on enter not on keyup
          },
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
        {
          label: "My",
          field: "is_portfolio",
          type: "boolean",
        },
      ],
    };
  },
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
    filterCurrency(data, filterString) {
      return data == filterString;
    },
    filterHourCCI(data, filterString) {
      var cci_hour = data;
      var filterString = parseInt(filterString);
      return data <= filterString;
    },
    filterDayCCI(data, filterString) {
      var cci_hour = data;
      var filterString = parseInt(filterString);
      return data <= filterString;
    },
    getStock() {
      let self = this;
      this.loading = true;
      axios
        .get("/api/stock/dividends")
        .then(function (response) {
          self.items = response.data.stocks;
          self.loading = false;
        })
        .catch(function (error) {
          console.error(error);
          self.loading = false;
        });
    },
  },
  mounted: function () {
    this.getStock();
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
  top: -0.76rem;
  bottom: -1.9rem;
  padding: 0.75rem;
}
</style>