<template>
  <section class="advert">
    <div class="row">
      <div class="col-12 grid-margin">
        <div class="card">
          <div class="card-body">
            <h4 class="card-title">Идея</h4>
            <form class="form-sample" @submit.prevent="update">
              <p class="card-description">
                <strong>Редактирование торговой идей</strong>
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
                      v-model="limitDayIdea"
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
                    <editor
                      id="description-idea"
                      api-key="no-api-key"
                      v-model="descriptionIdea"
                      value="descriptionIdea"
                      :init="{
                        height: 700,
                        menubar: true,
                        plugins: [
                          'advlist autolink lists link image charmap print preview anchor',
                          'searchreplace visualblocks code fullscreen',
                          'insertdatetime media table paste code help wordcount',
                          'codesample toc autosave',
                        ],
                        toolbar:
                          'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image \
                          formatselect | bold italic backcolor | \
           alignleft aligncenter alignright alignjustify | \
           bullist numlist outdent indent | removeformat | help',
                      }"
                    />
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
                  ><span v-show="!loading"> Обновить </span>
                  <div
                    v-show="loading"
                    class="spinner-border spinner-border-sm"
                    role="status"
                  >
                    <span class="sr-only">Loading...</span>
                  </div>
                </b-button>
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
import Editor from "@tinymce/tinymce-vue";

export default {
  name: "group-create",
  components: {
    editor: Editor,
  },
  data() {
    return {
      loading: false,
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
        .get("/api/ideas/edit/" + this.$route.params.id)
        .then(function (response) {
          console.log(response.data.idea.figi);
          self.figiTools = response.data.idea.figi;
          self.nameIdea = response.data.idea.name;
          self.typeIdea = response.data.idea.action;
          self.limitDayIdea = response.data.idea.min_period;
          self.aimIdea = response.data.idea.aim_price;
          self.descriptionIdea = response.data.idea.description;
          self.statusIdea = response.data.idea.status;
        })
        .catch(function (error) {
          console.error(error);
        });
    },
    async update() {
      let self = this;
      this.loading = true;
      axios.get("/sanctum/csrf-cookie").then((response) => {
        axios
          .post("/api/ideas/update", {
            idea_id: this.$route.params.id,
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
              this.makeToast("success");
              self.loading = false;
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
    makeToast(variant = null) {
      this.$bvToast.toast("Данные успешно обновлены", {
        title: `${variant || "default"}`,
        variant: variant,
        solid: true,
      });
    },
  },
  mounted: function () {
    this.getPostData();
  },
};
</script>