<template>
  <div
    class="user-profile-sidebar"
    :class="{'show': shallShowActiveChatContactSidebar}"
  >
    <!-- Header -->
    <header
      v-if="contact"
      class="user-profile-header"
    >
      <span class="close-icon">
        <feather-icon
          icon="XIcon"
          @click="$emit('update:shall-show-active-chat-contact-sidebar', false)"
        />
      </span>

      <div class="header-profile-sidebar">
        <div class="avatar box-shadow-1 avatar-xl avatar-border">
          <b-avatar
            size="70"
            :src="contact.avatar"
          />
          <span
            class="avatar-status-xl"
            :class="`avatar-status-${contact.status}`"
          />
        </div>
        <h4 class="chat-user-name">
          {{ contact.fullName }}
        </h4>
        <span v-if="contact.role" class="user-post text-capitalize">{{ contact.role.slug }}</span>
      </div>
    </header>

    <!-- User Details -->
    <vue-perfect-scrollbar
      :settings="perfectScrollbarSettings"
      class="user-profile-sidebar-area scroll-area"
    >

      <!-- Personal Info -->
      <div class="personal-info">
        <h6 class="section-label mb-1 mt-3">
          Personal Information
        </h6>
        <ul class="list-unstyled">
          <li class="mb-1" v-if="contact.email">
            <feather-icon
              icon="MailIcon"
              size="16"
              class="mr-75"
            />
            <span class="align-middle">{{contact.email}}</span>
          </li>
          <li class="mb-1" v-if="contact.lastLogin">
            <feather-icon
              icon="CalendarIcon"
              size="16"
              class="mr-75"
            />
            <span class="align-middle">Last seen at {{contact.lastLogin | formatNow}}</span>
          </li>
        </ul>
      </div>
    </vue-perfect-scrollbar>
  </div>
</template>

<script>
import VuePerfectScrollbar from 'vue-perfect-scrollbar'
import { BAvatar } from 'bootstrap-vue'

export default {
  components: {
    // BSV
    BAvatar,

    // 3rd Party
    VuePerfectScrollbar,
  },
  props: {
    shallShowActiveChatContactSidebar: {
      type: Boolean,
      required: true,
    },
    contact: {
      type: Object,
      required: true,
    },
  },
  setup() {
    const perfectScrollbarSettings = {
      maxScrollbarLength: 150,
    }

    return {
      perfectScrollbarSettings,
    }
  },
}
</script>

<style>

</style>
