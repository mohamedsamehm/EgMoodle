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
    addSurvey(ctx, SurveyData) {
      return new Promise((resolve, reject) => {
        axios
          .post('/api/survey/store', SurveyData)
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },
    fetchSurvey(ctx, { id }) {
      return new Promise((resolve, reject) => {
        axios
          .get(`/api/survey/show/${id}`)
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },
    fetchCourses(ctx) {
      return new Promise((resolve, reject) => {
        axios
          .get('/api/lecture/')
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },
    fetchCourseStudent(ctx, { id }) {
      return new Promise((resolve, reject) => {
        axios
          .get(`/api/course_material/show_student/${id}`)
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },
    fetchCourseProfessor(ctx, { id }) {
      return new Promise((resolve, reject) => {
        axios
          .get(`/api/course_material/show_professor/${id}`)
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },
    fetchSurveyQuestions(ctx) {
      return new Promise((resolve, reject) => {
        axios
          .get('/api/survey/questions')
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },
  },
}
