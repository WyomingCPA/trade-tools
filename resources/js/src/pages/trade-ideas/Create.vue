<template>
  <section class="advert">
    <div class="row">
      <div class="col-12 grid-margin">
        <div class="card">
          <div class="card-body">
            <h4 class="card-title">Идея</h4>
            <form class="form-sample" @submit.prevent="create">
              <p class="card-description">
                <strong>Создание торговой идей</strong>
              </p>
              <div class="row">
                <div class="col-sm-10">
                  <b-form-group
                    horizontal
                    label="Название идей"
                    label-for="title-idea"
                  >
                    <b-form-input
                      v-model="nameIdea"
                      type="text"
                      id="title-idea"
                    ></b-form-input>
                  </b-form-group>
                </div>
                <div class="col-sm-10">
                  <b-form-group
                    horizontal
                    label="Идея: Long или Short"
                    label-for="type-idea"
                  >
                    <b-form-select
                      v-model="typeIdea"
                      :options="typeIdeaOptions"
                      size="sm"
                      class="mt-3"
                    ></b-form-select>
                  </b-form-group>
                </div>
                <div class="col-sm-10">
                  <b-form-group
                    horizontal
                    label="Горизонт(время входа, указываем максимальное):"
                    label-for="horizont-idea"
                  >
                    <b-form-input
                      v-model="horizontIdea"
                      type="number"
                      step="any"
                      id="horizont-idea"
                    ></b-form-input>
                  </b-form-group>
                </div>
                <div class="col-sm-10">
                  <b-form-group
                    horizontal
                    label="Цель(указываем цель цены):"
                    label-for="aim-idea"
                  >
                    <b-form-input
                      v-model="aimIdea"
                      type="text"
                      id="aim-idea"
                    ></b-form-input>
                  </b-form-group>
                </div>
                <div class="col-sm-10">
                  <b-form-textarea
                    id="textarea"
                    v-model="descriptionIdea"
                    placeholder="Enter something..."
                    rows="10"
                  ></b-form-textarea>

                  <pre class="mt-3 mb-0">{{ descriptionIdea }}</pre>
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
      nameIdea: "",
      typeIdea: "",
      typeIdeaOptions: [
        { value: "long", text: "Long" },
        { value: "short", text: "Short" },
      ],
      limitCheck: 0,
      aimIdea: "",
      descriptionIdea: "",
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
          .post("/api/trade-ideas/store", {
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