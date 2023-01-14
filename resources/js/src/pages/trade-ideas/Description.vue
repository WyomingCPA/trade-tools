<template>
  <div class="row g-5">
    <div class="col-md-8">
      <article class="blog-post">
        {{ descriptionIdea }}
      </article>
    </div>
  </div>
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
      descriptionIdea: "",
    };
  },
  methods: {
    getDescriptionData() {
      let self = this;
      axios
        .get("/api/ideas/description/" + this.$route.params.id)
        .then(function (response) {
          self.descriptionIdea = response.data.description;
        })
        .catch(function (error) {
          console.error(error);
        });
    },
  },
  mounted: function () {
    this.getDescriptionData();
  },
};
</script>