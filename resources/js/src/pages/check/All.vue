<template>
  <section class="tables">
    <div class="page-header">
      <h3 class="page-title">Bootstrap Table</h3>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="javascript:void(0);">Table</a>
          </li>
          <li class="breadcrumb-item active" aria-current="page">Все группы</li>
        </ol>
      </nav>
    </div>
    <div class="row">
      <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <h4 class="card-title"></h4>
            <b-table responsive :items="items" :fields="fields">
              <template #cell(actions)="row">
                <b-button
                  size="sm"
                  @click="setBalance(row.item, row.index, $event.target)"
                  class="mr-1"
                >
                  Задать баланс
                </b-button>
                <b-button
                  size="sm"
                  @click="deleteCheck(row.item, row.index, $event.target)"
                  class="mr-1"
                >
                  Удалить счет
                </b-button>
              </template>
              <!-- could also be a footer field slot instead -->
              <template slot="bottom-row">
                <td>Всего</td>
                <td></td>
                <!-- this is a computed prop that adds up all the expenses in the visible rows -->
                <td>{{ totalBalance }}</td>
                <td></td>
                <td>{{ totalDifference }}</td>
                <td></td>
                <td>{{ totalCalculateSummPrecent }}</td>
              </template>
            </b-table>
            <!-- Info modal -->
            <b-modal
              ref="my-modal"
              :id="infoModal.id"
              :title="infoModal.title"
              @hide="resetInfoModal"
              @ok="handleOk"
            >
              <form ref="form" @submit.stop.prevent="handleSubmit">
                <b-form-group
                  label="Новый баланс"
                  label-for="name-input"
                  invalid-feedback="Name is required"
                >
                  <b-form-input
                    id="name-input"
                    v-model="countBalance"
                    required
                  ></b-form-input>
                </b-form-group>
              </form>
            </b-modal>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>
<script>
import axios from "axios";

export default {
  name: "check-all",
  components: {},
  data() {
    return {
      countBalance: 0,
      idCheck: "",
      items: Array,
      infoModal: {
        id: "info-modal",
        title: "",
        content: "",
      },
      fields: [
        {
          key: "name",
          label: "Название инструмента",
          sortable: true,
        },
        {
          key: "type",
          label: "Тип инструмента",
          sortable: true,
        },
        {
          key: "balances.balance",
          label: "Баланс",
          sortable: true,
        },
        {
          key: "limit",
          label: "Лимит",
          sortable: true,
          // Variant applies to the whole column, including the header and footer
        },
        {
          key: "difference_limit",
          label: "Разница",
          sortable: true,
        },
        {
          key: "precent",
          label: "Проценты",
          sortable: true,
        },
        {
          key: "precent_month_calculate",
          label: "Проценты за месяц",
          sortable: true,
        },
        {
          key: "balances.updated_at",
          label: "Последнее обновление",
          sortable: true,
          // Variant applies to the whole column, including the header and footer
        },
        { key: "actions", label: "Actions" },
      ],
    };
  },
  methods: {
    handleOk(bvModalEvt) {
      // Prevent modal from closing
      bvModalEvt.preventDefault();
      // Trigger submit handler
      this.handleSubmit();
    },
    setBalance(item, index, button) {
      this.infoModal.title = item.name;
      this.idCheck = item.id;
      this.$root.$emit("bv::show::modal", this.infoModal.id, button);
    },
    deleteCheck(item, index, button) {
      this.idCheck = item.id;
      let self = this;
      axios.get("/sanctum/csrf-cookie").then((response) => {
        axios
          .post("/api/finance/delete", {
            id_check: self.idCheck,
          })
          .then((response) => {
            if (response.status) {
              console.log("Вызвали алерт");
              this.getCheck();
            } else {
              console.log("Не работает");
              console.log(response.status);
            }
          })
          .catch(function (error) {
            console.log(response);
            console.error(error);
          });
      });
    },
    getCheck() {
      let self = this;
      axios
        .get("/api/finance")
        .then(function (response) {
          self.items = response.data.checks;
          console.log(response.data.checks);
        })
        .catch(function (error) {
          console.error(error);
        });
    },
    resetInfoModal() {
      this.infoModal.title = "";
      this.infoModal.content = "";
    },
    async handleSubmit() {
      let self = this;
      axios.get("/sanctum/csrf-cookie").then((response) => {
        axios
          .post("/api/finance/set-balance", {
            set_balance: this.countBalance,
            id_check: self.idCheck,
          })
          .then((response) => {
            if (response.status) {
              console.log("Вызвали алерт");
              this.getCheck();
              this.$refs["my-modal"].hide();
            } else {
              console.log("Не работает");
              console.log(response.status);
            }
          })
          .catch(function (error) {
            console.log(response);
            console.error(error);
          });
      });
    },
  },
  mounted: function () {
    this.getCheck();
  },
  computed: {
    totalBalance() {
      let count = 0;
      this.items.forEach((value, index) => {
        if (value.balances != null && value.type != "credit") {
          count += value.balances["balance"];
        }
      });
      return count;
    },
    totalDifference() {
      let count = 0;
      this.items.forEach((value, index) => {
        console.log(value);
        if (value.difference_limit != null) {
          count += value.difference_limit;
        }
      });
      return count;
    },
    totalCalculateSummPrecent() {
      let count = 0;
      this.items.forEach((value, index) => {
        console.log(value);
        if (value.precent_month_calculate != null) {
          count += value.precent_month_calculate;
        }
      });
      return count;
    },
  },
};
</script>