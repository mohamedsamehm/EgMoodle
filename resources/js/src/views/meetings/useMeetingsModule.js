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
    fetchMeetings(ctx, queryParams) {
      return new Promise((resolve, reject) => {
        axios
          .get('/api/meetings/professor/', { params: queryParams })
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },
    fetchMeeting(ctx, { id }) {
      return new Promise((resolve, reject) => {
        axios
          .get(`/api/meetings/${id}`)
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },
    fetchStudents(ctx, { id }) {
      return new Promise((resolve, reject) => {
        axios
          .get(`/api/meetings/getAllStudents/${id}`)
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },
    fetchStudentsForMeetings(ctx, { id }) {
      return new Promise((resolve, reject) => {
        axios
          .get(`/api/meetings/getAllStudentsForMeetings/${id}`)
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },
    addMeeting(ctx, MeetingData) {
      return new Promise((resolve, reject) => {
        axios
          .post('/api/meetings/professor/store', MeetingData)
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },
    submitAttendance(ctx, MeetingData) {
      return new Promise((resolve, reject) => {
        axios
          .post('/api/meetings/store', MeetingData)
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },
    updateMeeting(ctx, MeetingData) {
      return new Promise((resolve, reject) => {
        axios
          .post('/api/meetings/professor/update', MeetingData)
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },
    deleteMeeting(ctx, id) {
      return new Promise((resolve, reject) => {
        axios
          .post('/api/materials/destroy', { id })
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },
  },
}
