<template>
  <div>
    <div class="row mb-3">
      <div class="col-12">
        <b-card title="Панель действий">
          <div class="mr-auto text-sm-right pt-2 pt-sm-0">
            <b-button
              v-on:click="getAllFutures"
              type="submit"
              variant="success"
              class="mr-2"
              ><span v-show="!loading">Обновить список фьючерсов</span>
              <div
                v-show="loading"
                class="spinner-border spinner-border-sm"
                role="status"
              >
                <span class="sr-only">Loading...</span>
              </div>
            </b-button>
          </div>
        </b-card>
      </div>
    </div>

    <vue-good-table
      @on-page-change="onPageChange"
      @on-per-page-change="onPerPageChange"
      @on-search="onSearch"
      @on-selected-rows-change="selectionChanged"
      :isLoading="loading"
      :totalRows="count"
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
var qs = require("qs");

export default {
  name: "futures-all",
  data() {
    return {
      loading: false,
      count: { type: Number },
      serverParams: {
        name: "",
      },
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
          label: "Figi",
          field: "figi",
        },
        {
          label: "Class Code",
          field: "class_code",
        },
        {
          label: "Lot",
          field: "lot",
        },
        {
          label: "Валюта",
          field: "currency",
        },
      ],
    };
  },
  methods: {
    fetchData() {
      let self = this;
      this.loading = true;
      axios
        .request({
          method: "get",
          url: "/api/futures/all",
          params: this.serverParams,
          paramsSerializer: (params) => {
            return qs.stringify(params);
          },
        })
        .then((response) => {
          self.items = response.data.futures;
          self.count = response.data.count;
          self.loading = false;
          console.log("test")
        })
        .catch((error) => {
          console.log(error);
          self.loading = false;
        });
    },
    async getAllFutures() {
      let self = this;
      this.loading = true;
      axios.get("/sanctum/csrf-cookie").then((response) => {
        axios
          .post("/api/futures/get-all-futures", {})
          .then((response) => {
            if (response.status) {
              self.loading = false;
              this.fetchData();
            } else {
              console.log("Не работает");
              console.log(response.status);
              self.loading = false;
            }
          })
          .catch(function (error) {
            console.log(response);
            console.error(error);
          });
      });
    },
    updateParams(newProps) {
      this.serverParams = Object.assign({}, this.serverParams, newProps);
    },
    onPageChange(params) {
      this.updateParams({ page: params.currentPage });
      this.fetchData();

    },
    onSearch(params) {
      this.updateParams({ name: params });
      this.fetchData();
      this.loading = false;
    },
    onPerPageChange(params) {
      this.updateParams({ perPage: params.currentPerPage });
      this.fetchData();
    },
    selectionChanged: function (params) {
      this.selRows = params.selectedRows;
    },
    favorite: function (event, rows) {
      let self = this;
      this.loading = true;
      axios.get("/sanctum/csrf-cookie").then((response) => {
        axios
          .post("/api/futures/favorite", { selRows: this.selRows })
          .then((response) => {
            if (response.status) {
              console.log("Вызвали алерт");
              this.fetchData();
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
    this.fetchData();
  },
};
</script>