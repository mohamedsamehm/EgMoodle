import { ref, toRefs, reactive, watch, computed } from '@vue/composition-api'
import store from '@/store'
import { title } from '@core/utils/filter'
import moment from 'moment'
// Notification
import { useToast } from 'vue-toastification/composition'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'

export default function useAssignments(props, emit) {
  // Use toast
  const toast = useToast()
  const weeks= [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];
  const types = ['One Answer', 'Multiple Answer'];
  // Table Handlers
  const tableColumns = [
    { label: '#', align: 'start', sortable: false, key: 'number',},
    { label: 'Title', key: 'title'},
    { label: 'Course', key: 'course_name' },
    { label: 'Sections', key: 'sections' },
    { label: 'Opens at', key: 'opens_at' },
    { label: 'Duration', key: 'duration' },
    { label: 'Created at', key: 'createdDate' },
    { label: 'Week', key: 'week' },
    { label: 'Actions', key: 'actions' },
  ];
  const tableColumnsMarks = [
    { label: '#', align: 'start', sortable: false, key: 'number',},
    { label: 'Title', key: 'title'},
    { label: 'Course', key: 'course_name' },
    { label: 'Sections', key: 'sections' },
    { label: 'Student Name', key: 'fullName'},
    { label: 'Grade', key: 'grade' },
    { label: 'Submitted at', key: 'createdDate' },
  ];
	const state = reactive({
		loading: true,
    quizzesObj: {},
    courses: [],
    quizzes: [],
    canExam: false,
    quiz: {
      questions: [],
      course: {
        sections: []
      },
      seconds: 0,
      endTime: '',
      hasAttempt: false,
    },
    myQuizData: {
      exam_id: '',
      grade: 0,
      questions: []
    }
	})
  const refetchData = () => {
		fetchQuizzes()
  }
  const fetchCourses = () => {
    store.dispatch('quizzes/fetchCourses')
    .then(res => {
      let coursesObj= {};
      for (const [key, value] of Object.entries(res.data[0])) {
        value['sections'] = [];
        coursesObj[value.course_id] = [];
      }
      for (const [key, value] of Object.entries(res.data[0])) {
        coursesObj[value.course_id].push(value);
      }
      for (const [key, value] of Object.entries(coursesObj)) {
        value.forEach(element => {
          let obj = {};
          obj['section_id'] = element.section_id;
          obj['section_name'] = element.section_name;
          value[0]['sections'].push(obj);
        });
        state.courses.push(value[0]);
      }
    })
    .catch((err) => {
      console.log(err);
    })
    .finally(() => {
      state.loading = false
    });
  }
  const fetchQuizzes = (ctx, callback) => {
    store.dispatch('quizzes/fetchQuizzes')
    .then(res => {
      state.quizzes = [];
      state.quizzesObj = {};
      res.data[0].forEach(element => {
        element['sections'] = [];
        element['sectionsObj'] = {};
        state.quizzesObj[element.material_id] = [];
      });
      res.data[0].forEach(element => {
        state.quizzesObj[element.material_id].push(element);
      });
      let i = 0;
      for (const [key, value] of Object.entries(state.quizzesObj)) {
        i++;
        value.forEach(element => {
          element.opens_at = moment.utc(element.opens_at).format("LLLL");
          element['id'] = element.material_id;
          element['number'] = i;
          value[0]['sections'].push(element.section_name);
          value[0]['sectionsObj'][element.section_id] = element.section_name;
        });
        value[0]['sections'] = value[0]['sections'].toString();
        state.quizzes.push(value[0]);
      }

    })
    .catch((err) => {
      toast({
        component: ToastificationContent,
        props: {
          title: 'Error fetching Quizzes list',
          icon: 'AlertTriangleIcon',
          variant: 'danger',
        },
      })
    })
    .finally(() => {
      state.loading = false
    });
    return state;
  }
  const fetchQuizzesMarks = (params) => {
    store.dispatch('quizzes/fetchQuizzesMarks', params)
    .then(res => {
      console.log(res);
      state.quizzes = [];
      state.quizzesObj = {};
      res.data[0].forEach(element => {
        element['sections'] = [];
        element['sectionsObj'] = {};
        state.quizzesObj[element.material_id] = [];
      });
      res.data[0].forEach(element => {
        state.quizzesObj[element.material_id].push(element);
      });
      let i = 0;
      for (const [key, value] of Object.entries(state.quizzesObj)) {
        i++;
        value.forEach(element => {
          element.opens_at = moment.utc(element.opens_at).format("LLLL");
          element['id'] = element.material_id;
          element['number'] = i;
          value[0]['sections'].push(element.section_name);
          value[0]['sectionsObj'][element.section_id] = element.section_name;
        });
        value[0]['sections'] = value[0]['sections'].toString();
        state.quizzes.push(value[0]);
      }
    })
    .catch((err) => {
      toast({
        component: ToastificationContent,
        props: {
          title: 'Error fetching Quizzes list',
          icon: 'AlertTriangleIcon',
          variant: 'danger',
        },
      })
    })
    .finally(() => {
      state.loading = false
    });
    return state;
  }
  const fetchQuiz = (params) => {
		state.loading = true;
    store.dispatch('quizzes/fetchQuiz', params)
      .then(res => {
				state.loading = false;
        state.quiz = res.data.quiz;
        state.quiz.questions = [];
        state.quiz.course = [];
        state.quiz.sections = [];
        state.courses.forEach(element => {
          if(element['course_id'] == state.quiz.course_id) {
            state.quiz.course = element;
          }
        });
        state.quiz.course.selectedSections = res.data.sections;
        res.data.questions.forEach(question => {
          state.quiz.questions.push({
            id: question.id,
            title: question.title,
            type: question.type,
            choices: []
          });
        });
        for (let indexQuestion = 0; indexQuestion < res.data.questions.length; indexQuestion++) {
          const question = res.data.questions[indexQuestion];
          for (let indexOption = 0; indexOption < res.data.options.length; indexOption++) {
            const option = res.data.options[indexOption];
            if(option.question_id == question.id) {
              state.quiz.questions[indexQuestion].choices.push(
                {
                  id: option.option_id,
                  choice: option.option,
                  answer: (option.answer == 1 ? true : false),
                  index: indexOption+1
                }
              )
            }
          }
        }
			})
      .catch(error => {
				console.log('error ', error)
        if (error.response.status === 404) {
          state.quiz = undefined
        }
      })
	}
  const fetchMyQuiz = (params) => {
    state.loading = true;
    store.dispatch('quizzes/fetchMyQuiz', params)
    .then(res => {
				state.loading = false;
        state.quiz = res.data.quiz;
        state.quiz.questions = [];
        res.data.student_quizzes.forEach(element => {
          if(element['exam_id'] == state.quiz.exam_id) {
            state.quiz.hasAttempt = true;
          }
        });
        res.data.questions.forEach(question => {
          state.quiz.questions.push({
            id: question.id,
            title: question.title,
            type: question.type,
            choices: []
          });
          state.myQuizData.questions.push({
            my_answer_choice_id: [],
            answer_choice_id: [],
            question_id: question.id,
          });
        });
        for (let indexQuestion = 0; indexQuestion < res.data.questions.length; indexQuestion++) {
          const question = res.data.questions[indexQuestion];
          for (let indexOption = 0; indexOption < res.data.options.length; indexOption++) {
            const option = res.data.options[indexOption];
            if(option.question_id == question.id) {
              state.quiz.questions[indexQuestion].choices.push(
                {
                  id: option.option_id,
                  choice: option.option,
                  answer: (option.answer == 1 ? true : false),
                  index: indexOption+1
                }
              )
              if (option.answer) {
                state.myQuizData.questions[indexQuestion].answer_choice_id.push(option.option_id)
              }
            }
          }
        }
        var returned_endate = moment(moment(state.quiz.opens_at)).add(state.quiz.duration);
        state.quiz.endTime = moment(returned_endate).fromNow();
        if(moment(returned_endate).isAfter(moment().format())) {
          state.quiz.seconds = moment(moment(returned_endate, 'HH:mm:ss')).diff(moment(), 'seconds');
          const minutes = Math.floor((state.quiz.seconds % 3600) / 60);
          const seconds = state.quiz.seconds % 60;
          let m = '',
              s = '';
          if(minutes < 10) {
            m = '0' + minutes;
          } else {
            m = minutes;
          }
          if(seconds < 10) {
            s = '0' + seconds;
          } else {
            s = seconds;
          }
          state.quiz.timeLeft = `${m}:${s}`;
          state.canExam = true;
        }
			})
      .catch(error => {
				console.log('error ', error)
        if (error.response.status === 404) {
          state.quiz = undefined
        }
      })
	}
  const fetchQuizResults = (params) => {
    state.loading = true;
    store.dispatch('quizzes/fetchQuizResults', params)
    .then(res => {
				state.loading = false;
        state.quiz = res.data.quiz;
        state.quiz.questions = [];
        res.data.questions.forEach(question => {
          state.quiz.questions.push({
            id: question.id,
            title: question.title,
            type: question.type,
            choices: [],
            grade: 0,
            answersLength: 0,
          });
        });
        for (let indexQuestion = 0; indexQuestion < res.data.questions.length; indexQuestion++) {
          const question = res.data.questions[indexQuestion];
          for (let indexOption = 0; indexOption < res.data.options.length; indexOption++) {
            const option = res.data.options[indexOption];
            let myAnswer = res.data.answers.find(function (answer) { return answer.answer_id === option.option_id });
            if(option.question_id == question.id) {
              state.quiz.questions[indexQuestion].choices.push(
                {
                  id: option.option_id,
                  choice: option.option,
                  answer: (option.answer == 1 ? true : false),
                  index: indexOption+1,
                  myAnswer: (myAnswer ? true : false)
                }
              )
            }
          }
          for (let index = 0; index < state.quiz.questions[indexQuestion].choices.length; index++) {
            let element = state.quiz.questions[indexQuestion].choices[index];
            if(element.answer) {
              state.quiz.questions[indexQuestion].answersLength += 1;
            }
          }
          for (let index = 0; index < state.quiz.questions[indexQuestion].choices.length; index++) {
            let element = state.quiz.questions[indexQuestion].choices[index];
            if(element.answer && element.myAnswer) {
              state.quiz.questions[indexQuestion].grade += 1/state.quiz.questions[indexQuestion].answersLength;
            }
          }
        }
			})
      .catch(error => {
				console.log('error ', error)
        if (error.response.status === 404) {
          state.quiz = undefined
        }
      })
	}
  return {
    weeks,
    fetchQuizzes,
    fetchQuiz,
    fetchMyQuiz,
    fetchCourses,
    fetchQuizResults,
    tableColumns,
    tableColumnsMarks,
		state,
    types,
    refetchData,
    fetchQuizzesMarks
  }
}
