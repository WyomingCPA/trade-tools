<template>
  <div>
    <div class="alert alert-primary" role="alert">
      Запуск бота<code> nohup /var/www/trader/env/bin/python main.py & </code
      ><br />
      Проверка состояния процесса <code>ps -ef | grep python</code>
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
        <button v-on:click="successOrder">Успешная сделка</button>
      </div>
      <div slot="selected-row-actions">
        <button v-on:click="failOrder">Неудачная сделка</button>
      </div>
      <div slot="selected-row-actions">
        <button v-on:click="nothingOrder">Ничего</button>
      </div>
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
        <span v-else-if="props.column.field === 'created_at'">
          <span @click="getNote(props.row, props.row.id, $event.target)">
            {{ props.row.created_at }}</span
          >
        </span>
        <span v-else-if="props.column.field === 'graph'">
          <a
            target="_blank"
            class="btn btn-primary"
            :href="'/orders/order-chart/' + props.row.id"
            >View</a
          >
        </span>
        <span v-else-if="props.column.field === 'status'">
          <span :class="getStatusBadgeClass(props.row.status)">
            {{ props.row.status }}
          </span>
        </span>
      </template>
    </vue-good-table>
    <!-- Info modal -->
    <b-modal
      ref="my-modal"
      :id="infoModal.id"
      :title="infoModal.title"
      @hide="resetInfoModal"
    >
    <span v-html="infoModal.content"></span>
    </b-modal>
  </div>
</template>
<script>
// import the styles
import axios from "axios";
import "vue-good-table/dist/vue-good-table.css";

var qs = require("qs");

export default {
  name: "stock-rub",
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
          label: "Name",
          field: "name-instrument",
        },
        {
          label: "Figi",
          field: "figi",
        },
        {
          label: "Current Price",
          field: "current_price",
          type: "decimal",
        },
        {
          label: "Lot",
          field: "quantity",
        },
        {
          label: "STOP Orders",
          field: "stop-order-count",
          type: "number",
        },

        {
          label: "Время создания",
          field: "created_at",
        },
        {
          label: "График",
          field: "graph",
        },
        {
          label: "Название стратегий",
          field: "strategy_name",
        },
        {
          label: "Status",
          field: "status",
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
    successOrder: function (event, rows) {
      var self = this;
      this.loading = true;
      axios.get("/sanctum/csrf-cookie").then((response) => {
        axios
          .post("/api/orders/set-success", { selRows: this.selRows })
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
    failOrder: function (event, rows) {
      var self = this;
      this.loading = true;
      axios.get("/sanctum/csrf-cookie").then((response) => {
        axios
          .post("/api/orders/set-fail", { selRows: this.selRows })
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
    nothingOrder: function (event, rows) {
      var self = this;
      this.loading = true;
      axios.get("/sanctum/csrf-cookie").then((response) => {
        axios
          .post("/api/orders/set-nothing", { selRows: this.selRows })
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
    handleSubmit() {
      
    },
    fetchRows() {
      let self = this;
      this.loading = true;
      axios
        .request({
          method: "post",
          url: "/api/orders/index",
          params: this.serverParams,
          paramsSerializer: (params) => {
            return qs.stringify(params);
          },
        })
        .then((response) => {
          self.items = response.data.stocks;
          self.count = response.data.count;
          self.loading = false;
        })
        .catch((error) => {
          console.log(error);
          self.loading = false;
        });
    },
    onPerPageChange(params) {
      this.updateParams({ perPage: params.currentPerPage });
      this.fetchRows();
    },
    getStatusBadgeClass(status) {
      if (status == "empty") {
        return "badge badge-primary";
      } else if (status == "success") {
        return "badge badge-success";
      } else if (status == "fail") {
        return "badge badge-danger";
      } else if (status == "nothing") {
        return "badge badge-info";
      } else {
        return "";
      }
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