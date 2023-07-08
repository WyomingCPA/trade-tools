<template>
  <va-card>
    <va-card-content>
      <div class="grid md:grid-cols-2 gap-6 mb-6">
        <va-input v-model="input" placeholder="Filter..." class="w-full" />

        <div class="flex flex-col gap-6">
          <va-checkbox v-model="isCustomFilteringFn" label="Use custom filtering function (looks for an exact match)" />
          <va-checkbox v-model="isDebounceInput" label="Debounce input" />
        </div>
      </div>
      <div class="flex flex-wrap gap-6 mb-6">
        <span v-for="(thing, index) in listPrice" :key="index"><a href="#" @click="filterPrice(index)"> {{ index }}({{
          thing }}) </a></span>
      </div>

      <va-data-table :items="items" :columns="columns" :filter="filter" :filter-method="customFilteringFn"
        @filtered="filteredCount = $event.items.length" :loading=loading>
        <template #cell(actions)="{ rowData }">
          <va-button @click="setLearn(rowData.id)">Повторил</va-button>
        </template>
      </va-data-table>

      <va-alert class="!mt-6" color="info" outline>
        Number of filtered items:
        <va-chip>{{ filteredCount }}</va-chip>
      </va-alert>
    </va-card-content>
  </va-card>
</template>
<script>
import { array } from '@amcharts/amcharts5'
import axios from 'axios'
import debounce from 'lodash/debounce.js'

var qs = require("qs");

export default {
  name: 'ProductAll',
  components: {},
  data() {
    const items = [];
    const input = '';
    const columns = [
      { key: 'name', sortable: true },
      { key: 'price', sortable: true },
      { key: 'count_learn', sortable: true },
      { key: 'actions', width: 80 },
    ]
    return {
      loading: false,
      items,
      columns,
      input,
      filter: input,
      isDebounceInput: false,
      isCustomFilteringFn: false,
      filteredCount: items.length,
      listPrice: Array,
      serverParams: {
        name: "",
      },
    }
  },
  methods: {
    filterExact(source) {
      if (this.filter === '') {
        return true
      }
      return source?.toString?.() === this.filter
    },

    updateFilter(filter) {
      this.filter = filter
    },

    debouncedUpdateFilter: debounce(function (filter) {
      this.updateFilter(filter)
    }, 600),

    filterPrice: function (param) {
      this.filter = param
      this.input = param
    },

    setLearn(id) {
      this.loading = true;
      this.idProduct = id;
      console.log(id);
      let self = this
      axios.get('/sanctum/csrf-cookie').then((response) => {
        axios
          .post('/api/product/set-learn', {
            id_product: self.idProduct,
          })
          .then((response) => {
            if (response.status) {
              console.log('Вызвали алерт')
              this.getWashProduct()
              this.loading = false
            } else {
              console.log('Не работает')
              console.log(response.status)
              this.loading = false
            }
          })
          .catch(function (error) {
            console.log(response)
            console.error(error)
            this.loading = false
          })
      })
    },
    fetchRows() {
      let self = this;
      this.loading = true;
      axios
        .request({
          method: "post",
          url: "/api/stock/all",
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
    getWashProduct() {
      let self = this
      axios
        .get('/api/product/smart')
        .then(function (response) {
          self.items = response.data.products;
          self.listPrice = response.data.price;
          console.log(response.data.groups);
          self.loading = false;
        })
        .catch(function (error) {
          console.error(error)
        })
    },
    resetInfoModal() {
      this.infoModal.title = ''
      this.infoModal.content = ''
    },
    async handleSubmit() {
      let self = this
      axios.get('/sanctum/csrf-cookie').then((response) => {
        axios
          .post('/api/group/set-subscriber', {
            count_subscriber: this.countSubscriber,
            id_group: self.idGroup,
          })
          .then((response) => {
            if (response.status) {
              console.log('Вызвали алерт')
              this.getGroups()
              this.$refs['my-modal'].hide()
            } else {
              console.log('Не работает')
              console.log(response.status)
            }
          })
          .catch(function (error) {
            console.log(response)
            console.error(error)
          })
      })
    },
  },
  computed: {
    customFilteringFn() {
      return this.isCustomFilteringFn ? this.filterExact : undefined
    },
  },
  watch: {
    input(newValue) {
      if (this.isDebounceInput) {
        this.debouncedUpdateFilter(newValue)
      } else {
        this.updateFilter(newValue)
      }
    },
  },
  mounted: function () {
    this.getWashProduct()
  },
  created() {
    this.fetchRows();
  },
}
</script>
<style lang="scss" scoped>
.table-crud {
  --va-form-element-default-width: 0;

  .va-input {
    display: block;
  }

  &__slot {
    th {
      vertical-align: middle;
    }
  }
}

.modal-crud {
  .va-input {
    display: block;
  }
}
</style>
  