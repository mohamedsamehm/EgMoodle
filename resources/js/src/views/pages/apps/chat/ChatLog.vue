<template>
  <div class="chats">
    <div
      v-for="(msgGrp, index) in formattedChatData.formattedChatLog"
      :key="index"
      class="chat"
      :class="{'chat-left': msgGrp.senderId === formattedChatData.contact.id}"
    >
      <div class="chat-avatar">
        <b-avatar
          size="36"
          class="avatar-border-2 box-shadow-1"
          variant="transparent"
          :src="msgGrp.senderId === formattedChatData.contact.id ? formattedChatData.contact.avatar : formattedChatData.profileUserAvatar"
        />
      </div>
      <div class="chat-body">
        <div
          v-for="msgData in msgGrp.messages"
          :key="msgData.time"
          class="chat-content"
        >
          <p>{{ msgData.msg }}</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { computed } from '@vue/composition-api'
import { BAvatar } from 'bootstrap-vue'

export default {
  components: {
    BAvatar,
  },
  props: {
    chatData: {
      type: Object,
      required: true,
    },
  },
  setup(props) {
    const formattedChatData = computed(() => {
      const myId = JSON.parse(localStorage.getItem('userData')).id;
      const profileUserAvatar = JSON.parse(localStorage.getItem('userData')).avatar;
      const contact = {
        id: props.chatData.contact.id,
        avatar: props.chatData.contact.avatar,
      }

      let chatLog = []
      if (props.chatData.chat) {
        chatLog = props.chatData.chat
      }
      const formattedChatLog = []
      let msgGroup = {}

      chatLog.forEach((msg, index) => {
        if (myId === msg.from) {
          msgGroup = {
            sender: myId,
            messages: [{
              msg: msg.message,
              time: msg.created_at,
            }],
          }
        } else {
          msgGroup = {
            senderId: msg.from,
            messages: [{
              msg: msg.message,
              time: msg.created_at,
            }],
          }
        }
        formattedChatLog.push(msgGroup)
      })
      return {
        formattedChatLog,
        contact,
        profileUserAvatar,
      }
    })

    return {
      formattedChatData,
    }
  },
}
</script>

<style>

</style>
