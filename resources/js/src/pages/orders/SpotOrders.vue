<template>
  <div>
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
      <template slot="table-row" slot-scope="props">
        <span v-if="props.column.field === 'name'">
          <a
            target="_blank"
            :href="'https://www.tinkoff.ru/invest/stocks/' + props.row.ticker"
            >{{ props.row.name }}</a
          >
        </span>
        <span v-else-if="props.column.field === 'stop-order-count'">
          <a
            target="_blank"
            class="btn btn-primary"
            :href="'/orders/stop-orders/' + props.row.id"
            >{{ props.row["stop-order-count"] }}</a
          >
        </span>
        <span v-else-if="props.column.field === 'detail'">
          <a
            target="_blank"
            class="btn btn-primary"
            :href="'/orders/spot-detil/' + props.row.id"
            >Detail</a
          >
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
  name: "stop-orders",
  data() {
    return {
      count: { type: Number },
      dataUrl: { type: String },
      loading: false,
      id_order: 0,
      serverParams: {},
      items: [],
      columns: [
        {
          label: "Type",
          field: "type",
        },
        {
          label: "Время создания",
          field: "created_at",
        },
        {
          label: "Detail",
          field: "detail",
        },
      ],
    };
  },
  methods: {
    updateParams(newProps) {
      this.serverParams = Object.assign({}, this.serverParams, newProps);
    },
    onPageChange(params) {
      this.updateParams({ page: params.currentPage });
      this.fetchRows();
    },
    onSearch(params) {
      this.updateParams({ name: params });
      this.fetchRows();
    },
    selectionChanged: function (params) {
      this.selRows = params.selectedRows;
    },
    fetchRows() {
      console.log(this.$route.params.id);
      let self = this;
      this.loading = true;
      this.id_order = this.$route.params.id;
      axios
        .get("/api/orders/spot-orders/" + this.$route.params.id)
        .then(function (response) {
          self.items = response.data.spots;
          self.loading = false;
          console.log(response.data.spots);
        })
        .catch(function (error) {
          console.error(error);
        });
    },
    onPerPageChange(params) {
      this.updateParams({ perPage: params.currentPerPage });
      this.fetchRows();
    },
  },
  created() {
    this.fetchRows();
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