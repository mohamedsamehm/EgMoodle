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
    fetchCourses(ctx) {
      return new Promise((resolve, reject) => {
        axios
          .get('/api/lecture/')
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },
    fetchMyAttendances(ctx) {
      return new Promise((resolve, reject) => {
        axios
          .get('/api/meetings/viewMyAttendances')
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },
    fetchAttendances(ctx, AttendnaceData) {
      return new Promise((resolve, reject) => {
        axios
          .get('/api/meetings/viewAttendance', { params: AttendnaceData })
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },
    fetchStudentAttendances(ctx, AttendnaceData) {
      return new Promise((resolve, reject) => {
        axios
          .get('/api/meetings/viewStudentAttendance', { params: AttendnaceData })
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
