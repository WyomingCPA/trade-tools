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
        <button v-on:click="favorite">Favorite</button>
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
// import the styles
import axios from "axios";
import "vue-good-table/dist/vue-good-table.css";

export default {
  name: "stock-all",
  data() {
    return {
      loading: false,
      items: [
        {
          name: "-",
          ticker: "-",
          currency: "-",
          last_price: "-",
        },
      ],
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
  methods: {
    getStock() {
      let self = this;
      this.loading = true;
      axios
        .get("/api/stock/all")
        .then(function (response) {
          self.items = response.data.stocks;
          self.loading = false;
        })
        .catch(function (error) {
          console.error(error);
          self.loading = false;
        });
    },
    selectionChanged: function (params) {
      this.selRows = params.selectedRows;
    },
    favorite: function (event, rows) {
      let self = this;
      this.loading = true;
      axios.get("/sanctum/csrf-cookie").then((response) => {
        axios
          .post("/api/stock/favorite", { selRows: this.selRows })
          .then((response) => {
            if (response.status) {
              console.log("Вызвали алерт");
              this.getStock();
              self.loading = false;
            } else {
              console.log("Не работает");
              console.log(response.status);
              self.loading = false;
            }
          });
      });
    },
  },
  mounted: function () {
    this.getStock();
  },
};
</script>