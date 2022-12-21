<template>
  <div
    class="chat-profile-sidebar"
    :class="{'show': shallShowUserProfileSidebar}"
  >
    <!-- Header -->
    <header class="chat-profile-header">
      <span class="close-icon">
        <feather-icon
          icon="XIcon"
          @click="$emit('close-sidebar')"
        />
      </span>

      <div class="header-profile-sidebar">
        <div class="mb-1 avatar-xl avatar-border">
          <b-avatar
            size="70"
            :src="profileUserData.avatar"
            class="badge-minimal"
            badge
            variant="light-primary"
            :badge-variant="resolveAvatarBadgeVariant(profileUserData.status)"
          >
            <feather-icon
              v-if="!profileUserData.fullName"
              icon="UserIcon"
              size="22"
            />
          </b-avatar>
        </div>
        <h4 class="chat-user-name">
          {{ profileUserData.fullName }}
        </h4>
        <span class="user-post text-capitalize">{{ profileUserData.role.name }}</span>
      </div>
    </header>

    <!-- User Details -->
    <vue-perfect-scrollbar
      :settings="perfectScrollbarSettings"
      class="profile-sidebar-area scroll-area"
    >

      <!-- Status -->
      <h6 class="section-label mb-1 mt-3">
        Status
      </h6>
      <b-form-radio-group
        id="user-status-options"
        v-model="profileUserData.status"
        stacked
      >
        <!-- :options="userStatusOptions" -->
        <b-form-radio
          v-for="option in userStatusOptions"
          :key="option.value"
          :value="option.value"
          :class="`custom-control-${option.radioVariant}`"
          @change="changeStatus"
        >
          {{ option.text }}
        </b-form-radio>
      </b-form-radio-group>
      <div class="mt-3">
        <b-button variant="primary" @click="logout">
          Logout
        </b-button>
      </div>
    </vue-perfect-scrollbar>
  </div>
</template>

<script>
import {
  BAvatar, BFormTextarea, BFormRadioGroup, BFormRadio, BFormCheckbox, BButton,
} from 'bootstrap-vue'
import VuePerfectScrollbar from 'vue-perfect-scrollbar'
import useChat from './useChat'
import { initialAbility } from '@/libs/acl/config';
import useJwt from '@/auth/jwt/useJwt';
import axios from 'axios';
export default {
  components: {
    BAvatar,
    BFormTextarea,
    BFormRadioGroup,
    BFormRadio,
    BFormCheckbox,
    BButton,
    VuePerfectScrollbar,
  },
  props: {
    shallShowUserProfileSidebar: {
      type: Boolean,
      required: true,
    },
    profileUserData: {
      type: Object,
      required: true,
    },
  },
  setup() {
    const perfectScrollbarSettings = {
      maxScrollbarLength: 150,
    }

    const { resolveAvatarBadgeVariant } = useChat()
    const userStatusOptions = [
      { text: 'Active', value: 'online', radioVariant: 'success' },
      { text: 'Do Not Disturb', value: 'busy', radioVariant: 'danger' },
      { text: 'Away', value: 'away', radioVariant: 'warning' },
      { text: 'Offline', value: 'offline', radioVariant: 'secondary' },
    ]

    return {
      perfectScrollbarSettings,
      userStatusOptions,
      resolveAvatarBadgeVariant,
    }
  },
  methods: {
    logout() {
      useJwt
        .logout({
          action: 'logout',
        })
        .then(response => {
          // Remove userData from localStorage
          // ? You just removed token from localStorage. If you like, you can also make API call to backend to blacklist used token
          localStorage.removeItem(useJwt.jwtConfig.storageTokenKeyName)
          localStorage.removeItem(useJwt.jwtConfig.storageRefreshTokenKeyName)
          // Remove userData from localStorage
          localStorage.removeItem('userData')
          // Reset ability
          this.$ability.update(initialAbility)
          // Redirect to login page
          this.$router.push({ name: 'login' })					
        })
        .catch(error => {
          console.log('error', error)
        })
    },
    changeStatus() {
      try {
        axios.post('/api/changeStatus/', {'status' : this.profileUserData.status}).then(res => {
          if(res) {
            let userData = JSON.parse(localStorage.getItem('userData'));
            userData.status = this.profileUserData.status;
            localStorage.setItem('userData', JSON.stringify(userData));
          }
        }).catch(err => {
          console.log(err);
        });
      } catch (error) {
        console.log(error);
      }
    }
  }
}
</script>

<style lang="scss" scoped>
#user-status-options ::v-deep {
  .custom-radio {
    margin-bottom: 1rem;
  }
}
</style>
