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
    fetchQuizzes(ctx, queryParams) {
      return new Promise((resolve, reject) => {
        axios
          .get('/api/quizzes/professor/', { params: queryParams })
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },
    addQuiz(ctx, QuizData) {
      return new Promise((resolve, reject) => {
        axios
          .post('/api/quizzes/professor/store', QuizData)
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },
    updateQuiz(ctx, QuizData) {
      return new Promise((resolve, reject) => {
        axios
          .post('/api/quizzes/professor/update', QuizData)
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },
    fetchQuiz(ctx, { id }) {
      return new Promise((resolve, reject) => {
        axios
          .get(`/api/quizzes/${id}`)
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },
    fetchQuizzesMarks(ctx, { id }) {
      return new Promise((resolve, reject) => {
        axios
          .get(`/api/quizzes/grades/${id}`)
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },
    fetchMyQuiz(ctx, { id }) {
      return new Promise((resolve, reject) => {
        axios
          .get(`/api/myquiz/${id}`)
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },
    fetchQuizResults(ctx, { id }) {
      return new Promise((resolve, reject) => {
        axios
          .get(`/api/myquiz/results/${id}`)
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },
    deleteQuiz(ctx, id) {
      return new Promise((resolve, reject) => {
        axios
          .post('/api/materials/destroy', { id })
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },
    storeMyQuiz(ctx, QuizData) {
      return new Promise((resolve, reject) => {
        axios
          .post('/api/quiz/store', QuizData)
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },
  },
}
