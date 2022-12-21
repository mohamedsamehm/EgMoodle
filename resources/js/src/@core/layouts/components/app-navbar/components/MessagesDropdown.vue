<template>
  <b-nav-item-dropdown
    class="dropdown-notification mr-25"
    menu-class="dropdown-menu-media"
    right
  >
    <template #button-content>
      <feather-icon
        :badge="unSeenMsgs"
        badge-classes="bg-danger"
        class="text-body"
        icon="MessageCircleIcon"
        size="21"
      />
    </template>

    <!-- Header -->
    <li class="dropdown-menu-header">
      <div class="dropdown-header d-flex">
        <h4 class="notification-title mb-0 mr-auto">
          Messages
        </h4>
        <b-badge
          pill
          variant="light-primary"
        >
          {{unSeenMsgs}} New
        </b-badge>
      </div>
    </li>
    <!-- Messages -->

    <vue-perfect-scrollbar
      :settings="perfectScrollbarSettings"
      class="scrollable-container media-list scroll-area messages"
      tagname="li"
    >
      <b-link
        v-for="(message, index) in messages"
        :key="index"
      >
        <b-media>
          <template #aside>
            <b-avatar
              size="32"
              :src="message.avatar"
              variant="light-primary"
            />
          </template>
          <p class="media-heading">
            <span class="font-weight-bolder text-capitalize" v-if="message.fullName">
              {{ message.fullName }} 
            </span>
            <span class="font-weight-bolder text-capitalize" v-else>
              {{ message.title }} 
            </span>
          </p>
          <small class="notification-text">{{ message.message }}</small>
        </b-media>
      </b-link>

    </vue-perfect-scrollbar>

    <!-- Cart Footer -->
    <li class="dropdown-menu-footer"><b-button
      :to="{ path: '/apps/chat/'}"
      v-ripple.400="'rgba(255, 255, 255, 0.15)'"
      variant="primary"
      block
    >View all messages</b-button>
    </li>
  </b-nav-item-dropdown>
</template>

<script>
import {
  BNavItemDropdown, BBadge, BMedia, BLink, BAvatar, BButton, BFormCheckbox,
} from 'bootstrap-vue'
import VuePerfectScrollbar from 'vue-perfect-scrollbar'
import Ripple from 'vue-ripple-directive'
export default {
  components: {
    BNavItemDropdown,
    BBadge,
    BMedia,
    BLink,
    BAvatar,
    VuePerfectScrollbar,
    BButton,
    BFormCheckbox,
  },
  directives: {
    Ripple,
  },
  setup() {
    const perfectScrollbarSettings = {
      maxScrollbarLength: 60,
      wheelPropagation: false,
    }
    return {
      perfectScrollbarSettings,
    }
  },
  props: {
    messages: {
      type: Array,
    },
    unSeenMsgs: {
      type: Number
    }
  },
}
</script>

<style>

</style>
