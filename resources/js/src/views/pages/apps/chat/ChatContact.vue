<template>
  <component
    :is="tag"
    v-on="$listeners"
  >
    <b-avatar
      size="42"
      class="badge-minimal "
      :src="user.avatar"
      :badge="isChatContact"
      :badge-variant="resolveAvatarBadgeVariant(user.status)"
      variant="light-primary"
    />
    <div class="chat-info flex-grow-1">
      <h5 class="mb-0 text-capitalize">
        {{ user.fullName }}
      </h5>
      <p class="card-text text-truncate">
        {{ isChatContact ? user.message : user.role.slug }}
      </p>
    </div>
    <div
      v-if="isChatContact"
      class="chat-meta text-nowrap"
    >
      <small class="float-right mb-25 chat-time">{{ formatDateToMonthShort(user.msgDate, { hour: 'numeric', minute: 'numeric' }) }}</small>
      <b-badge
        v-if="user.unSeenMsg > 0"
        pill
        variant="primary"
        class="float-right"
      >
        {{ user.unSeenMsg }}
      </b-badge>
    </div>
  </component>
</template>

<script>
import { BAvatar, BBadge } from 'bootstrap-vue'
import { formatDateToMonthShort } from '@core/utils/filter'
import useChat from './useChat'

export default {
  components: {
    BAvatar,
    BBadge,
  },
  props: {
    tag: {
      type: String,
      default: 'div',
    },
    user: {
      type: Object,
      required: true,
    },
    isChatContact: {
      type: Boolean,
      dedfault: false,
    },
  },
  setup() {
    const { resolveAvatarBadgeVariant } = useChat()
    return { formatDateToMonthShort, resolveAvatarBadgeVariant }
  },
}
</script>

<style>

</style>
