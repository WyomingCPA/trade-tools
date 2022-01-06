<template>
  <div>
    <vue-good-table
      @on-selected-rows-change="selectionChanged"
      :isLoading="loading"
      theme="nocturnal"
      :columns="columns"
      :rows="items"
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
        <button v-on:click="unhide">Убрать из скрытого</button>
      </div>
      <template slot="table-row" slot-scope="props">
        <span v-if="props.column.field === 'name'">
          <a
            target="_blank"
            :href="'https://www.tinkoff.ru/invest/bonds/' + props.row.ticker"
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
import axios from "axios";
import "vue-good-table/dist/vue-good-table.css";
var qs = require("qs");

export default {
  name: "bond-new",
  methods: {
    selectionChanged: function (params) {
      this.selRows = params.selectedRows;
    },
    unhide: function (event, rows) {
      var self = this;
      this.loading = true;
      axios.get("/sanctum/csrf-cookie").then((response) => {
        axios
          .post("/api/bond/untrash", { selRows: this.selRows })
          .then((response) => {
            if (response.status) {
              console.log("Вызвали алерт");
              this.fetchRows();
              this.loading = false;
            } else {
              console.log("Не работает");
              console.log(response.status);
              this.loading = false;
            }
          });
      });
    },
    fetchRows() {
      let self = this;
      this.loading = true;
      axios
        .request({
          method: "get",
          url: "/api/bond/trash",
          params: this.serverParams,
          paramsSerializer: (params) => {
            return qs.stringify(params);
          },
        })
        .then((response) => {
          self.items = response.data.bonds;
          self.loading = false;
        })
        .catch((error) => {
          console.log(error);
          self.loading = false;
        });
    },
  },
  data() {
    return {
      loading: false,
      items: [],
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
          label: "Номинал",
          field: "nominal",
          type: "number",
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
  created() {
    this.fetchRows();
  },
};
</script>