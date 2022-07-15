<template>
  <section class="dashboard">
    <div class="row">
      <div class="col-md-4 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <h4></h4>
          </div>
        </div>
      </div>
      <div class="col-md-8 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <div class="d-flex flex-row justify-content-between">
              <div>
                <h4 class="card-title">Быстрые действия</h4>
                <p>Статус</p>
              </div>
              <div class="align-self-center">
                <p class="text-muted">Действия</p>
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                <div class="preview-list">
                  <div class="preview-item border-bottom py-3">
                    <div class="preview-thumbnail">
                      <div class="preview-icon bg-primary rounded">
                        <i class="mdi mdi-chart-bar"></i>
                      </div>
                    </div>
                    <div class="preview-item-content d-sm-flex flex-grow">
                      <div class="flex-grow">
                        <h6 class="preview-subject">Общее количество свечей</h6>
                        <p class="mb-0">{{ count_all_candles }}</p>
                      </div>
                      <div class="mr-auto text-sm-right pt-2 pt-sm-0">
                        <p class="text-muted">{{ candles_last_ago }}</p>
                        <b-button
                          v-on:click="deleteAllCandles"
                          type="submit"
                          variant="success"
                          class="mr-2"
                          ><span v-show="!loading"> Удалить все </span>
                          <div
                            v-show="loading"
                            class="spinner-border spinner-border-sm"
                            role="status"
                          >
                            <span class="sr-only">Loading...</span>
                          </div>
                        </b-button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-4 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <div class="d-flex flex-row p-3">
              <div class="align-self-top">
                <p class="card-title mb-1 font-weight-bold">
                  Сделки за сегодня
                </p>
                <h3 class="mb-0">{{ today_open_orders }}</h3>
              </div>
              <div class="align-self-center flex-grow text-right">
                <i class="icon-lg mdi mdi-cash-multiple text-warning"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-4 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <div class="d-flex flex-row p-3">
              <div class="align-self-top">
                <p class="card-title mb-1 font-weight-bold">Сделки за Неделю</p>
                <h3 class="mb-0">{{ week_open_orders }}</h3>
              </div>
              <div class="align-self-center flex-grow text-right">
                <i class="icon-lg mdi mdi-cash-multiple text-warning"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-4 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <div class="d-flex flex-row p-3">
              <div class="align-self-top">
                <p class="card-title mb-1 font-weight-bold">Сделки За Месяц</p>
                <h3 class="mb-0">{{ month_open_orders }}</h3>
              </div>
              <div class="align-self-center flex-grow text-right">
                <i class="icon-lg mdi mdi-cash-multiple text-warning"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
import axios from "axios";

export default {
  name: "dashboard",
  components: {},
  data() {
    return {
      today_open_orders: { type: Number },
      week_open_orders: { type: Number },
      month_open_orders: { type: Number },
      count_all_candles: { type: Number },
      candles_last_ago: { type: String },
      dataUrl: { type: String },
      loading: false,
      id_order: 0,
      serverParams: {},
      items: [],
      loading: false,
    };
  },
  methods: {
    fetchData() {
      let self = this;
      this.loading = true;
      axios
        .get("/api/dashboard/index")
        .then(function (response) {
          self.today_open_orders = response.data.today_open_orders;
          self.week_open_orders = response.data.week_open_orders;
          self.month_open_orders = response.data.month_open_orders;
          self.count_all_candles = response.data.count_all_candles;
          self.candles_last_ago = response.data.candles_last_ago;
          
          self.loading = false;
          console.log(response.data.count_all_candles);
        })
        .catch(function (error) {
          console.error(error);
        });
    },
    async deleteAllCandles() {
      let self = this;
      this.loading = true;
      axios.get("/sanctum/csrf-cookie").then((response) => {
        axios
          .post("/api/dashboard/delete-all-candles", {
          })
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
  },
  created() {
    this.fetchData();
  },
};
</script>

<style scoped>
</style>