<template>
  <!-- Need to add height inherit because Vue 2 don't support multiple root ele -->
  <div style="height: inherit">
    <div
      class="body-content-overlay"
      :class="{'show': shallShowUserProfileSidebar || shallShowActiveChatContactSidebar || mqShallShowLeftSidebar}"
      @click="mqShallShowLeftSidebar=shallShowActiveChatContactSidebar=shallShowUserProfileSidebar=false"
    />
    <!-- Main Area -->
    <section class="chat-app-window">
      <!-- Start Chat Logo -->
      <div
        v-if="Object.keys(state.activeChat.contact).length == 0"
        class="start-chat-area"
      >
        <div class="mb-1 start-chat-icon">
          <feather-icon
            icon="MessageSquareIcon"
            size="56"
          />
        </div>
        <h4
          class="sidebar-toggle start-chat-text"
          @click="startConversation"
        >
          Start Conversation
        </h4>
      </div>

      <!-- Chat Content -->
      <div
        v-else
        class="active-chat"
      >
        <!-- Chat Navbar -->
        <div class="chat-navbar">
          <header class="chat-header">

            <!-- Avatar & Name -->
            <div class="d-flex align-items-center">

              <!-- Toggle Icon -->
              <div class="sidebar-toggle d-block d-lg-none mr-1">
                <feather-icon
                  icon="MenuIcon"
                  class="cursor-pointer"
                  size="21"
                  @click="mqShallShowLeftSidebar = true"
                />
              </div>

              <b-avatar
                size="36"
                :src="state.activeChat.contact.avatar"
                class="mr-1 cursor-pointer badge-minimal"
                badge
                :badge-variant="resolveAvatarBadgeVariant(state.activeChat.contact.status)"
                @click.native="shallShowActiveChatContactSidebar=true"
              />
              <h6 class="mb-0 text-capitalize">
                {{ state.activeChat.contact.fullName }}
                <p v-if="typing" class="m-0"><small class="d-block">Typing...</small></p>
              </h6>
            </div>

            <!-- Contact Actions -->
            <div class="d-flex align-items-center">
              <div class="dropdown">
                <b-dropdown
                  variant="link"
                  no-caret
                  toggle-class="p-0"
                  right
                >
                  <template #button-content>
                    <feather-icon
                      icon="MoreVerticalIcon"
                      size="17"
                      class="align-middle text-body"
                    />
                  </template>
                  <b-dropdown-item @click="clearAll(state.activeChat.contact.id)">
                    Clear Chat
                  </b-dropdown-item>
                </b-dropdown>
              </div>
            </div>
          </header>
        </div>

        <!-- User Chat Area -->
        <vue-perfect-scrollbar
          ref="refChatLogPS"
          :settings="perfectScrollbarSettings"
          class="user-chats scroll-area"
        >
          <chat-log :chat-data="state.activeChat" />
        </vue-perfect-scrollbar>

        <!-- Message Input -->
        <b-form
          class="chat-app-form"
          @submit.prevent="sendMessage"
        >
          <b-input-group class="input-group-merge form-send-message mr-1">
            <b-form-input
              v-model="chatInputMessage"
              placeholder="Enter your message"
              @keydown="typingEvent(state.activeChat.contact.id)"
            />
          </b-input-group>
          <b-button variant="primary" type="submit">Send</b-button>
        </b-form>
      </div>
    </section>
    <!-- Active Chat Contact Details Sidebar -->
    <chat-active-chat-content-details-sidedbar
      :shall-show-active-chat-contact-sidebar.sync="shallShowActiveChatContactSidebar"
      :contact="state.activeChat.contact || {}"
    />
    <!-- Sidebar -->
    <portal to="content-renderer-sidebar-left">
      <chat-left-sidebar
        :chats-contacts="state.chats"
        :contacts="state.contacts"
        :active-chat-contact-id="state.activeChat.contact ? state.activeChat.contact.id : null"
        :shall-show-user-profile-sidebar.sync="shallShowUserProfileSidebar"
        :profile-user-data="userData"
        :profile-user-minimal-data="userData"
        :mq-shall-show-left-sidebar.sync="mqShallShowLeftSidebar"
        @show-user-profile="showUserProfileSidebar"
        @open-chat="openChatOfContact"
      />
    </portal>
  </div>
</template>

<script>
import store from '@/store';
import { formatDateToMonthShort } from '@core/utils/filter'
import axios from '@axios'
import {
  ref, onUnmounted, nextTick,
} from '@vue/composition-api'
import {
  BAvatar, BDropdown, BDropdownItem, BForm, BInputGroup, BFormInput, BButton,
} from 'bootstrap-vue'
import VuePerfectScrollbar from 'vue-perfect-scrollbar'
// import { formatDate } from '@core/utils/filter'
import { $themeBreakpoints } from '@themeConfig'
import { useResponsiveAppLeftSidebarVisibility } from '@core/comp-functions/ui/app'
import ChatLeftSidebar from './ChatLeftSidebar.vue'
import chatStoreModule from './chatStoreModule'
import ChatActiveChatContentDetailsSidedbar from './ChatActiveChatContentDetailsSidedbar.vue'
import ChatLog from './ChatLog.vue'
import useChat from './useChat'
export default {
  components: {
    // BSV
    BAvatar,
    BDropdown,
    BDropdownItem,
    BForm,
    BInputGroup,
    BFormInput,
    BButton,

    // 3rd Party
    VuePerfectScrollbar,

    // SFC
    ChatLeftSidebar,
    ChatActiveChatContentDetailsSidedbar,
    ChatLog,
  },
  setup() {
    const CHAT_APP_STORE_MODULE_NAME = 'app-chat'

    // Register module
    if (!store.hasModule(CHAT_APP_STORE_MODULE_NAME)) store.registerModule(CHAT_APP_STORE_MODULE_NAME, chatStoreModule)

    // UnRegister on leave
    onUnmounted(() => {
      if (store.hasModule(CHAT_APP_STORE_MODULE_NAME)) store.unregisterModule(CHAT_APP_STORE_MODULE_NAME)
    })

    const { resolveAvatarBadgeVariant, fetchChatsAndContacts, state } = useChat()


    // Scroll to Bottom ChatLog
    const refChatLogPS = ref(null)
    const scrollToBottomInChatLog = () => {
      const scrollEl = refChatLogPS.value.$el || refChatLogPS.value
      scrollEl.scrollTop = scrollEl.scrollHeight
    }
    // ------------------------------------------------
    // Chats & Contacts
    // ------------------------------------------------

    fetchChatsAndContacts()

    // ------------------------------------------------
    // Single Chat
    // ------------------------------------------------
    const chatInputMessage = ref('')
    const openChatOfContact = userId => {
      // Reset send message input value
      chatInputMessage.value = ''
      const contact = state.contacts.find(c => c.id === userId)
      if (contact) {
        state.activeChat.contact = contact
      }

      store.dispatch('app-chat/getChat', { userId })
        .then(response => {
          // Set unseenMsgs to 0
          state.activeChat.chat = response.data.chat

          // Scroll to bottom
          nextTick(() => { scrollToBottomInChatLog() })
        })

      // if SM device =>  Close Chat & Contacts left sidebar
      // eslint-disable-next-line no-use-before-define
      mqShallShowLeftSidebar.value = false
    }
		
    const sendMessage = () => {
      if (!chatInputMessage.value) return
      const payload = {
        to: state.activeChat.contact.id,
        // eslint-disable-next-line no-use-before-define
        from: JSON.parse(localStorage.getItem('userData')).id,
        message: chatInputMessage.value,
        fullName: JSON.parse(localStorage.getItem('userData')).fullName,
      }
      store.dispatch('app-chat/sendMessage', payload)
        .then(response => {
          const chat = state.chats.find(c => c.id === state.activeChat.contact.id)
          const newMessageData = response.data;
          // ? If it's not undefined => New chat is created (Contact is not in list of chats)
          if (chat == undefined) {
            state.activeChat.chat.push(newMessageData);
            state.chats.push({
              ...state.activeChat.contact,
              message: newMessageData.message,
              msgDate: newMessageData.created_at,
            })
          } else {
            // Add message to log
            state.activeChat.chat.push(newMessageData)
            document.querySelector('.chat-users-list .active .card-text').innerHTML = newMessageData.message;
            document.querySelector('.chat-users-list .active .chat-meta small').innerHTML = formatDateToMonthShort(newMessageData.created_at, { hour: 'numeric', minute: 'numeric' });
          }
          // Reset send message input value
          chatInputMessage.value = ''

          // Set Last Message for active contact
          // Scroll to bottom
          setTimeout(() => {
            nextTick(() => { scrollToBottomInChatLog() })
          }, 1000);
        })
    }

    const perfectScrollbarSettings = {
      maxScrollbarLength: 150,
    }

    // User Profile Sidebar
    // ? Will contain all details of profile user (e.g. settings, about etc.)
    const profileUserData = ref({})
    // ? Will contain id, name and avatar & status
    const shallShowUserProfileSidebar = ref(false)
    const showUserProfileSidebar = () => {
      store.dispatch('app-chat/getProfileUser')
        .then(response => {
          profileUserData.value = response.data
          shallShowUserProfileSidebar.value = true
        })
    }

    // Active Chat Contact Details
    const shallShowActiveChatContactSidebar = ref(false)

    // UI + SM Devices
    // Left Sidebar Responsiveness
    const { mqShallShowLeftSidebar } = useResponsiveAppLeftSidebarVisibility()
    const startConversation = () => {
      if (store.state.app.windowWidth < $themeBreakpoints.lg) {
        mqShallShowLeftSidebar.value = true
      }
    }

    return {
      fetchChatsAndContacts,
      // Filters
      // formatDate,

      // useChat
      resolveAvatarBadgeVariant,

      // Single Chat
      refChatLogPS,
      chatInputMessage,
      openChatOfContact,
      sendMessage,

      // Profile User Minimal Data
      // User Profile Sidebar
      profileUserData,
      shallShowUserProfileSidebar,
      showUserProfileSidebar,

      // Active Chat Contact Details
      shallShowActiveChatContactSidebar,

      // UI
      perfectScrollbarSettings,

      // UI + SM Devices
      startConversation,
      mqShallShowLeftSidebar,
      state,
      formatDateToMonthShort,
    }
  },
  mounted() {
    Echo.private(`chat.${this.userData.id}`).listen('MessageSend', (e) => {
      const newMessageData = e.message;
      let userFromId = newMessageData.from;
      const contact = this.state.contacts.find(c => c.id === userFromId)
      const chat = this.state.chats.find(c => c.id === userFromId)
      if(this.state.activeChat.contact.id == userFromId) {
        this.state.activeChat.chat.push(newMessageData)
      }
      if (chat == undefined) {
        this.state.chats.push({
          ...contact,
          message: newMessageData.message,
          msgDate: newMessageData.created_at,
          unSeenMsg: 1,
        })
      } else {
        chat['message'] = newMessageData.message;
        chat['msgDate'] = newMessageData.created_at;
        const index = this.state.chats.findIndex(object => {
          return object.id === userFromId;
        });
        if(chat['unSeenMsg'] >= 0) {
          chat['unSeenMsg'] = chat['unSeenMsg']+1;
        } else {
          chat['unSeenMsg'] = 1;
        }
        if(document.querySelectorAll('.chat-users-list li')[index].querySelector('.chat-meta .badge') == null) {
          let span = document.createElement('span');
          span.classList.add('badge', 'float-right', 'badge-primary', 'badge-pill');
          span.textContent = chat['unSeenMsg'];
          document.querySelectorAll('.chat-users-list li')[index].querySelector('.chat-meta').appendChild(span);
        } else {
          document.querySelectorAll('.chat-users-list li')[index].querySelector('.chat-meta .badge').innerHTML = chat['unSeenMsg'];
        }
        document.querySelectorAll('.chat-users-list li')[index].querySelector('.card-text').innerHTML = newMessageData.message;
        document.querySelectorAll('.chat-users-list li')[index].querySelector('.chat-meta small').innerHTML = formatDateToMonthShort(newMessageData.created_at, { hour: 'numeric', minute: 'numeric' });
      }
    });
    Echo.private('typingevent').listenForWhisper('typing', (e) => {
      console.log(e);
      if(e.userId == this.userData.id && e.typing.length > 0) {
        this.typing = true;
        const chat = this.state.chats.find(c => c.id === e.user.id)
        if(chat) {
          const index = this.state.chats.findIndex(object => {
            return object.id === e.user.id;
          });
          document.querySelectorAll('.chat-users-list li')[index].querySelector('.card-text').innerHTML = 'Typing...';
          setTimeout(() => {
            this.typing = false;
            document.querySelectorAll('.chat-users-list li')[index].querySelector('.card-text').innerHTML = chat['message'];
          }, 3000);
        }
      } else {
        this.typing = false; 
      }
    });
  },
  methods: {
    typingEvent(userId) {
      Echo.private('typingevent')
      .whisper('typing', {
        user: this.userData,
        typing: this.chatInputMessage,
        userId: userId
      });
      for (const [index, message] of Object.entries(this.$store.state.messages.messages)) {
        if(message.id == userId) {
          this.$store.state.messages.messages.splice(index, 1);
          this.$store.state.messages.unSeenMsgs--;
          if(document.querySelectorAll('.messages a')[index] !== null && document.querySelectorAll('.messages a')[index] !== undefined) {
            document.querySelectorAll('.messages a')[index].classList.add('removeMessage');
          }
        }
      }
      document.querySelectorAll('.removeMessage').forEach(message => {
        document.querySelector('.nav-item.dropdown-notification .badge.badge-pill').innerHTML -= 1;
        message.remove();
      });
      if(this.state.activeChat.contact.unSeenMsg > 0) {
        this.state.activeChat.contact.unSeenMsg = 0;
        const index = this.state.chats.findIndex(object => {
          return object.id === userId
        });
        try {
          axios.post(`/api/updateType/${userId}`).then(res => {
            if(res) {
              document.querySelectorAll('.chat-users-list li')[index].querySelector('.chat-meta .badge').remove();
            }
          }).catch(err => {
            console.log(err);
          });
        } catch (error) {
          console.log(error);
        }
      }
    },
    clearAll(userId) {
      try {
        axios.post('/api/clearAll/', {id: userId}).then(res => {
          this.state.activeChat= {
            contact: {},
            chat: []
          }
          const index = this.state.chats.findIndex(object => {
            return object.id === userId
          });
          var removeIndex = this.state.chats.map(function(item) { return item.id; }).indexOf(index);
          this.state.chats.splice(removeIndex, 1);
        }).catch(err => {
        });
      } catch (error) {
        console.log(error);
      }
    }
  },
  data() {
    return {
      userData: JSON.parse(localStorage.getItem('userData')),
      typing: false,
    }
  },
}
</script>

<style lang="scss" scoped>

</style>

<style lang="scss">
@import "~@core/scss/base/pages/app-chat.scss";
@import "~@core/scss/base/pages/app-chat-list.scss";
</style>
