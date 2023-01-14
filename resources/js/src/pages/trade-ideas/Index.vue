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
      <div slot="selected-row-actions">
        <button v-on:click="deleteIdea">Delete</button>
      </div>
      <template slot="table-row" slot-scope="props">
        <span v-if="props.column.field === 'id'">
          <a target="_blank" :href="'/trade-ideas/edit/' + props.row.id">{{
            props.row.id
          }}</a>
        </span>
        <span v-else-if="props.column.field === 'name'">
          <a target="_blank" :href="'/trade-ideas/description/' + props.row.id">{{
            props.row.id
          }}</a>
        </span>
        <span v-else-if="props.column.field === 'created_at'">
          <span>{{ props.row.created_at }}</span>
        </span>
        <span v-else-if="props.column.field === 'graph'">
          <a
            target="_blank"
            class="btn btn-primary"
            :href="'/test-strategy/strategy-chart/' + props.row.id"
            >View</a
          >
        </span>
        <span v-else-if="props.column.field === 'status'">
          <span :class="getStatusBadgeClass(props.row.status)">
            {{ props.row.status }}
          </span>
        </span>
        <span v-else-if="props.column.field === 'action'">
          {{ props.row.action }}
        </span>
        <span v-else-if="props.column.field === 'operation'">
          <b-button
            size="sm"
            @click="calculateLots(props, $event.target)"
            class="mr-1"
          >
            Рассчитать лотность
          </b-button>
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
  name: "all-idea",
  data() {
    return {
      count: { type: Number },
      dataUrl: { type: String },
      loading: false,
      infoModal: {
        id: "info-modal",
        title: "",
        content: "",
      },
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
          label: "Tools",
          field: "name-instrument",
        },
        {
          label: "Name",
          field: "name",
        },
        {
          label: "Figi",
          field: "figi",
        },
        {
          label: "Время обновления",
          field: "updated_at",
        },
        {
          label: "Продолжительность(в днях)",
          field: "min_period",
        },
        {
          label: "Цель цены",
          field: "aim_price",
        },
        {
          label: "Status",
          field: "status",
        },
        {
          label: "Направление",
          field: "action",
        },
        {
          label: "Операций",
          field: "operation",
        },
      ],
    };
  },
  methods: {
    calculateLots(item, button) {
      console.log(item);
      let route = this.$router.resolve({
        path: "/calculator/stock-lots-calculation/" + item.row.id,
      });
      window.open(route.href);
    },
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
    getNote(item, index, button) {
      this.infoModal.title = item.created_at;
      this.infoModal.content = item.note;
      this.idCheck = item.id;
      console.log(item.note);
      this.$root.$emit("bv::show::modal", this.infoModal.id, button);
    },
    handleOk(bvModalEvt) {
      // Prevent modal from closing
      bvModalEvt.preventDefault();
      // Trigger submit handler
      this.handleSubmit();
    },
    handleSubmit() {},
    fetchRows() {
      let self = this;
      this.loading = true;

      axios
        .get("/api/ideas/index")
        .then(function (response) {
          self.items = response.data.models;
          self.loading = false;
        })
        .catch(function (error) {
          console.error(error);
          self.loading = false;
        });
    },
    onPerPageChange(params) {
      this.updateParams({ perPage: params.currentPerPage });
      this.fetchRows();
    },
    getStatusBadgeClass(status) {
      if (status == "draft") {
        return "badge badge-primary";
      } else if (status == "open") {
        return "badge badge-success";
      } else if (status == "close") {
        return "badge badge-danger";
      } else if (status == "research") {
        return "badge badge-info";
      } else {
        return "";
      }
    },
    async getCandleForChart(item, button) {
      var self = this;
      this.loading = true;
      axios.get("/sanctum/csrf-cookie").then((response) => {
        axios
          .post("/api/test-strategy/get-candle-test", {
            selRows: item,
          })
          .then((response) => {
            if (response.status) {
              console.log("Вызвали алерт");
              this.loading = false;
            } else {
              console.log("Не работает");
              console.log(response.status);
              this.loading = false;
            }
          });
      });
    },

    deleteIdea: function (event, rows) {
      let self = this;
      this.loading = true;
      axios.get("/sanctum/csrf-cookie").then((response) => {
        axios
          .post("/api/ideas/delete", { selRows: this.selRows })
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