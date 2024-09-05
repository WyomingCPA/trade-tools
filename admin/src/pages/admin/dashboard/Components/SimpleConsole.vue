<template>
  <div class="va-table-responsive">
    <table class="va-table va-table--hoverable">
      <thead>
        <tr>
          <th>Id</th>
          <th>Message</th>
          <th>Country</th>
          <th>Time</th>
        </tr>
      </thead>
      <tbody>
        <tr
          v-for="event in events"
          :key="event.id"
        >
          <td>{{ event.id }}</td>
          <td>{{ event.message }}</td>
          <td>{{ event.created_at }}</td>
          <td>
            <VaBadge
              :text="event.id"
              :color="danger"
            />
          </td>
        </tr>
      </tbody>
    </table>
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
                value.data = JSON.parse(value.data);
                
  
                self.events.unshift(value);
              }
            });
          })
          .catch(function (error) {
            console.error(error);
          });
      },
    },
    mounted: function () {
      this.timer = setInterval(() => {
        this.getConsoleEvent();
      }, 5000);
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