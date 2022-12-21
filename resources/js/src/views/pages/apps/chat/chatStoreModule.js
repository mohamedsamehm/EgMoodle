import axios from '@axios'

export default {
  namespaced: true,
  state: {},
  getters: {},
  mutations: {},
  actions: {
    fetchChatsAndContacts() {
      return new Promise((resolve, reject) => {
        axios
          .get('/api/usersList')
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },
    getProfileUser() {
      return new Promise((resolve, reject) => {
        axios
          .get('/apps/chat/users/profile-user')
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },
    getChat(ctx, { userId }) {
      return new Promise((resolve, reject) => {
        axios
          .get(`/api/getChat/${userId}`)
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },
    sendMessage(ctx, obj) {
      return new Promise((resolve, reject) => {
        axios
          .post('/api/sendMessage/', obj)
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },
  },
}
