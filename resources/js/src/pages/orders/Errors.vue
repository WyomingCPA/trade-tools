<template>
  <div>
    <vue-good-table
      @on-page-change="onPageChange"
      @on-per-page-change="onPerPageChange"
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
      :select-options="{
        enabled: true,
      }"
    >
      <div slot="selected-row-actions">
        <button v-on:click="successOrder">Успешная сделка</button>
      </div>
      <div slot="selected-row-actions">
        <button v-on:click="failOrder">Неудачная сделка</button>
      </div>
      <div slot="selected-row-actions">
        <button v-on:click="nothingOrder">Ничего</button>
      </div>
      <div slot="selected-row-actions">
        <button class="btn btn-danger" v-on:click="deleteOrder">Delete</button>
      </div>
      <template slot="table-row" slot-scope="props">
        <span v-if="props.column.field === 'message'">
          {{ props.row.message }}
        </span>
      </template>
    </vue-good-table>
  </div>
</template>
<script>
import axios from "axios";
import "vue-good-table/dist/vue-good-table.css";

export default {
  name: "mini-console",
  data() {
    return {
      count: { type: Number },
      dataUrl: { type: String },
      loading: false,

      serverParams: {
        figi: "",
      },
      items: [],
      columns: [
        {
          label: "id",
          field: "id",
        },
        {
          label: "Message",
          field: "message",
        },
        {
          label: "Время создания",
          field: "created_at",
        },
      ],
    };
  },
  methods: {
    getErrors() {
      let self = this;
      this.loading = true;
      axios
        .get("/api/orders/last-error")
        .then(function (response) {
          self.items = response.data.errors;
          self.loading = false;
          console.log(response.data.errors);
        })
        .catch(function (error) {
          console.error(error);
        });
    },
  },
  created() {
    this.getErrors();
  },
};
</script>
