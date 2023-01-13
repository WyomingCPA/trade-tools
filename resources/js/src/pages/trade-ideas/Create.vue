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
                    label="Figi инструмента"
                    label-for="figi-tools"
                  >
                    <b-form-input
                      v-model="figiTools"
                      type="text"
                      id="figi-tools"
                    ></b-form-input>
                  </b-form-group>
                </div>
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
                    label="Горизонт(время входа, указываем максимальное в днях):"
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
                  <b-form-group
                    horizontal
                    label="Описание идей:"
                    label-for="description-idea"
                  >
                    <b-form-textarea
                      id="description-idea"
                      v-model="descriptionIdea"
                      placeholder="Enter something..."
                      rows="10"
                    ></b-form-textarea>
                  </b-form-group>
                </div>
                <div class="col-sm-10">
                  <b-form-group
                    horizontal
                    label="Статус"
                    label-for="status-idea"
                  >
                    <b-form-select
                      v-model="statusIdea"
                      :options="statusIdeaOptions"
                      size="sm"
                      class="mt-3"
                    ></b-form-select>
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
      nameIdea: "",
      figiTools: "",
      typeIdea: "",
      typeIdeaOptions: [
        { value: "long", text: "Long" },
        { value: "short", text: "Short" },
      ],
      limitDayIdea: 0,
      aimIdea: "",
      descriptionIdea: "",
      statusIdea: "",
      statusIdeaOptions: [
        //'draft', 'research', 'open', 'close'
        { value: "draft", text: "Черновик" },
        { value: "research", text: "Исследование" },
        { value: "open", text: "Open" },
        { value: "close", text: "Close" },
      ],
    };
  },
  methods: {
    getPostData() {
      let self = this;
      axios
        .get("/api/posts/create")
        .then(function (response) {
          self.category = response.data.categorys;
          self.optionsCategory = self.category.map(function (category) {
            return { value: category.id, text: category.title };
          });
        })
        .catch(function (error) {
          console.error(error);
        });
    },
    async create() {
      let self = this;
      axios.get("/sanctum/csrf-cookie").then((response) => {
        axios
          .post("/api/ideas/store", {
            figi_idea: self.figiTools,
            name_idea: self.nameIdea,
            type_idea: self.typeIdea,
            limit_day_idea: self.limitDayIdea,
            aim_idea: self.aimIdea,
            description_idea: self.descriptionIdea,
            status_idea: self.statusIdea,
          })
          .then((response) => {
            if (response.status) {
              console.log("Вызвали алерт");
              this.$router.push({ path: "/trade-ideas/index" });
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