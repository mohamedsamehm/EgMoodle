import { ref, toRefs, reactive, watch, computed } from '@vue/composition-api'
import store from '@/store'
import { title } from '@core/utils/filter'
import moment from 'moment'

// Notification
import { useToast } from 'vue-toastification/composition'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'
import axios from 'axios';

export default function useCourse(props, emit) {
  // Use toast
  const toast = useToast()
  let course_material = {}
  let readArr = {}
  let timeNow = new Date();
  let timeLeft = '';
  const sortBy = ref({ text: 'All', value: 'all' })
  const sortByOptions = [
    { text: 'All', value: 'all' },
    { text: 'Assignments', value: 'assignments' },
    { text: 'Quizzes', value: 'quizzes' },
  ]
  // Table Handlers
	const state = reactive({
		loading: true,
    course: [],
    mySurvey: false,
    questions: [],
    weeks: [
      {id: 1, value: []},
      {id: 2, value: []},
      {id: 3, value: []},
      {id: 4, value: []},
      {id: 5, value: []},
      {id: 6, value: []},
      {id: 7, value: []},
      {id: 8, value: []},
      {id: 9, value: []},
      {id: 10, value: []},
      {id: 11, value: []},
      {id: 12, value: []},
    ],
    course_material_all: [],
    myQuizzes: [],
    myAssignments: {},
    quizzesObj: {},
    assignmentsObj: {},
    readedText: {},
    emptyWeeks: true,
	})
  const fetchCourses = () => {
    store.dispatch('course/fetchCourses')
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
  const fetchSurveyQuestions = () => {
    store.dispatch('course/fetchSurveyQuestions')
    .then(res => {
      state.questions = res.data[0];
    })
    .catch((err) => {
      console.log(err);
    })
  }
  const fetchSurvey = (params) => {
    store.dispatch('course/fetchSurvey', params)
    .then(res => {
      if(res.data[0].length == 0) state.mySurvey = true;
    })
    .catch((err) => {
      console.log(err);
    })
  }
  const fetchCourseStudent = (params) => {
    store.dispatch('course/fetchCourseStudent', params)
    .then(res => {
      course_material = res.data[0];
      state.assignmentsObj = res.data[2];
      state.quizzesObj = res.data[4];
      state.course = res.data[1][0];
      res.data[5].forEach(element => {
        state.myQuizzes.push(element);
      });
      if(Object.keys(res.data[4]).length > 0) {
        for (const [key, value] of Object.entries(state.quizzesObj)) {
          var returned_endate = moment(moment(value[0].opens_at)).add(value[0].duration);
          if(moment(value[0].opens_at).isAfter(moment().format())) {
            value[0].quizText = 'Quiz Will Open at:';
            value[0].alertColor = "info";
            value[0].quizTime = moment(value[0].opens_at).format("LLLL");
          } else {
            value[0].quizEndTime = moment(returned_endate).fromNow();
            const result = state.myQuizzes.find(quiz => quiz.exam_id === value[0].exam_id);
            if(result) {
              if(moment(returned_endate).isAfter(moment().format())) {
                value[0].quizText = 'Results will be availabe';
                value[0].quizTime = value[0].quizEndTime;
                value[0].alertColor = "info";
              } else {
                value[0].buttonColor = "success";
                value[0].quizText = 'View results';
                value[0].route = "quiz-results";
              }
            } else {
              if(moment(returned_endate).isAfter(moment().format())) {
                value[0].quizText = 'Attempt Quiz Now';
                value[0].buttonColor = "primary";
                value[0].route = "quiz-submit";
              } else {
                value[0].quizText = 'Quiz has been Ended from';
                value[0].alertColor = "warning";
                value[0].quizTime = value[0].quizEndTime;
              }
            }
          }
        }
      }
      if(res.data[3].length > 0) {
        res.data[3].forEach(element => {
          state.myAssignments[element.material_id] = element;
        });
      }
      if(Object.keys(course_material).length > 0) {
        state.emptyWeeks = false;
        for (const [key, value] of Object.entries(course_material)) {
          let material = value[0];
          state.weeks.forEach(week => {
            if(material.week == week.id) {
              week.value.push(material);
              try {
                axios.post('/api/material/show', {material_id: material.id}).then(res => {
                  if(res.data.length == 0) {
                    material['read'] = 0;
                    readArr['material_id'] = material.id;
                    state.readedText[material.id] = "Mark as readed";
                    try {
                      axios.post('/api/material/store', readArr).then(res => {
                        material['read_id'] = res.data.last_insert_id;
                      }).catch(err => {
                        console.log(err)
                      });
                    } catch (error) {
                      console.log(error);
                    }
                  } else {
                    material['content'] =  material['content'] + ' ';
                    material['read'] = res.data[0].read;
                    material['read_id'] = res.data[0].id;
                    if(res.data[0].read == 0) {
                      state.readedText[material.id] = "Mark as readed";
                    } else {
                      state.readedText[material.id] = "Done";
                    }
                  }
                }).catch(err => {
                  console.log(err)
                });
              } catch (error) {
                console.log(error);
              }
            }
          });
        }
        for (const [key, value] of Object.entries(course_material)) {
          state.course_material_all.push(value[0]);
        }
        for (const [keyCourseMaterial, valueCourseMaterial] of Object.entries(state.course_material_all)) {
          for (const [keyAssignment, valueAssignment] of Object.entries(state.assignmentsObj)) {
            if(valueCourseMaterial['id'] == valueAssignment[0]['material_id']) {
              valueAssignment[0]['id'] = valueAssignment[0]['material_id'];
              state.course_material_all[keyCourseMaterial] = valueAssignment[0];
            }
          }
        }
      } else {
        state.emptyWeeks = true;
      }
    })
    .catch((err) => {
      console.log(err);
    })
    .finally(() => {
      state.loading = false
    });
  }
  const fetchCourseProfessor = (params) => {
    store.dispatch('course/fetchCourseProfessor', params)
    .then(res => {
      course_material = res.data[0];
      state.assignmentsObj = res.data[2];
      state.course = res.data[3][0];
      state.quizzesObj = res.data[4];
      res.data[3][0]['sections'] = [];
      if(Object.keys(res.data[3]).length > 0) {
        for (const [key, value] of Object.entries(res.data[3])) {
          res.data[3][0]['sections'].push(value['section_name']);
        }
      }
      if(Object.keys(course_material).length > 0) {
        state.emptyWeeks = false;
        for (const [key, value] of Object.entries(course_material)) {
          value[0]['sections'] = [];
        }
        for (const [key, value] of Object.entries(res.data[1])) {
          for (const [keyCourse_material, valueCourse_material] of Object.entries(course_material)) {
            if(key == keyCourse_material) {
              value.forEach(val => {
                valueCourse_material[0]['sections'].push(val['section_name']);
              });
            }
          }
        }
        for (const [key, value] of Object.entries(course_material)) {
          state.weeks.forEach(week => {
            for (let val = 0; val < value.length; val++) {
              if(value[val].week == week.id) {
                week.value.push(value[0]);
                break;
              }
            }
          });
        }
        for (const [key, value] of Object.entries(course_material)) {
          state.course_material_all.push(value[0]);
        }
        for (const [keyCourseMaterial, valueCourseMaterial] of Object.entries(state.course_material_all)) {
          for (const [keyAssignment, valueAssignment] of Object.entries(state.assignmentsObj)) {
            valueAssignment[0]['assignment_id'] = valueAssignment[1]['id'];
            if(valueCourseMaterial['id'] == valueAssignment[0]['material_id']) {
              valueAssignment[0]['id'] = valueAssignment[0]['material_id'];
              state.course_material_all[keyCourseMaterial] = valueAssignment[0];
            }
          }
        }
      } else {
        state.emptyWeeks = true;
      }
    })
    .catch((err) => {
      console.log(err);
    })
    .finally(() => {
      state.loading = false
    });
  }

  return {
    fetchCourseStudent,
    fetchCourseProfessor,
    fetchCourses,
    fetchSurveyQuestions,
		state,
    sortBy,
    sortByOptions,
    fetchSurvey,
  }
}
