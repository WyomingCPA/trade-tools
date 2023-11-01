<template>
  
    <div class="list-wrapper">
      <div class="preview-list">
        <div
          class="preview-item border-bottom py-3"
          v-for="event in events"
          :key="event.id"
        >
          <div class="preview-item-content d-flex flex-grow">
            <div class="flex-grow">
              <div class="d-sm-flex justify-content-between">
                <div class="d-flex">
                  <p class="text-small">{{ event.message }}</p>
                  <p class="text-small text-muted border-right pr-3">
                    {{ event.created_at }}
                  </p>
                  <b-button
                    v-b-modal="'modal-' + event.id"
                    type="submit"
                    variant="success"
                    class="mr-2"
                    ><span> View </span>
                  </b-button>
                  <b-modal
                    size="lg"
                    :id="'modal-' + event.id"
                    title=" Modal"
                    busy
                  >
                    <div class="table-responsive">
                      <table class="table">
                        <thead>
                          <tr>
                            <th scope="col">Figi</th>
                            <th scope="col">RSI</th>
                            <th scope="col">RSI Strategy</th>
                            <th scope="col">MACD</th>
                            <th scope="col">Last Supertrend</th>
                            <th scope="col">SuperTrend Strategy</th>
                            <th scope="col">Time</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr v-for="item in event.data" :key="item.id">
                            <th scope="col">{{ item.figi }}</th>
                            <th scope="col">{{ item.RSI }}</th>
                            <th scope="col">{{ item.rsi_strategy_info }}</th>
                            <th scope="col">{{ item.MACD }}</th>
                            <th scope="col">{{ item.last_row_supertrend }}</th>
                            <th scope="col">
                              {{ item.supertend_strategy_info }}
                            </th>
                            <th scope="col">{{ item.time }}</th>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </b-modal>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </template>
  <script>
  import axios from "axios";
  
  export default {
    name: "mini-console",
    data() {
      return {
        timer: null,
        events: [],
      };
    },
    methods: {
      getConsoleEvent() {
        let self = this;
        this.loading = true;
        axios
          .get("/api/console/last-events-console")
          .then(function (response) {
            response.data.events.map(function (value, key) {
              var exists = self.events.some(function (field) {                
                return field.id === value.id;
              });
              if (!exists) {
                console.log(value);
                //value.data = JSON.parse(value.data);
                
  
                self.events.unshift(value.values());
              }
            });
          })
          .catch(function (error) {
            console.error(error);
          });
      },
    },
    mounted: function () {
      //this.timer = setInterval(() => {
      //  this.getConsoleEvent();
      //}, 5000);
    },
    beforeDestroy() {
      clearInterval(this.timer);
    },
  };
  </script>
  <style scoped>
  .preview-list {
    height: 400px;
    overflow-y: scroll;
  }
  </style>