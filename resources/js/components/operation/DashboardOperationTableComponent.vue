<template>
  <div>
    <vue-good-table
      @on-selected-rows-change="selectionChanged"
      :theme="nocturnal"
      :columns="columns"
      :rows="operations"
      :sort-options="{
        enabled: true,
      }"
      :line-numbers="true"
      :pagination-options="{
        enabled: true,
        mode: 'records',
        perPage: 30,
        position: 'top',
        perPageDropdown: [10, 30, 100],
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
      <div slot="selected-row-actions">
        <button v-on:click="hide">Hide</button>
      </div>
      <template slot="table-row" slot-scope="props">
        <span v-if="props.column.field === 'status'">
          {{ props.row.status }}
        </span>
        <span v-else-if="props.column.field === 'figi'">
          {{ props.row.figi }}
        </span>
        <span v-else-if="props.column.field === 'payment'">
          {{ props.row.payment }}
        </span>
        <span v-else-if="props.column.field === 'price'">
          {{ props.row.price }}
        </span>
        <span v-else-if="props.column.field === 'currency'">
          {{ props.row.currency }}
        </span>
        <span v-else-if="props.column.field === 'instrumentType'">
          {{ props.row.instrumentType }}
        </span>
        <span v-else-if="props.column.field === 'date'">
          {{ props.row.date }}
        </span>
        <span v-else-if="props.column.field === 'operationType'">
          {{ props.row.operationType }}
        </span>
      </template>
    </vue-good-table>
  </div>
</template>
<script>
import VueGoodTablePlugin from "vue-good-table";

// import the styles
import "vue-good-table/dist/vue-good-table.css";
import each from "lodash.foreach";
Vue.use(VueGoodTablePlugin);

export default {
  props: ["operations"],
  name: "dashboard-operations",
  methods: {
    selectionChanged: function (params) {
      this.selRows = params.selectedRows;
    },
    hide: function (event, rows) {
      var self = this;
      axios.post("trash", { selRows: this.selRows }).then((response) => {
        window.location.href = "all";
      });
    },
    favorite: function (event, rows) {
      var self = this;
      axios.post("favorite", { selRows: this.selRows }).then((response) => {
        window.location.href = "all";
      });
    },
    columnFilterFn: function (data, filterString) {
      var x = parseInt(filterString);
      if (data != 0 && data != null) {
        var newTxt = data.toString().split("(");
        var precent;
        for (var i = 1; i < newTxt.length; i++) {
          //console.log(newTxt[i].split(")")[0]);
          precent = newTxt[i].split(")")[0];
        }
        precent = parseFloat(precent);
        if (x > 0) {
          return precent <= x && precent >= 0;
        }
        if (x <= 0) return precent <= x;
      }
    },
    fealdFn(rowObj) {
      var nominal = parseInt(rowObj.nominal);
      var lastPrice = parseInt(rowObj.last_price);
      var precent = 0;
      if (lastPrice > nominal) {
        precent = ((lastPrice - nominal) / nominal) * 100;
        var lastPriceString = lastPrice.toString();
        var precentString = precent.toFixed(2).toString();
        var result = lastPriceString + "(" + precentString + "%" + ")";
        return result;
      } else if (lastPrice < nominal && lastPrice != 0) {
        precent = ((nominal - lastPrice) / nominal) * 100;
        var lastPriceString = lastPrice.toString();
        var precentString = precent.toFixed(2).toString();
        var result = lastPriceString + "(" + -precentString + "%" + ")";
        return result;
      } else {
        return lastPrice;
      }
    },
  },
  data() {
    return {
      columns: [
        {
          label: "status",
          field: "status",
        },
        {
          label: "figi",
          field: "figi",
        },
        {
          label: "payment",
          field: "payment",
        },
        {
          label: "price",
          field: "price",
        },
        {
          label: "currency",
          field: "currency",
        },
        {
          label: "instrumentType",
          field: "instrumentType",
        },
        {
          label: "date",
          field: "date",
        },
        {
          label: "operationType",
          field: "operationType",
        },
      ],
    };
  },
};
</script>