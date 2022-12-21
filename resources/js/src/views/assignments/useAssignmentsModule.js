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
    fetchMarks(ctx, { id }) {
      return new Promise((resolve, reject) => {
        axios
          .get(`/api/markAssignments/${id}`)
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },
    fetchAssignments(ctx, queryParams) {
      return new Promise((resolve, reject) => {
        axios
          .get('/api/assignments/professor/', { params: queryParams })
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },
    fetchAssignment(ctx, { id }) {
      return new Promise((resolve, reject) => {
        axios
          .get(`/api/assignments/${id}`)
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },
    fetchMyAssignment(ctx, { id }) {
      return new Promise((resolve, reject) => {
        axios
          .get(`/api/myassignment/${id}`)
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },
    addAssignment(ctx, AssignmentData, config) {
      return new Promise((resolve, reject) => {
        axios
          .post('/api/assignments/professor/store', AssignmentData, config)
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },
    storeSubmittedAssignment(ctx, AssignmentData, config) {
      return new Promise((resolve, reject) => {
        axios
          .post('/api/student_assignments/store', AssignmentData, config)
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },
    updateSubmittedAssignment(ctx, AssignmentData, config) {
      return new Promise((resolve, reject) => {
        axios
          .post('/api/student_assignments/update', AssignmentData, config)
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },
    updateAssignment(ctx, AssignmentData, config) {
      return new Promise((resolve, reject) => {
        axios
          .post('/api/assignments/professor/update', AssignmentData, config)
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },
    updateGrade(ctx, obj) {
      return new Promise((resolve, reject) => {
        axios
          .post('/api/saveGrade', obj)
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },
    deleteAssignment(ctx, id) {
      return new Promise((resolve, reject) => {
        axios
          .post('/api/materials/destroy', { id })
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },
  },
}
