import store from '@/store'
import { ref, toRefs, reactive, watch, computed } from '@vue/composition-api'
import { title } from '@core/utils/filter'
import moment from 'moment'
export default function useChat() {
  const resolveAvatarBadgeVariant = status => {
    if (status === 'online') return 'success'
    if (status === 'busy') return 'danger'
    if (status === 'away') return 'warning'
    return 'secondary'
  }
  const state = reactive({
    contacts: [],
    chats: [],
    activeChat: {
      contact: {},
      chat: []
    }
	})
  const fetchChatsAndContacts = () => {
    let myId = JSON.parse(localStorage.getItem('userData')).id
    store.dispatch('app-chat/fetchChatsAndContacts')
    .then(res => {
      state.contacts = res.data.contacts;
      state.contacts.forEach(element => {
        element.unSeenMsg = 0;
        let adding = false;
        for (const [key, value] of Object.entries(res.data.chats)) {
          if (element.id == key) {
            for (const [k, val] of Object.entries(value)) {
              element.message = val['message'];
              element.msgDate = val['created_at'];
              break;
            }
            for (const [k, val] of Object.entries(value)) {
              if(val['type'] == 0) {
                element.unSeenMsg++;
              }
            }
            if(element.unSeenMsg == 0) {
              for (const [k, val] of Object.entries(res.data.chats[myId])) {
                if(element.id == val['to']) {
                  if(moment(value[0]['created_at']).isBefore(val['created_at'])) {
                    element.message = val['message'];
                    element.msgDate = val['created_at'];
                  } else {
                    element.message = value[0]['message'];
                    element.msgDate = value[0]['created_at'];
                  }
                  break;
                }
              }
            }
          } else if(myId == key) {
            if(element.unSeenMsg == 0) {
              for (const [k, val] of Object.entries(res.data.chats[myId])) {
                if(element.id == val['to']) {
                  element.message = val['message'];
                  element.msgDate = val['created_at'];
                  break;
                }
              }
            }
          }
        }
        for (const [key, value] of Object.entries(res.data.chats)) {
          if(myId == key) {
            for (const [k, val] of Object.entries(value)) {
              if (element.id == val['to']) {
                state.chats.push(element);
                adding = true;
                break;
              } else {
                adding = false;
                continue;
              }
            }
            if (adding) {
              break;
            }
          } else if (element.id == key) {
            state.chats.push(element);
            adding = true;
            break;
          } else {
            adding = false;
            continue;
          }
        }
      });
    })
    .catch((err) => {
      console.log(err);
    })
  }

  return {
    fetchChatsAndContacts,
    resolveAvatarBadgeVariant,
    state,
  }
}