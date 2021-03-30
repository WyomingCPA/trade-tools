<template>
  <div>
    <vue-good-table
      @on-selected-rows-change="selectionChanged"
      :theme="nocturnal"
      :columns="columns"
      :rows="bonds"
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
      <div slot="selected-row-actions">
        <button v-on:click="hide">Hide</button>
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
        <span v-else-if="props.column.field === 'current_yield'">
          {{ props.row.current_yield }}
        </span>
        <span v-else-if="props.column.field === 'last_price'">
          {{ props.row.last_price }}
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
  props: ["bonds"],
  name: "bond-all-table",
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
        console.log(newTxt);
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
    columnFilterCY: function (data, filterString) {
      var x = parseInt(filterString);
      if (data != 0 && data != null) {
        var min_value = data.toString();
        min_value = parseFloat(min_value);
        console.log(min_value);
        if (x > 0) {
          return min_value >= x;
        }
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
          label: "Текущая доходность",
          field: 'current_yield',
          type: "number",
          filterOptions: {
            styleClass: "class1",
            enabled: true,
            placeholder: "Min.тек. доходность",
            filterDropdownItems: [6, 7, 8, 9, 10, 11, 12, 13],
            filterFn: this.columnFilterCY,
            trigger: "enter",
          },
        },
        {
          label: "Последняя цена",
          field: this.fealdFn,
          type: "number",
          filterOptions: {
            styleClass: "class2", // class to be added to the parent th element
            enabled: true, // enable filter for this column
            placeholder: "Max. % от номинала", // placeholder for filter input
            filterDropdownItems: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10], // dropdown (with selected values) instead of text input
            filterFn: this.columnFilterFn, //custom filter function that
            trigger: "enter", //only trigger on enter not on keyup
          },
        },
      ],
    };
  },
};
</script>