import axios from '@axios'

export default {
  namespaced: true,
  state: {
		formLoading: false,
	},
  getters: {

	},
  mutations: {
		CHANGE_FORM_LOADING(state, payload) {
      state.formLoading = payload
    },
	},
  actions: {
    fetchMyAttendances(ctx) {
      return new Promise((resolve, reject) => {
        axios
          .get('/api/meetings/viewMyAttendances')
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },
    fetchStudentCourses(ctx, AttendnaceData) {
      return new Promise((resolve, reject) => {
        axios
          .get('/api/enroll', { params: AttendnaceData })
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },
  },
}
