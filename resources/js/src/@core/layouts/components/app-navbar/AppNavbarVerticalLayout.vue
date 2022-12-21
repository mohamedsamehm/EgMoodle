<template>
  <div class="navbar-container d-flex content align-items-center">

    <!-- Nav Menu Toggler -->
    <ul class="nav navbar-nav d-xl-none">
      <li class="nav-item">
        <b-link
          class="nav-link"
          @click="toggleVerticalMenuActive"
        >
          <feather-icon
            icon="MenuIcon"
            size="21"
          />
        </b-link>
      </li>
    </ul>

    <!-- Left Col -->
		<!--
    <div class="bookmark-wrapper align-items-center flex-grow-1 d-none d-lg-flex">
      <bookmarks />
    </div>
		-->
		
    <b-navbar-nav class="nav align-items-center ml-auto">
      <!-- <locale /> -->
      <dark-Toggler class="d-none d-lg-block" />

      <!-- <search-bar /> -->
      <!-- <cart-dropdown /> -->
      <!-- <notification-dropdown /> -->
      <messages-dropdown :messages="state.messages" :unSeenMsgs="state.unSeenMsgs"/>

      <user-dropdown />
    </b-navbar-nav>
  </div>
</template>

<script>
import {
  BLink, BNavbarNav,
} from 'bootstrap-vue'
import {
  ref, onUnmounted, nextTick,
} from '@vue/composition-api'
import store from '@/store';
// import Bookmarks from './components/Bookmarks.vue'
// import Locale from './components/Locale.vue'
// import SearchBar from './components/SearchBar.vue'
import DarkToggler from './components/DarkToggler.vue'
// import CartDropdown from './components/CartDropdown.vue'
import MessagesDropdown from './components/MessagesDropdown.vue'
import NotificationDropdown from './components/NotificationDropdown.vue'
import UserDropdown from './components/UserDropdown.vue'
import useChat from './components/useChat'
import chatStoreModule from './components/chatStoreModule'
export default {
  components: {
    BLink,

    // Navbar Components
    BNavbarNav,
    // Bookmarks,
    // Locale,
    // SearchBar,
    DarkToggler,
    // CartDropdown,
    MessagesDropdown,
    NotificationDropdown,
    UserDropdown,
  },
  setup() {
    const CHAT_APP_STORE_MODULE_NAME = 'messages'

    // Register module
    if (!store.hasModule(CHAT_APP_STORE_MODULE_NAME)) store.registerModule(CHAT_APP_STORE_MODULE_NAME, chatStoreModule)

    // UnRegister on leave
    onUnmounted(() => {
      if (store.hasModule(CHAT_APP_STORE_MODULE_NAME)) store.unregisterModule(CHAT_APP_STORE_MODULE_NAME)
    })

    const { fetchChats, state } = useChat()

    fetchChats()

    return {
      state,
      fetchChats,
    }
  },
  mounted() {
    Echo.private(`chat.${this.userData.id}`).listen('MessageSend', (e) => {
      this.state.messages.push({
        id: e.message.id,
        fullName: e.fullName,
        message: e.message.message,
        avatar: e.message.avatar,
      })
      this.state.unSeenMsgs++;
    });
  },
  props: {
    toggleVerticalMenuActive: {
      type: Function,
      default: () => {},
    },
  },
  data() {
    return {
      userData: JSON.parse(localStorage.getItem('userData')),
    }
  },
}
</script>
