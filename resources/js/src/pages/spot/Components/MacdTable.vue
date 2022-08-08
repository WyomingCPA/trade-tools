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
        <span v-else-if="props.column.field === 'graph'">
          <a
            target="_blank"
            class="btn btn-primary"
            :href="'/orders/mini-chart/' + props.row.id"
            >View</a
          >
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
  name: "macd-table",
  props: {
    items: Array,
  },
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
          label: "Line",
          field: "0",
        },
        {
          label: "Time",
          field: "1",
        },
      ],
    };
  },
};
</script>
