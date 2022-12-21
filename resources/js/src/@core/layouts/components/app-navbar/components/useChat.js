import store from '@/store'
import { ref, toRefs, reactive, watch, computed } from '@vue/composition-api'
import { title } from '@core/utils/filter'
export default function useChat() {
  const state = reactive({
    messages: [],
    unSeenMsgs: 0,
	})
  const fetchChats = () => {
    store.dispatch('messages/fetchChats')
    .then(res => {
      state.messages = [];
      for (const [key, value] of Object.entries(res.data.chats)) {
        if(value.type == 0) {
          state.messages.push({
            id: value.id,
            title: value.fullName,
            message: value.message,
            avatar: value.avatar,
          });
          state.unSeenMsgs++;
        }
      }
      store.commit("messages/UPDATE_MESSAGES", state.messages);
      store.commit("messages/UPDATE_UNSEENMESSAGS", state.unSeenMsgs);
    })
    .catch((err) => {
      console.log(err);
    })
    return state;
  }

  return {
    fetchChats,
    state,
  }
}