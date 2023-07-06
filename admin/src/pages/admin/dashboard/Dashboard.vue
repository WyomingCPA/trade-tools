<template>
  <div class="dashboard">
    <dashboard-info-block />
  </div>
</template>

<script setup lang="ts">
  import { ref } from 'vue'
  import DashboardInfoBlock from './DashboardInfoBlock.vue'
  const dashboardMap = ref()

  function addAddressToMap({ city, country }: { city: { text: string }; country: string }) {
    dashboardMap.value.addAddress({ city: city.text, country })
  }
</script>

<style lang="scss">
  .row-equal .flex {
    .va-card {
      height: 100%;
    }
  }

  .dashboard {
    .va-card {
      margin-bottom: 0 !important;
      &__title {
        display: flex;
        justify-content: space-between;
      }
    }
  }
</style>
