<template>
  <b-card no-body>
    <b-card-body class="d-flex justify-content-between align-items-center">
      <div class="truncate">
        <h2 class="mb-25 font-weight-bolder">
          {{ statistic }}
        </h2>
        <span>{{ statisticTitle }}</span>
      </div>
      <b-avatar
        :variant="`light-${color}`"
        size="45"
      >
        <feather-icon
          size="21"
          :icon="icon"
        />
      </b-avatar>
    </b-card-body>
    <b-card-footer v-if="infoTitle !== ''" class="border-top py-1" :class="`text-${color}`">
      <feather-icon
        size="13"
        :icon="infoColor"
        class="mr-icon"
      />
      <small>{{ infoTitle }}</small>
    </b-card-footer>
  </b-card>
</template>

<script>
import { BCard, BCardBody, BAvatar, BCardFooter } from 'bootstrap-vue'

export default {
  components: {
    BCard,
    BCardBody,
    BAvatar,
    BCardFooter
  },
  props: {
    icon: {
      type: String,
      required: true,
    },
    statistic: {
      type: [Number, String],
      required: true,
    },
    statisticTitle: {
      type: String,
      default: '',
    },
    color: {
      type: String,
      default: 'primary',
    },
    infoTitle: {
      type: String,
      default: '',
    },
    infoColor: {
      type: String,
      default: 'primary',
    },
  },
}
</script>
<style scoped>
.mr-icon {
  margin-right: 6px;
}
</style>