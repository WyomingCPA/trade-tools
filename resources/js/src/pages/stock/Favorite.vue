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
      <template slot="loadingContent">
        <!-- your content here -->
      </template>
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
        <span v-else-if="props.column.field === 'action'">
          <a
            target="_blank"
            class="btn btn-primary"
            :href="'action/' + props.row.id"
            >Перейти</a
          >
        </span>
      </template>
    </vue-good-table>
  </div>
</template>

<script>
// import the styles
import "vue-good-table/dist/vue-good-table.css";
import axios from "axios";

export default {
  name: "stock-favorite",
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
        {
          label: "Action",
          field: "action",
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
      this.loading = true;
      axios.get("/sanctum/csrf-cookie").then((response) => {
        axios
          .post("/api/stock/unfavorite", { selRows: this.selRows })
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
          url: "/api/stock/favorites",
          params: this.serverParams,
          paramsSerializer: (params) => {
            return qs.stringify(params);
          },
        })
        .then((response) => {
          self.items = response.data.stocks;
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