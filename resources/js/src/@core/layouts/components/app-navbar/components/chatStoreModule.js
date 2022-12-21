import axios from '@axios'

export default {
  namespaced: true,
  state: {
    messages: [],
    unSeenMsgs: 0,
  },
  getters: {},
  mutations: {
		UPDATE_MESSAGES(state, payload) {
      state.messages = payload
    },
    UPDATE_UNSEENMESSAGS(state, payload) {
      state.unSeenMsgs = payload
    },
	},
  actions: {
    fetchChats(ctx) {
      return new Promise((resolve, reject) => {
        axios
          .get('/api/messages')
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },
  },
}
