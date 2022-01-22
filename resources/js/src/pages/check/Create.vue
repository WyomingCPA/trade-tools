<template>
  <section class="advert">
    <div class="row">
      <div class="col-12 grid-margin">
        <div class="card">
          <div class="card-body">
            <h4 class="card-title">Счет</h4>
            <form class="form-sample" @submit.prevent="create">
              <p class="card-description">
                <strong>Создание счета</strong>
              </p>
              <div class="row">
                <div class="col-sm-10">
                  <b-form-group
                    horizontal
                    label="Название счета"
                    label-for="title-check"
                  >
                    <b-form-input
                      v-model="nameCheck"
                      type="text"
                      id="title-group"
                    ></b-form-input>
                  </b-form-group>
                </div>
                <div class="col-sm-10">
                  <b-form-group
                    horizontal
                    label="Тип счета"
                    label-for="type-check"
                  >
                  <b-form-select v-model="typeCheck" :options="options" size="sm" class="mt-3"></b-form-select>
                  </b-form-group>
                </div>
                <div class="col-sm-10">
                  <b-form-group
                    horizontal
                    label="Лимит счета"
                    label-for="limit-check"
                  >
                    <b-form-input
                      v-model="limitCheck"
                      type="number"
                      step="any"
                      id="limit-check"
                    ></b-form-input>
                  </b-form-group>
                </div>
                <div class="col-sm-10">
                  <b-form-group
                    horizontal
                    label="Проценты счета"
                    label-for="precent-check"
                  >
                    <b-form-input
                      v-model="precentCheck"
                      type="number"
                      step="any"
                      id="precent-group"
                    ></b-form-input>
                  </b-form-group>
                </div>
              </div>
              <div class="d-flex">
                <b-button type="submit" variant="success" class="mr-2"
                  >Создать</b-button
                >
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>
<script>
import axios from "axios";

export default {
  name: "group-create",
  components: {},
  data() {
    return {
      nameCheck: "",
      typeCheck: "",
      limitCheck: 0,
      precentCheck: 0,
      options: [
        { value: "credit", text: "Кредитная карта" },
        { value: "debet", text: "Дебетовая карта" },
        { value: "broker", text: "Брокерский счет" },
        { value: "deposit", text: "Депозит" },
        //'credit', 'debet', 'broker', 'deposit'
      ],
    };
  },
  methods: {
    async create() {
      axios.get("/sanctum/csrf-cookie").then((response) => {
        axios
          .post("/api/finance/store", {
            name_check: this.nameCheck,
            type_check: this.typeCheck,
            limit_check: this.limitCheck,
            precent_check: this.precentCheck,
          })
          .then((response) => {
            if (response.status) {
              this.$snotify.async(
                "Called with promise",
                "Success async",
                () =>
                  new Promise((resolve, reject) => {
                    setTimeout(
                      () =>
                        resolve({
                          title: "Success!!!",
                          test: reject,
                          body: "We got an example success!",
                          config: {
                            closeOnClick: true,
                          },
                        }),
                      2000
                    );
                  })
              );
              console.log("Вызвали алерт");
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
  mounted: function () {},
};
</script>